<?php

class FrameworkSessionCsrf extends FrameworkSessionModule {

    protected $token;

    public function __construct()
    {
        $this->token = new FrameworkToken();
    }

}
