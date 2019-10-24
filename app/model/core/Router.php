<?php


namespace Tandava\Core;

use Tandava\Logger\Logger;
use Tandava\Network\Mime;
use Tandava\Network\Header;
use Tandava\Network\Client;
use Tandava\StringModel;

/**
 * Class Router
 * @package Tandava
 * Маршрутизатор. Нужен, для обработки строки
 */
class Router
{
    private $routes = ["get" => [], "post" => [], "request" => []], $errors;

    /**
     * @return void
     * Запускает маршрутизатор
     */
    public function Run(): void
    {
        $uri = $this->GetURI();
        $passArray = true;

        if ($uri != '/' && file_exists(ROOT . '/public/' . $uri)) {
            Header::Content(Mime::GetType(ROOT . '/public/' . $uri));

            echo file_get_contents(ROOT . '/public/' . $uri);

            if (LOGGING["REQUEST"]["FILES"]) Logger::Add("{$_SERVER["REMOTE_ADDR"]} (" . Client::GetAgent() . ") request to file {$uri}");
            return;
        }

        if (LOGGING["REQUEST"]["ROUTER"]) Logger::Add("{$_SERVER["REMOTE_ADDR"]} (" . Client::GetAgent() . ") request to {$uri}");

        $functionHandler = new FunctionHandler();

        if (count($_POST) > 0 && count($_GET) < 1) {
            $function = $this->SearchPostPath($uri);

            if ($function === false) {
                $this->GetRequest($uri, $function, $args);
            } else {
                $args = $_POST;
            }
        } elseif (count($_GET) > 0 && count($_POST) < 1) {
            $uri = $this->GetURI(true);

            $function = $this->SearchGetPath($uri);

            if ($function === false) {
                $this->GetRequest($uri, $function, $args);
            } else {
                $args = $_GET;
            }
        } else {
            $this->GetRequest($uri, $function, $args);
            $passArray = false;
        }

        $response = $functionHandler->Handler($function, $args, $passArray);

        if (StringModel::SubStartsWith("@", $response)) {
            Header::Error($response);
            $function = $this->GetError($response);

            $response = $functionHandler->Handler($function, []);
        }

        echo $response;
    }

    /**
     * @param string $url
     * @param $function
     * Добавляет post-путь, который доступен только при передаче GET параметров без POST
     */
    public function Get(string $url, $function): void { $this->Add("get", $url, $function); }

    /**
     * @param string $url
     * @param $function
     * Добавляет post-путь, который доступен только при передаче POST параметров без GET
     */
    public function Post(string $url, $function): void { $this->Add("post", $url, $function); }

    /**
     * @param string $url
     * @param $function
     * Добавляет request-путь. Т.е, доступный в любом случае (post, get)
     */
    public function Request(string $url, $function): void { $this->Add("request", $url, $function); }

    public function Error(int $code, $function): void
    {
        $this->errors[$code] = $function;
    }

    /**
     * @param string $to
     * @param string $url
     * @param $function
     * Добавляет в маршрутизатор путь
     */
    private function Add(string $to, string $url, $function): void
    {
        $this->routes[$to][$this->ConvertUri($url)] = $function;
    }

    private function GetRequest($uri, &$function, &$args)
    {
        $function = $this->SearchRequestPath($uri);

        if ($function === false) {
            Header::Error(404);
            $function = $this->GetError(404);
            $args = [];
        } else {
            $args = $function[0];
            $function = $function[1];
        }
    }

    /**
     * @param $uri
     * @return string
     * Убирает / из начала, и конца строки
     */
    private function ConvertUri(string $uri): string
    {
        return mb_strlen($uri) > 1 ? trim($uri, '/') : $uri;
    }

    /**
     * @param bool $deleteGet
     * @return string
     * Возвращает путь, куда зашел пользователь
     */
    private function GetURI(bool $deleteGet = false): string
    {
        $uri = $this->ConvertUri($_SERVER["REQUEST_URI"]);

        if ($deleteGet) {
            $uri = explode('?', $uri);

            if (count($uri) > 1) {
                array_pop($uri);
            }

            return $this->ConvertUri(implode('?', $uri));
        }

        return $uri;
    }

    /**
     * @param string $uri
     * @return bool|string|callable
     * Ищет в post-путях
     */
    private function SearchPostPath(string $uri)
    {
        foreach ($this->routes["post"] as $route => $function)
        {
            if ($uri == $route) {
                return $function;
            }
        }

        return false;
    }

    /**
     * @param string $uri
     * @return bool|string|callable
     * Ищет в get-путях
     */
    private function SearchGetPath(string $uri)
    {
        foreach ($this->routes["get"] as $route => $function)
        {
            if ($uri == $route) {
                return $function;
            }
        }

        return false;
    }

    /**
     * @param string $uri
     * @return bool|string|callable
     * Ищет в post и get путях
     */
    private function SearchRequestPath(string $uri)
    {
        foreach ($this->routes["request"] as $route => $function)
        {
            if (preg_match('~^' . $route . '$~', $uri, $args)) {
                array_shift($args);

                $args = array_map(function ($arg) {
                    return urldecode($arg);
                }, $args);

                return [$args, $function];
            }
        }

        return false;
    }

    /**
     * @param int $code
     * @return string|bool|callable
     */
    private function GetError(int $code)
    {
        return $this->errors[$code] ?? false;
    }
}