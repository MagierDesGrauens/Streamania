<?php

namespace Streamania;

use \Streamania\Database;

/**
 * Klasse User
 */
class User
{
    const STATE_LOGGED_IN = 1;
    const STATE_LOGIN_FAILED = -1;
    const STATE_ALREADY_LOGGED_IN = -2;
    const STATE_REGISTER_SUCCESS = 2;
    const STATE_REGISTER_EMPTY = -1;
    const STATE_REGISTER_EXISTS_MAIL = -2;
    const STATE_REGISTER_EXISTS_NAME = -3;

    private static $id;
    private static $sessionId;
    private static $mail;
    private static $username;
    private static $isLoggedIn;
    private static $exists;

    public static function init()
    {
        self::$id = -1;
        self::$sessionId = '';
        self::$mail = '';
        self::$username = '';
        self::$isLoggedIn = false;
        self::$exists = false;
    }

    public static function setFromArray($data): void
    {
        if (!empty($data)) {
            self::$id = intval($data['users_id']);
            self::$sessionId = $data['session_id'];
            self::$mail = $data['mail'];
            self::$username = $data['username'];
            self::$exists = true;

            if (!empty($data['session_id'])) {
                self::$isLoggedIn = true;
            }
        }
    }

    public static function fetchById(): void
    {
        self::$exists = false;

        $user = Database::fetchSingle('SELECT * FROM users WHERE users_id = ?', [self::$id]);

        self::setFromArray($user);
    }

    public static function fetchBySessionId($sessionId): void
    {
        self::$exists = false;

        $user = Database::fetchSingle('SELECT * FROM users WHERE session_id = ?', [$sessionId]);

        self::setFromArray($user);
    }

    public static function fetchByMailPassword($mail, $pass): void
    {
        self::$exists = false;

        $user = Database::fetchSingle(
            'SELECT * FROM users WHERE mail = ? AND password = ?',
            [
                $mail,
                hash('sha256', $pass)
            ]
        );

        self::setFromArray($user);
    }

    public static function fetchByMail($mail): void
    {
        self::$exists = false;

        $user = Database::fetchSingle(
            'SELECT * FROM users WHERE mail = ?',
            [$mail]
        );

        self::setFromArray($user);
    }

    public static function fetchByName($name): void
    {
        self::$exists = false;

        $user = Database::fetchSingle(
            'SELECT * FROM users WHERE username = ?',
            [$name]
        );

        self::setFromArray($user);
    }

    public static function login(): void
    {
        Database::fetch(
            'UPDATE users SET session_id = ? WHERE users_id = ?',
            [session_id(), self::getId()]
        );

        self::$isLoggedIn = true;
    }

    public static function logout(): void
    {
        Database::fetch('UPDATE users SET session_id = "" WHERE users_id = ?', [self::$id]);
    }

    public static function register($username, $mail, $password): void
    {
        $password = hash('sha256', $password);

        Database::fetch(
            'INSERT INTO users (mail, password, username) VALUES (?, ?, ?)',
            [$mail, $password, $username]
        );

        self::$isLoggedIn = true;
    }

    public static function isLoggedIn(): bool
    {
        return self::$isLoggedIn;
    }

    public static function exists(): bool
    {
        return self::$exists;
    }

    public static function getId(): int
    {
        return self::$id;
    }

    public static function getSessionId(): string
    {
        return self::$sessionId;
    }

    public static function getMail(): string
    {
        return self::$mail;
    }

    public static function getUsername(): string
    {
        return self::$username;
    }
}
