<?php

declare(strict_types=1);

namespace lib;

use PDO;
use PDOException;
use PDOStatement;
use RuntimeException;

class DbConnection
{
    private PDOStatement $stmt;
    public PDO $pdo;

    private static ?DbConnection $instance = null;

    private const DB_NAME = 'fs_app_db';
    private const DB_HOST = 'fs_app_mysql';
    private const DB_USER_NAME = 'fs_app';
    private const DB_USER_PASSWORD = 'fs_app';

    protected function __construct()
    {
        try {
            $this->pdo = new PDO(
                'mysql:dbname=' . self::DB_NAME . ';host=' . self::DB_HOST,
                self::DB_USER_NAME,
                self::DB_USER_PASSWORD
            );
        } catch (PDOException $e) {
            die("Ошибка подключения: " . $e->getMessage() . PHP_EOL);
        }
    }

    /**
     * Singletons should not be cloneable.
     */
    protected function __clone()
    {
    }

    /**
     * Singletons should not be restorable from strings.
     */
    public function __wakeup()
    {
        throw new RuntimeException("Cannot unserialize a singleton.");
    }

    public static function getInstance(): null|static
    {
        if (!self::$instance) {

            self::$instance = new static();
        }

        return self::$instance;
    }

    public function query(string $sql, array $bindingParams = []): false|PDOStatement
    {

        if (empty($bindingParams)) {
            return $this->stmt = $this->pdo->query($sql);
        }

        $query = $sql; // ex. SELECT * FROM table WHERE field = :param;
        $this->stmt = $this->pdo->prepare($query);

        foreach ($bindingParams as $param => $value) {
            $this->stmt->bindParam(":$param", $value);
        }

        return $this->stmt;
    }

    /**
     * Для выполнения запросов типа INSERT, UPDATE, DELETE
     */
    public function execute(string $sql, array $bindingParams = []): ?int
    {
        if (!empty($bindingParams)) {
            $this->stmt = $this->pdo->prepare($sql);

            foreach ($bindingParams as $param => $value) {
                $this->stmt->bindParam(":$param", $value);
            }

            $this->stmt->execute();
        } else {
            $this->stmt = $this->pdo->query($sql);
        }

        return $this->stmt->rowCount();
    }

    public function fetch(): ?array
    {
        if (!$this->stmt->execute()) {
            return null;
        }

        $rows = [];

        while ($row = $this->stmt->fetch(PDO::FETCH_ASSOC)) {
            $rows[] = $row;
        }

        return $rows;
    }
}