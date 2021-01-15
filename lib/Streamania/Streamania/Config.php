<?php

namespace Streamania;

/**
 * Klasse Config
 */
class Config
{
    /**
     * @var bool
     */
    private static $iniRead = false;

    /**
     * @var array
     */
    private static $config = [];

    /**
     * @param string $group
     * @param string $key
     * @return string
     */
    public static function value(string $group, string $key): string
    {
        if (!self::$iniRead) {
            if (file_exists(__DIR__ . '/../../../bin/config.ini')) {
                self::$config = parse_ini_file(__DIR__ . '/../../../bin/config.ini', true);
                self::$iniRead = true;
            }
        }

        return self::$config[$group][$key] ?? '';
    }
}
