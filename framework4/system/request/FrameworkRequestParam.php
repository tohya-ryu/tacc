<?php

class FrameworkRequestParam {

    private $key;
    private $value;
    private $type;

    public function __construct($type, $key, $value)
    {
        $this->type = $type;
        $this->key = $key;
        $this->value = $value;
    }

}
