<?php

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

    public function installDatabase(): void
    {
        if (!Database::tableExists('users')) {
            $sql = file_get_contents(__DIR__ . '/resources/sql/streamania2.sql');
            Database::fetch($sql);

            $this->installed = true;
        }
    }

    public function installDemodata(): void
    {
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