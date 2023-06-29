<?php

trait FrameworkSingleton {

    // Uses:
    protected static $instance;

    public static function get()
    {
        if (!isset(self::$instance))
            self::$instance = new self();

        return self::$instance;
    }
}
