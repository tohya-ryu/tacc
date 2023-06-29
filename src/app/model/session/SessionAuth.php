<?php

class SessionAuth extends FrameworkSessionModule {
    use FrameworkMagicGet;
    private static $magic_get_attr = array(
    );

    public $userid;

    public function __construct()
    {
        $this->userid = null;
    }

}
