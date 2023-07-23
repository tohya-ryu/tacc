<?php

namespace framework5;

class route {

    // record of data for the current route
    // and tokens for each route in the set of all routes

    public array $tokens;

    public function __construct()
    {
        $this->tokens = [];
    }


}
