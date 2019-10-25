<?php


namespace Tandava\Core\Router;

use Error;
use Tandava\Core\FunctionHandler;
use Tandava\Network\Header;
use Tandava\StringModel;

/**
 * Class Router
 * @package Tandava
 * Маршрутизатор. Нужен, для обработки запроса пользователя
 */
class Router extends Add
{
    public function Run(): void
    {
        $uri = Uri::Convert(Uri::Get());
        $handler = new FunctionHandler();

        $method = mb_strtolower($_SERVER["REQUEST_METHOD"]);

        if (!isset($this->routes[$method])) throw new Error("Unknown request method");

        if (!$this->CheckMiddleWares($this->middlewares, $handler)) return;

        foreach ($this->routes[$method] as $route => $data) {
            [$function, $middlewares] = $data;

            if (preg_match("~" . str_replace("~", "\~", $route) . "~", $uri, $args)) {
                if (!$this->CheckMiddleWares($middlewares, $handler)) return;

                array_shift($args);

                $this->Handler($function, $args, $handler);

                return;
            }
        }

        $this->SetError(404);
    }

    /**
     * @param array $middlewares
     * @param FunctionHandler $handler
     * @return bool
     */
    private function CheckMiddleWares(array $middlewares, FunctionHandler $handler): bool
    {
        foreach ($middlewares as $middleware) {
            $responseMiddleware = $handler->Handler($middleware);

            if (!is_bool($responseMiddleware)) {
                if (is_string($responseMiddleware) && StringModel::SubStartsWith("@", $responseMiddleware)) {
                    $this->SetError($responseMiddleware);
                } else {
                    $this->Handler($responseMiddleware, [], $handler);
                }
                return false;
            } else {
                if ($responseMiddleware === false) {
                    $this->SetError(403);
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param int $code
     * @return mixed
     * Возвращает функцию ошибки
     */
    private function GetError(int $code)
    {
        return $this->errors[$code] ?? false;
    }

    /**
     * @param int $code
     * Вызывает ошибку
     */
    private function SetError(int $code)
    {
        Header::Error($code);

        $this->Handler($this->GetError($code), [], new FunctionHandler());
    }

    /**
     * @param $function
     * @param array $args
     * @param FunctionHandler $handler
     */
    private function Handler($function, array $args, FunctionHandler $handler): void
    {
        $response = $handler->Handler($function, $args);

        if (StringModel::SubStartsWith("@", $response))
            $this->SetError($response);
        else
            echo $response;
    }
}