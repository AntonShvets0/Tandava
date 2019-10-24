<?php

namespace Tandava\Date;

/**
 * Class Date
 * @package Tandava\Date
 * Для работы с датами
 */
class DateTime
{
    /**
     * @return string
     * Возвращает текущую дату в формате ч:м:с д.м.г
     */
    public static function Now(): string
    {
        return date("H:i:s d.m.y");
    }
}