<?php

class FrameworkRequestParam {
    use FrameworkMagicGet;
    private static $magic_get_attr = array('key', 'value');

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
