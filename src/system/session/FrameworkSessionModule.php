<?php

class FrameworkSessionModule {

    protected $key;

    public function register($key)
    {
        $this->key = $key;
        $session = FrameworkSession::get();
        $mod = $session->get_module($key);
        if ($mod) {
            foreach (get_object_vars($mod) as $k => $v) {
                $this->$k = $v;
            }
        } else {
            $session->register_module($key, $this);
        }
    }

    public function save()
    {
        $session = FrameworkSession::get();
        $session->register_module($this->key, $this);
    }

    public function unregister()
    {
        $session = FrameworkSession::get();
        if (isset($this->key)) {
            $key = $this->key;
            foreach (get_object_vars($this) as $k => $v) {
                $this->$k = null;
            }
            $session->unregister_module($key);
        } else {
            debug_print_backtrace(0,1);
            trigger_error(
                "call to register() has to precede call to unregister().",
                E_USER_ERROR);
        }
    }

}

