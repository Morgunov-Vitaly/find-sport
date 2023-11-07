<?php

namespace lib;

class DbSeeds
{

    public static function seedClientsTable(): void
    {
        /** @var DbConnection $connection */
        $connection = DbConnection::getInstance();

        $sql = "insert into `clients` values
            (1,'Иван', 'Иванов'),
            (2,'Василиса', 'Краснова')";

        $connection->query($sql);
    }

    public static function seedSessionConfigurationsTable(): void
    {
        /** @var DbConnection $connection */
        $connection = DbConnection::getInstance();

        $sql = "insert into `session_configurations` values
            (1,1,'17:00:00','60','2023-08-21'),
            (2,2,'17:00:00','60','2023-08-22')";

        $connection->query($sql);
    }

    public static function seedSessionsTable(): void
    {
        /** @var DbConnection $connection */
        $connection = DbConnection::getInstance();

        $sql = "insert into `sessions` values
            (1,'2023-08-21 17:00:00',1),
            (2,'2023-08-28 17:00:00',1),
            (3,'2023-08-22 17:00:00',2),
            (4,'2023-08-29 17:00:00',2),
            (5,'2023-08-21 17:00:00',1),
            (6,'2023-08-22 17:00:00',2),
            (7,'2023-08-22 17:00:00',2)";

        $connection->query($sql);
    }

    public static function seedSessionMembersTable(): void
    {
        /** @var DbConnection $connection */
        $connection = DbConnection::getInstance();

        $sql = "insert into `session_members` values
           (1,1,1),
           (2,1,2),
           (3,2,1),
           (4,2,2),
           (5,3,1),
           (6,3,2),
           (7,5,1),
           (8,7,1),
           (9,7,2)";

        $connection->query($sql);
    }
}