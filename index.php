<?php
/**
 * Tandava Framework
 * @author LordOverLord0
 * @see vk.com/lordoverlord0
 * 2019
 */

const SHOW_ERRORS = true; // Включить показ ошибок

const LOGGING = [ // Логирование
    "ERROR" => false, // Логирование ошибок
    "WARNING" => false, // Логирование предупреждений
    "REQUEST" => [
        "FILES" => false, // Логирование запросов к файлам (из папки /public)
        "ROUTER" => false // Логирование запросов к маршрутизатору
    ]
];

define("ROOT", $_SERVER["DOCUMENT_ROOT"]);
define("CONFIG", parse_ini_file(ROOT . '/app/components/config.ini', true));

if (SHOW_ERRORS) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);

    if (LOGGING["ERROR"]) setLoggingError();
    if (LOGGING["WARNING"]) setLoggingWarning();

} else {
    ini_set('display_errors', '0');
    error_reporting(0);
}

require_once ROOT . '/vendor/autoload.php';

$router = new Tandava\Core\Router\Router();

require_once ROOT . '/app/components/Routes.php';

$router->Run();





function setLoggingWarning()
{
    set_error_handler(function () {
       $args = array_slice(func_get_args(), 1);
       \Tandava\Logger\Logger::Add("{$args[0]}, file {$args[1]}, line {$args[2]}", "WARNING", "warn");
    });
}

function setLoggingError()
{
    set_exception_handler(function (Throwable $throwable) {
        $message = $throwable->getMessage();
        $line = $throwable->getLine();
        $file = $throwable->getFile();

        \Tandava\Logger\Logger::Add("{$message}, line: {$line}, file: {$file}", "ERROR", "error");
    });
}