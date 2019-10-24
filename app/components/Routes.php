<?php

$router->Request("/(.*)", "test@helloWorld");

$router->Error(404, function () {
    return "Не найдено";
});