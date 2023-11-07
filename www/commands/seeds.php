<?php

require_once  __DIR__ . DIRECTORY_SEPARATOR . 'init.php';

$dbSeeder = new lib\DbSeeds();

$dbSeeder::seedClientsTable();
$dbSeeder::seedSessionConfigurationsTable();
$dbSeeder::seedSessionsTable();
$dbSeeder::seedSessionMembersTable();
echo 'Таблицы заполнены исходными данными' . PHP_EOL;