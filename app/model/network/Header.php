<?php


namespace Tandava\Network;

/**
 * Class Header
 * @package Tandava\Web
 * Для работы с заголовком
 */
class Header
{
    private static $errorList = [
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        404 => 'Not Found',
        403 => 'Forbidden',
        402 => 'Payment Required',
        401 => 'Unauthorized',
        400 => 'Bad Request'
    ];

    /**
     * @param string $mime
     * @return void
     * Устанавливает Content-Type
     */
    public static function Content(string $mime): void
    {
        header("Content-Type: {$mime}");
    }

    /**
     * @param string $location
     * @return void
     * Редирект на нужную страницу
     */
    public static function Location(string $location): void
    {
        header("Location: {$location}");
    }

    /**
     * @param int $code
     * @return void
     * Устанавливает код ошибки
     */
    public static function Error(int $code): void
    {
        if (!array_key_exists($code, self::$errorList)) return;

        header("HTTP/1.0 " . $code . ' ' .self::$errorList[$code], true, $code);
    }
}