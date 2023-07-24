<?php

namespace framework5;

class route {

    // record of data for the current route
    // and tokens for each route in the set of all routes

    public string $method;
    public string $uri;
    public string $target;
    public ?array  $tokens;

    public function __construct()
    {
        $this->method = "";
        $this->uri    = "";
        $this->target = "";
        $this->tokens = null;
    }


}
