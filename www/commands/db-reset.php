<?php

require_once  __DIR__ . DIRECTORY_SEPARATOR . 'init.php';

$dbMigrations = new lib\DbMigrations();
$dbMigrations::dropSessionMembersTable();
$dbMigrations::dropClientsTable();
$dbMigrations::dropSessionsTable();
$dbMigrations::dropSessionConfigurationsTable();
echo 'Таблицы БД удалены' . PHP_EOL;

$dbMigrations::createClientsTable();
$dbMigrations::createSessionConfigurationsTable();
$dbMigrations::createSessionsTable();
$dbMigrations::createSessionMembersTable();
echo 'Созданы новые таблицы' . PHP_EOL;

$dbSeeder = new lib\DbSeeds();

$dbSeeder::seedClientsTable();
$dbSeeder::seedSessionConfigurationsTable();
$dbSeeder::seedSessionsTable();
$dbSeeder::seedSessionMembersTable();
echo 'Таблицы заполнены исходными данными' . PHP_EOL;