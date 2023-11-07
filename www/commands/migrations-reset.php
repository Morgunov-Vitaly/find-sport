<?php

require_once  __DIR__ . DIRECTORY_SEPARATOR . 'init.php';

$dbMigrations = new lib\DbMigrations();

$dbMigrations::dropSessionMembersTable();
$dbMigrations::dropClientsTable();
$dbMigrations::dropSessionsTable();
$dbMigrations::dropSessionConfigurationsTable();
echo 'Таблицы БД удалены' . PHP_EOL;