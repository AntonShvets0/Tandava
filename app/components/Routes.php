<?php

$router->Any("/", "test@helloWorld", "test@middleware");

$router->Error(404, function () {
    return "Не найдено";
});
$router->Error(403, function () {
    return "Нет доступа";
});