<?php


namespace Tandava\Network;

/**
 * Class Mime
 * @package Tandava
 * Класс, для работы с MIME
 */
class Mime
{
    private static $defaultMime = "text/plain";

    private static $mimeTypes = [
        'txt' => 'text/plain',
        'htm' => 'text/html',
        'html' => 'text/html',
        'php' => 'text/html',
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'xml' => 'application/xml',
        'swf' => 'application/x-shockwave-flash',
        'flv' => 'video/x-flv',

        // images
        'png' => 'image/png',
        'jpe' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'gif' => 'image/gif',
        'bmp' => 'image/bmp',
        'ico' => 'image/vnd.microsoft.icon',
        'tiff' => 'image/tiff',
        'tif' => 'image/tiff',
        'svg' => 'image/svg+xml',
        'svgz' => 'image/svg+xml',

        // archives
        'zip' => 'application/zip',
        'rar' => 'application/x-rar-compressed',
        'exe' => 'application/x-msdownload',
        'msi' => 'application/x-msdownload',
        'cab' => 'application/vnd.ms-cab-compressed',

        // audio/video
        'mp3' => 'audio/mpeg',
        'qt' => 'video/quicktime',
        'mov' => 'video/quicktime',

        // adobe
        'pdf' => 'application/pdf',
        'psd' => 'image/vnd.adobe.photoshop',
        'ai' => 'application/postscript',
        'eps' => 'application/postscript',
        'ps' => 'application/postscript',

        // ms office
        'doc' => 'application/msword',
        'rtf' => 'application/rtf',
        'xls' => 'application/vnd.ms-excel',
        'ppt' => 'application/vnd.ms-powerpoint',

        // open office
        'odt' => 'application/vnd.oasis.opendocument.text',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
    ];

    /**
     * @param string $file
     * @return string
     *
     * Возвращает mime-тип файла
     */
    public static function GetType(string $file): string
    {
        $mime = self::$mimeTypes;

        $array = explode('.', $file);

        $extension = strtolower(array_pop($array));

        if (array_key_exists($extension, $mime)) {
            return $mime[$extension];
        } elseif (function_exists('finfo_open')) {
            $info = finfo_open(FILEINFO_MIME);
            $mimeType = finfo_file($info, $file);
            finfo_close($info);
            return $mimeType;
        } else {
            return self::$defaultMime;
        }
    }

    /**
     * @param string $type
     * @return string
     * Возвращает расширение MIME типа
     */
    public static function GetExtension(string $type): string
    {
        $mime = array_flip(self::$mimeTypes);

        return $mime[$type] ?? "";
    }
}