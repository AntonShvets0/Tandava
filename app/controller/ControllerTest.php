<?php

class ControllerTest extends \Tandava\View
{
    public function ActionHelloWorld($additional = "")
    {
        return $this->File("world", ["arg" => $additional]);
    }
}