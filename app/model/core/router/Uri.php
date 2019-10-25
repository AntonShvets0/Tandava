<?php


namespace Tandava\Core\Router;

/**
 * Class Uri
 * @package Tandava\Core\Router
 * Класс, отвечающий за обработку ссылки
 */
class Uri
{
    /**
     * @return string
     * Возвращает запрос пользователя
     */
    public static function Get(): string
    {
        return $_SERVER["REQUEST_URI"];
    }

    /**
     * @param string $uri
     * @return string
     * Преобразовывает ссылку
     */
    public static function Convert(string $uri): string
    {
        $uri = explode("?", trim($uri, "/"));

        array_pop($uri);

        return implode("?", $uri);
    }
}