<?php

namespace Streamania;

class Config
{
    private static $iniRead = false;
    private static $config = [];

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
