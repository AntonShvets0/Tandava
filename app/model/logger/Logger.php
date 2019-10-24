<?php

namespace Tandava\Logger;

use Tandava\Date\DateTime;

/**
 * Class Logger
 * @package Tandava\Logger
 * Для работы с логами
 */
class Logger
{
    private static $folder = "logs";

    /**
     * @param string $text
     * @param string $prefix
     * @param string $suffixFile
     * Добавить в файл запись
     */
    public static function Add(string $text, string $prefix = "", string $suffixFile = ""): void
    {
        $prefix = mb_strtoupper($prefix);
        $suffixFile = mb_strtolower($suffixFile);

        $text = "[" . DateTime::Now() . "]" . (empty($prefix) ? ": " . $text : " [{$prefix}]: {$text}");

        $file = date("d_m_y") . (empty($suffixFile) ? "" : "_{$suffixFile}") . '.txt'; // Файл для записи

        file_put_contents(ROOT . DIRECTORY_SEPARATOR . self::$folder . DIRECTORY_SEPARATOR . $file, $text . PHP_EOL, FILE_APPEND);
    }

    /**
     * @return string
     */
    public static function GetFolder(): string
    {
        return self::$folder;
    }

    /**
     * @param string $folder
     */
    public static function SetFolder(string $folder): void
    {
        self::$folder = $folder;
    }
}