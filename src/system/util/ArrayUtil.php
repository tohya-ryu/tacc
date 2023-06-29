<?php

class ArrayUtil {

    public static function last_key($array)
    {
        if (version_compare(strtok(phpversion(), '-'), '7.3.0') >= 0)
            return array_key_last($array);
        else
            return array_pop(array_keys($array));
    }

}
