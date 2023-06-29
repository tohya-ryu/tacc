<?php

class FrameworkSession {
    use FrameworkSingleton;

    private $modules;
    private $id;

    private final function __construct()
    {
        $this->modules = array();
    }

    public function init()
    {
        $error_f = false;
        if (ini_set('session.use_strict_mode', 1) === false)
            $error_f = true;
        if (ini_set('session.use_only_cookies', 1) === false)
            $error_f = true;
        if (ini_set('session.use_trans_sid', 0) === false)
            $error_f = true;
        if ($error_f) {
            trigger_error("Failed to run ini_set when initializing session.",
                E_USER_ERROR);
        }
        session_start();
        $this->id = session_id();
    }

    public function get_id()
    {
        return $this->id;
    }

    public function register_module($key, $obj)
    {
        $_SESSION[$key] = $obj;
        $this->modules[$key] = $obj;
    }

    public function unregister_module($key)
    {
        unset($_SESSION[$key]);
        unset($this->modules[$key]);
    }

    public function get_module($key)
    {
        if (array_key_exists($key, $this->modules)) {
            return $this->modules[$key];
        } else {
            if (array_key_exists($key, $_SESSION)) {
                $this->modules[$key] = $_SESSION[$key];
                return $this->modules[$key];
            } else {
                return false;
            }
        }
    }

}
