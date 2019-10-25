<?php


namespace Tandava\Core;

/**
 * Class FunctionHandler
 * @package Tandava
 * данный класс нужен для обработки строк/анонимных функций/массивов и т.п
 */
class FunctionHandler
{
    /**
     * @param $function
     * @param array $args
     * @param bool $passArray
     * @return string
     * Обрабатывает строку
     */
    public function Handler($function, array $args = [], bool $passArray = false): string
    {
        if ($function === false) {
            return "";
        }

        if (is_string($function) && stripos($function, '@') !== false) { // Если это строка, и там найдена @
            [$controller, $action] = explode('@', $function);

            $controller = 'Controller' . ucfirst($controller);
            $action = 'Action' . ucfirst($action);

            require_once ROOT . '/app/controller/' . $controller . '.php'; // Подключаем класс

            $class = new $controller;

            // Если мы должны возвратить массив, то вызываем call_user_func, иначе call_user_func_array
            $response = !$passArray ? call_user_func_array([$class, $action], $args) : call_user_func([$class, $action], $args);
        } else $response = !$passArray ? call_user_func_array($function, $args) : call_user_func($function, $args);

        if (is_array($response)) $response = json_encode($response, JSON_UNESCAPED_UNICODE);

        return $response;
    }
}