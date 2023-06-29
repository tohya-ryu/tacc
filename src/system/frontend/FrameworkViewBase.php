<?php

class FrameworkViewBase {
    use FrameworkMagicGet;
    private static $magic_get_attr = array(
        'controller'
    );

    private $controller;
    private $render_stack;
    private $render_stack_pos;
    private $content_template_file;
    private $layout_template_file;

    final public function __construct($controller)
    {
        $this->controller = $controller;
        $this->render_stack = array();
        $this->render_stack_pos = 0;
    }

    public function set_layout($file)
    {
        $this->layout_template_file = $file;
    }

    public function render($template_file, $data = array())
    {
        $path = realpath("app/templates/$template_file");
        if ($this->render_stack_pos == 0) {
            $this->render_stack_pos = $this->render_stack_pos + 1;
            $this->content_template_file = $template_file;
            $this->render($this->layout_template_file, $data);
        } else {
            if (in_array($path, $this->render_stack)) {
                debug_print_backtrace(0,$this->render_stack_pos-1);
                trigger_error("Template file called recursively '$path'",
                    E_USER_ERROR);
            }
            $this->render_stack_pos = $this->render_stack_pos + 1;
            array_push($this->render_stack, $path);
            $template = new FrameworkTemplate($this, $path, $data);
            $template->render();
            array_pop($this->render_stack);
            $this->render_stack_pos = $this->render_stack_pos - 1;
        }
    }

    public function render_content($data = array())
    {
        $this->render($this->content_template_file, $data);
    }

    public function enc($str)
    {
        if (is_null($str))
            return "";
        else
            return htmlspecialchars($str, ENT_QUOTES);
    }
}
