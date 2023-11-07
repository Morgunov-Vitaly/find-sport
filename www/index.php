<?php

use lib\DbConnection;

require_once './commands/init.php';

echo 'СТАРТ СКРИПТА ПО ИСПРАВЛЕНИЮ ПРОБЛЕМЫ С ДУБЛЯМИ' . PHP_EOL;

try {
    /** @var DbConnection $connection */
    $connection = DbConnection::getInstance();
    $connection->pdo->beginTransaction();
    echo 'Оборачиваем манипуляции с таблицами в транзакцию, чтобы не нарушились связи между таблицами. '
        . PHP_EOL
        . '(В зависимости от условий это может быть избыточным действием)' . PHP_EOL;

    fixSessionMembersRecordsWithWrongSessionIds($connection);
    deleteSessionMembersDoubles($connection);
    deleteSessionsDoubles($connection);
    echo 'СКРИПТ ВЫПОЛНЕН'
        . PHP_EOL
        .'Дубли записей в таблице sessions удалены. А записи таблицы session_members теперь ссылаются на оригинальные сессии'
        . PHP_EOL;
    echo '*** Желательно добавить определение uniq в таблицу sessions, чтобы больше такого не повторялось***' . PHP_EOL;

    $connection->pdo->commit();
} catch (PDOException $e) {
    // Если произошла ошибка, откатите транзакцию
    $connection->pdo->rollBack();
    echo "Произошла ошибка: " . $e->getMessage();
}

die();

/**
 * В поле session_id таблицы session_members
 * меняем значения ссылающиеся на дубли в таблице sessions
 * на индексы "оригинальных" записей - созданных первыми (с наименьшим индексом)
 */
function fixSessionMembersRecordsWithWrongSessionIds(DbConnection $connection): void
{
    $sql = <<<EOL
UPDATE session_members
    LEFT JOIN (SELECT dbls.id as dbls_session_id, orig.orig_session_id
               FROM (SELECT sessions.id, sessions.start_time, sessions.session_configuration_id
                     FROM sessions
                              LEFT JOIN (SELECT MIN(id) as id
                                         FROM sessions
                                         GROUP BY start_time, session_configuration_id) as originals
                                        ON sessions.id = originals.id
                     WHERE originals.id IS NULL) as dbls
                        LEFT JOIN (SELECT MIN(id) as orig_session_id, start_time, session_configuration_id
                                   FROM sessions
                                   GROUP BY start_time, session_configuration_id) as orig
                                  ON (orig.start_time = dbls.start_time AND
                                      orig.session_configuration_id = dbls.session_configuration_id)) AS tmp
    ON session_members.session_id = tmp.dbls_session_id
SET session_members.session_id = tmp.orig_session_id
WHERE tmp.dbls_session_id IS NOT NULL;
EOL;

    $count = $connection->execute($sql);

    echo "Исправлено записей в таблице session_members ссылающихся на сессии - дубли: $count" . PHP_EOL;
}

/**
 * Удаляем дубли в таблице session_members
 */
function deleteSessionMembersDoubles(DbConnection $connection): void
{
    $sql = <<<EOL
DELETE session_members FROM session_members
LEFT JOIN (
    SELECT MIN(id) as id, session_id, client_id FROM session_members
    GROUP BY session_id, client_id
) as origs
                       ON session_members.id = origs.id
WHERE origs.id IS NULL;
EOL;

    $count = $connection->execute($sql);

    echo "Удалено дублей в таблице session_members: $count" . PHP_EOL;
}

/**
 * Удаляем дубли в таблице sessions
 */
function deleteSessionsDoubles(DbConnection $connection): void
{
    $sql = <<<EOL
DELETE sessions FROM sessions
LEFT JOIN (
    SELECT MIN(id) as id, start_time, session_configuration_id FROM sessions
    GROUP BY start_time, session_configuration_id
) as origs
                       ON sessions.id = origs.id
WHERE origs.id IS NULL;
EOL;

    $count = $connection->execute($sql);

    echo "Удалено дублей в таблице sessions: $count" . PHP_EOL;
}
