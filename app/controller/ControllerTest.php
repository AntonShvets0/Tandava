<?php

use Tandava\View;

class ControllerTest extends View
{
    public function ActionHelloWorld($additional = "")
    {
        return $this->File("world", ["arg" => $additional]);
    }

    public function ActionMiddleware()
    {
        return "@403";
    }
}