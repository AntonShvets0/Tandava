<?php


namespace Tandava;


class StringModel
{
    /**
     * @param string $needle
     * @param string $haystack
     * @return bool
     * Проверить, начинается ли строка с определенных символов
     */
    public static function StartsWith(string $needle, string $haystack): bool
    {
        return mb_substr($haystack, 0, mb_strlen($needle)) == $needle;
    }

    /**
     * @param string $needle
     * @param string $haystack
     * @return bool
     * Проверить, начинается ли строка с определенных символов. Если да, то удалить их, и возвратить true
     */
    public static function SubStartsWith(string $needle, string &$haystack): bool
    {
        $bool = self::StartsWith($needle, $haystack);

        if ($bool) $haystack = mb_substr($haystack, mb_strlen($needle));

        return $bool;
    }
}