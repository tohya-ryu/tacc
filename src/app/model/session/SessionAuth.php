<?php

class SessionAuth extends FrameworkSessionModule {
    public $userid;

    public function __construct()
    {
        $this->userid = null;
    }

}
