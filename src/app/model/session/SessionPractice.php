<?php

class SessionPractice extends FrameworkSessionModule {
    use FrameworkMagicGet;
    private static $magic_get_attr = array('set');

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
