<?php

namespace framework5;

class router {

    public array   $routes;
    public array   $prepared_routes;
    public array   $params;
    public ?string $match_interface;
    public ?string $match_action;
    public ?string $fallback;

    public function __construct()
    {
        $this->routes          = [];
        $this->prepared_routes = [];
        $this->params          = [];
        $this->match_interface = null;
        $this->match_action    = null;
        $this->fallback        = null;
    }
    
}
