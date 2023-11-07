<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'init.php';

$dbMigrations = new lib\DbMigrations();

$dbMigrations::createClientsTable();
$dbMigrations::createSessionConfigurationsTable();
$dbMigrations::createSessionsTable();
$dbMigrations::createSessionMembersTable();
echo 'Созданы новые таблицы' . PHP_EOL;