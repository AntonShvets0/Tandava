<?php


namespace Tandava\Core\Router;


/**
 * Class Add
 * @package Tandava\Core\Router
 * Класс, который регистрирует функции, отвечающие за добавление путей
 */
class Add extends Storage
{
    /**
     * @param string $way
     * @param $function
     * @param $middleware
     * Добавляет GET-путь
     */
    public function Get(string $way, $function, $middleware = []): void { $this->Custom("get", $way, $function, $middleware); }

    /**
     * @param string $way
     * @param $function
     * @param $middleware
     * Добавляет POST-путь
     */
    public function Post(string $way, $function, $middleware = []): void { $this->Custom("post", $way, $function, $middleware); }

    /**
     * @param string $way
     * @param $function
     * @param $middleware
     * Добавляет PUT-путь
     */
    public function Put(string $way, $function, $middleware = []): void { $this->Custom("get", $way, $function, $middleware); }

    /**
     * @param string $way
     * @param $function
     * @param $middleware
     * Добавляет DELETE-путь
     */
    public function Delete(string $way, $function, $middleware = []): void { $this->Custom("get", $way, $function, $middleware); }

    /**
     * @param string $way
     * @param $function
     * @param $middleware
     * Добавляет путь, который доступен во всех типах
     */
    public function Any(string $way, $function, $middleware = []): void
    {
        foreach ($this->routes as $type => $data)
            $this->Custom($type, $way, $function, $middleware);
    }

    /**
     * @param array $types
     * @param string $way
     * @param $function
     * @param $middleware
     * Добавляет путь, который доступен в некоторых типах
     */
    public function Match(array $types, string $way, $function, $middleware = []): void
    {
        foreach ($types as $type) {
            if (!isset($this->routes[$type])) throw new \Error("Unknown type: {$type}");
            $this->Custom($type, $way, $function, $middleware);
        }
    }

    /**
     * @param int $code
     * @param $function
     * Регистрирует ошибку
     */
    public function Error(int $code, $function): void
    {
        $this->errors[$code] = $function;
    }

    /**
     * @param $type
     * @param $way
     * @param $function
     * @param $middleware
     * Позволяет добавить путь к кастомному типу
     */
    protected function Custom($type, $way, $function, $middleware)
    {
        if (!is_array($middleware)) $middleware = [$middleware];

        $this->routes[$type][Uri::Convert($way)] = [$function, $middleware];
    }
}