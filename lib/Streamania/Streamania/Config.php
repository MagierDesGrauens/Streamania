<?php

namespace Streamania;

/**
 * Klasse Config
 */
class Config
{
    /**
     * @var array
     */
    private static $errorList = [];

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

        $value = self::$config[$group][$key] ?? null;

        if ($value === null) {
            self::$errorList[] = ['group' => $group, 'key' => $key];

            $value = '';
        }

        return $value;
    }

    /**
     * @return array
     */
    public static function getErrorList(): array
    {
        return self::$errorList;
    }
}
