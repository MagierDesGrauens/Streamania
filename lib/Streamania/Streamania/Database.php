<?php

namespace Streamania;

use PDO;

class Database
{
    private static $pdo;
    private static $stm;
    private static $lastInsertId;

    public static function connect(): bool
    {
        $dsn = sprintf(
            'mysql:dbname=%s;host=%s',
            Config::value('Database', 'db'),
            Config::value('Database', 'host')
        );

        try {
            $dbh = new PDO(
                $dsn,
                Config::value('Database', 'user'),
                Config::value('Database', 'password')
            );
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
            return false;
        }

        return true;
    }

    public static function fetch(string $sql, array $params = []): mixed
    {
        self::$stm = self::$pdo->prepare($sql);

        foreach ($params as $key => $value) {
            if (is_int($key)) {
                $key++;
            }

            echo "bind" . $key . "-".$value;

            self::$stm->bindValue($key, $value);
        }

        $this->execute();

        return self::$stm->fetch(PDO::FETCH_ASSOC);
    }

    public static function execute(string $sql): void
    {
        self::$stm->execute();
    }

    public static function TableExists(string $tableName): bool
    {
        $exists = Database::fetch(sprintf('SELECT 1 FROM `%s` LIMIT 1', $tableName));
        var_dump($exists);
        return false;
    }
}
