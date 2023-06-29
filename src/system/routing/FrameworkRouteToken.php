<?php

class FrameworkRouteToken {

    public $name;
    public $wildcard_f;
    public $match_condition;
    public $next;
    public $controller_name;
    public $action_name;

    public function __construct($name = null)
    {
        $this->name = $name;
        $this->wildcard_f = false;
        $this->match_condition = null;
        $this->next = array();
        $this->controller_name = null;
        $this->action_name = null;
    }

    public static function equals($tok1, $tok2)
    {
        return (
            ($tok1->name == $tok2->name) &&
            ($tok1->wildcard_f == $tok2->wildcard_f) &&
            ($tok1->match_condition == $tok2->match_condition)
        );
    }

}
