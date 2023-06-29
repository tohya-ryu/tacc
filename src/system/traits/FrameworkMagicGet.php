<?php

trait FrameworkMagicGet {

    // Uses array:
    //
    // private static $magic_get_attr;
    //
    // to identify allowed properties

    public function __get($attr)
    {
        if (in_array($attr, self::$magic_get_attr)) {
            return $this->$attr;
        } else {
            debug_print_backtrace(0,1);
            trigger_error("Invalid Property <b>$attr</b>.", E_USER_ERROR);
        }
    }

}
