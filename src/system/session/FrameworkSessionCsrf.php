<?php

class FrameworkSessionCsrf extends FrameworkSessionModule {
    use FrameworkMagicGet;
    private static $magic_get_attr = array('token');

    protected $token;

    public function __construct()
    {
        $this->token = new FrameworkToken();
    }

}
