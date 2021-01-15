<?php

namespace Streamania;

use PDO;

/**
 * Klasse Database
 */
class Database
{
    /**
     * @var PDO
     */
    private static $pdo;

    /**
     * @var PDOStatement
     */
    private static $stm;

    /**
     * @var int
     */
    private static $lastInsertId;

    /**
     * @return bool
     */
    public static function connect(): bool
    {
        $dsn = sprintf(
            'mysql:dbname=%s;host=%s',
            Config::value('Database', 'db'),
            Config::value('Database', 'host')
        );

        try {
            self::$pdo = new PDO(
                $dsn,
                Config::value('Database', 'user'),
                Config::value('Database', 'password')
            );
        } catch (PDOException $e) {
            throw new Exception('Connection failed: ' . $e->getMessage());
        }

        return true;
    }

    /**
     * @param string $sql
     * @param array $params
     * @return array
     */
    public static function fetch(string $sql, array $params = []): array
    {
        self::$stm = self::$pdo->prepare($sql);

        foreach ($params as $key => $value) {
            if (is_int($key)) {
                $key++;
            }

            self::$stm->bindValue($key, $value);
        }

        self::$stm->execute();
        $result = self::$stm->fetchAll(PDO::FETCH_ASSOC);

        return $result === false ? [] : $result;
    }

    /**
     * @param string $sql
     * @param array $params
     * @return array
     */
    public static function fetchSingle(string $sql, array $params = []): array
    {
        $result = self::fetch($sql, $params);

        return $result[0] ?? [];
    }

    /**
     * @param string $sql
     */
    public static function execute(string $sql): void
    {
        self::$stm->execute();
    }

    /**
     * @param string $tableName
     * @return bool
     */
    public static function tableExists(string $tableName): bool
    {
        $exists = Database::fetch(sprintf(
            'SELECT table_name FROM information_schema.tables WHERE table_schema = "%s" AND table_name = :tableName LIMIT 1;',
            strtolower(Config::value('Database', 'db'))
        ), [":tableName" => $tableName]);

        return count($exists) > 0;
    }
}
