<?php

use \Streamania\Database;

namespace Streamania;

/**
 * Klasse Installer
 */
class Installer
{
    /**
     * @var bool
     */
    private $installed;

    /**
     * Konstruktor
     */
    public function __construct()
    {
        $this->installed = false;
    }

    /**
     * @param string $arg
     */
    public function install(string $arg)
    {
        $methods = get_class_methods($this);

        // Alle install-Funktionen ausführen
        foreach ($methods as $method) {
            if (substr($method, 0, 7) === 'install' && $method !== 'install') {
                $this->{$method}();
            }
        }

        if (!$this->installed) {
            printf('Nothing new installed, already up to date.');
        }
    }

    public function resetAll(): void
    {
        $methods = get_class_methods($this);

        // Alle reset-Funktionen ausführen
        foreach ($methods as $method) {
            if (substr($method, 0, 5) === 'reset' && $method !== 'resetAll') {
                $this->{$method}();
            }
        }
    }

    /**
     * ==========================
     * Install / Reset Funktionen
     * ==========================
     */

     public function installConfig(): void
     {
        $file = __DIR__ . '/../../../bin/config.ini';
        $ini = [];
        $iniSampleData = [
            'Database' => [
                'host' => 'localhost',
                'user' => 'root',
                'password' => '123456',
                'db' => 'Streamania2'
            ],
            'Website' => [
                'base' => 'http://localhost/Streamania/public/'
            ],
            'Watch2Gether' => [
                'socket_url' => 'ws://localhost',
                'port' => '2021'
            ]
        ];
        $output = '';

        if (!file_exists($file)) {
            file_put_contents($file, '');
        }

        $ini = parse_ini_file($file, true);

        // Keys prüfen
        foreach ($iniSampleData as $group => $data) {
            // Fehlende Gruppen hinzufügen
            if (!array_key_exists($group, $ini)) {
                $ini[$group] = [];
            }

            foreach ($data as $key => $value) {
                // Sample Wert setzen, wenn Feld in Gruppe fehlt
                if (!array_key_exists($key, $ini[$group])) {
                    $ini[$group][$key] = $value;
                }
            }
        }

        // Ini schreiben
        foreach ($ini as $group => $data) {
            $output .= sprintf('[%s]', $group) . PHP_EOL;

            foreach ($data as $key => $value) {
                $output .= sprintf('%s = "%s"', $key, $value) . PHP_EOL;
            }

            $output .= PHP_EOL;
        }

        file_put_contents($file, $output);
     }

    public function installDatabase(): void
    {
        Database::connect();

        if (!Database::tableExists('users')) {
            $sql = file_get_contents(__DIR__ . '/resources/sql/streamania2.sql');
            Database::fetch($sql);

            $this->installed = true;
        }
    }

    public function installDemodata(): void
    {
        Database::connect();

        if (Database::tableExists('videos')) {
            $sql = file_get_contents(__DIR__ . '/resources/sql/demodata.sql');
            Database::fetch($sql);

            $this->installed = true;
        } else {
            printf('Table "videos" not found. Please execute install database first');
            $this->installed = false;
        }
    }

    public function resetDatabase(): void
    {
        // @todo vielleicht irgendwann einbinden
    }
}