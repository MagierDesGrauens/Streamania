<?php

namespace Streamania;

use \Streamania\Database;

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
     * @var bool
     */
    private $reset;

    /**
     * Konstruktor
     */
    public function __construct()
    {
        $this->installed = false;
        $this->reset = false;
    }

    /**
     * @param string $arg
     */
    public function install(string $arg, string $subArg)
    {
        $methods = get_class_methods($this);

        $this->reset = ($arg === '--reset' || $subArg === '--reset');

        if ($arg === '--reset') {
            $arg = '';
        }

        $this->log('Install with reset.');

        if ($arg === '' || $arg === '--reset') {
            // Alle install-Funktionen ausführen
            foreach ($methods as $method) {
                if (substr($method, 0, 7) === 'install' && $method !== 'install') {
                    $this->log('Execute ' . $method);
                    $this->{$method}();
                }
            }

            if (!$this->installed) {
                $this->log('Nothing new installed, already up to date.');
            }
        } else {
            $method = 'install' . ucfirst($arg);

            $this->log('Execute ' . $method);
            $this->{$method}();
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
     *
     * Attention: Order of functions is the order of installation!
     */

    public function installDatabase(): void
    {
        Database::connect();

        if (!Database::tableExists('users') || $this->reset) {
            if ($this->reset) {
                $this->log('Reset database...');
                Database::fetch(file_get_contents(__DIR__ . '/resources/sql/clear-streamania.sql'));
            }

            $this->log('Install database...');
            Database::fetch(file_get_contents(__DIR__ . '/resources/sql/streamania.sql'));

            $this->installed = true;
        }
    }

     public function installConfig(): void
     {
        $file = __DIR__ . '/../../../bin/config.ini';
        $output = '';
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

        if (!file_exists($file)) {
            file_put_contents($file, '');
        }

        $ini = parse_ini_file($file, true);

        if ($this->reset) {
            $ini = [];
        }

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

        $this->log('Writing config file...');
        file_put_contents($file, $output);
    }

    public function installDemodata(): void
    {
        Database::connect();

        if (Database::tableExists('videos')) {
            if ($this->reset) {
                $this->log('Reset demodata...');
                Database::fetch(file_get_contents(__DIR__ . '/resources/sql/clear-demodata.sql'));
            }

            $this->log('Install demodata...');
            Database::fetch(file_get_contents(__DIR__ . '/resources/sql/demodata.sql'));

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

    public function log($message): void
    {
        printf(
            '[%s] %s' . PHP_EOL,
            date('H:i:s'),
            $message
        );
    }
}
