<?php
namespace lib;

class DbMigrations
{
    public static function createClientsTable(): void
    {
        /** @var DbConnection $connection */
        $connection = DbConnection::getInstance();

        $sql = "create table IF NOT EXISTS clients
            (
            id            bigint unsigned auto_increment primary key,
            first_name    varchar(255)            not null,
            last_name     varchar(255)            null
            )  collate = utf8mb4_unicode_ci";

        $connection->query($sql);
    }

    public static function createSessionConfigurationsTable(): void
    {
        /** @var DbConnection $connection */
        $connection = DbConnection::getInstance();

        $sql = "create table IF NOT EXISTS session_configurations
            (
            id               bigint unsigned auto_increment primary key,
            day_number       int          not null,
            start_time       time         not null,
            duration_minutes varchar(255) not null,
            start_date       datetime     null
            ) collate = utf8mb4_unicode_ci";

        $connection->query($sql);
    }

    public static function createSessionsTable(): void
    {
        /** @var DbConnection $connection */
        $connection = DbConnection::getInstance();

        $sql = "create table IF NOT EXISTS sessions
            (
            id                       bigint unsigned auto_increment primary key,
            start_time               datetime        not null,
            session_configuration_id bigint unsigned not null
            ) collate = utf8mb4_unicode_ci";

        $connection->query($sql);
    }

    public static function createSessionMembersTable(): void
    {
        /** @var DbConnection $connection */
        $connection = DbConnection::getInstance();

        $sql = "create table IF NOT EXISTS session_members
            (
            id              bigint unsigned auto_increment primary key,
            session_id      bigint unsigned not null,
            client_id       bigint unsigned not null,
            constraint session_members_session_id_foreign
            foreign key (session_id) references sessions (id)
            on delete cascade,
            constraint session_members_client_id_foreign
            foreign key (client_id) references clients (id)
            on delete cascade
            ) collate = utf8mb4_unicode_ci";

        $connection->query($sql);
    }

    public static function dropClientsTable(): void
    {
        /** @var DbConnection $connection */
        $connection = DbConnection::getInstance();
        $connection->query('DROP TABLE IF EXISTS clients');
    }

    public static function dropSessionConfigurationsTable(): void
    {
        /** @var DbConnection $connection */
        $connection = DbConnection::getInstance();

        $connection->query('DROP TABLE IF EXISTS session_configurations');
    }

    public static function dropSessionsTable(): void
    {
        /** @var DbConnection $connection */
        $connection = DbConnection::getInstance();

        $connection->query('DROP TABLE IF EXISTS sessions');
    }

    public static function dropSessionMembersTable(): void
    {
        /** @var DbConnection $connection */
        $connection = DbConnection::getInstance();

        $connection->query('DROP TABLE IF EXISTS session_members');
    }
}