<?php

class SessionPractice extends FrameworkSessionModule {
    protected $set;

    public function __construct()
    {
        $this->set = null;
    }

    public function reset()
    {
        $this->set = null;
    }

    public function set($ar)
    {
        $this->set = $ar;
    }

}
