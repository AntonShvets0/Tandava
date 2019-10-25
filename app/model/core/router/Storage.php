<?php


namespace Tandava\Core\Router;

/**
 * Class Storage
 * @package Tandava\Core\Router
 * Класс, который хранит информаию о маршрутизаторе
 */
class Storage
{
    protected $routes = [
        "get" => [],
        "post" => [],
        "delete" => [],
        "put" => [],
        "any" => []
    ];

    protected $errors = [];

    protected $middlewares = [];
}