<?php

class FrameworkTemplate {
    use FrameworkMagicGet;
    private static $magic_get_attr = array(
        'view', 'data'
    );

    private $view;
    private $data;
    private $path;

    public function __construct($view, $path, $data)
    {
        $this->view = $view;
        $this->path = $path;
        $this->data = array();
        foreach ($data as $key => $value) {
            if ($key == 'view' || $key == 'path') {
                debug_print_backtrace(0,1);
                trigger_error("Invalid Data Key <b>$key</b>.", E_USER_ERROR);
            }
            $this->data[$key] = $value;
        }
    }

    public function render()
    {
        require $this->path;
    }

    private function print($key)
    {
        echo $this->data[$key];
    }

    private function get($key)
    {
        return $this->data[$key];
    }

    private function base_uri($str)
    {
        echo $this->view->controller->base_uri($str);
    }
}
