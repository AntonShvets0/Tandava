<?php


namespace Tandava\Network;

/**
 * Class Client
 * @package Tandava\Network
 * Работа с клиентом
 */
class Client
{
    /**
     * @return string
     * Возвращает user agent пользователя
     */
    public static function GetAgent(): string
    {
        return $_SERVER["HTTP_USER_AGENT"];
    }

    /**
     * @return string
     * Возвращает имя браузера клиента
     */
    public static function GetBrowser(): string
    {
        return get_browser(null, true)["browser"];
    }
}