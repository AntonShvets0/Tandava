<?php


namespace Tandava;

/**
 * Class Percent
 * @package Tandava
 * Класс, для работы с процентами
 */
class Percent
{
    /**
     * @param int $percent
     * @return bool
     * Возвращает true, с определенной вероятностью
     */
    public static function Check(int $percent): bool
    {
        return mt_rand(0, 100) <= $percent;
    }

    /**
     * @param array $case
     * @return string
     * Возвращает случайный ключ из массива, основываясь на вероятностях
     * Значение в массиве должно содержать процент выпадения данного элемента
     * Массив должен быть такого вида: ["Хлеб" => 50, "Масло" => 25, "Яйца" => 25]
     */
    public static function CaseRandom(array $case): string
    {
        $targetArray = [];

        foreach ($case as $key => $percent) {
            StringModel::SubEndsWith("%", $percent);
            $targetArray = array_merge($targetArray, array_fill(0, $percent, $key)); // создаем массив, который содержит имя ключа столько раз, сколько написано в значении. Объединяем его с уже существующим
        }

        return $targetArray[array_rand($targetArray)]; // Получаем случайный элемент из массива
    }
}