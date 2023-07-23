<?php

namespace framework5;

class route_token {

    public ?string $name;
    public ?string $match_condition;
    public ?string $interface_name;
    public ?string $action_name;
    public bool    $is_wildcard;
    public array   $next;

    public function __construct($name = null)
    {
        $this->name            = $name;
        $this->is_wildcard     = false;
        $this->match_condition = null;
        $this->next            = [];
        $this->interface_name  = null;
        $this->action_name     = null;
    }

}
