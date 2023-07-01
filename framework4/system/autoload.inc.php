<?php

function framework4_autoload($class)
{
    $ar = array(
        realpath("app/locales/$class.php"),
        realpath("app/controllers/$class.php"),
        realpath("app/views/$class.php"),
        realpath("app/model/data/$class.php"),
        realpath("app/model/session/$class.php"),
        realpath("app/model/services/$class.php"),
        realpath("app/util/$class.php"),
        realpath("system/$class.php"),
        realpath("system/request/$class.php"),
        realpath("system/helpers/$class.php"),
        realpath("system/traits/$class.php"),
        realpath("system/routing/$class.php"),
        realpath("system/store/$class.php"),
        realpath("system/session/$class.php"),
        realpath("system/model/$class.php"),
        realpath("system/frontend/$class.php"),
        realpath("system/language/$class.php"),
        realpath("system/locales/$class.php"),
        realpath("system/util/$class.php")
    );
    foreach ($ar as $str) {
        if (framework4_load_class($str))
            break;
    }
}

function framework4_load_class($filename)
{
    if (is_readable($filename)) {
        require $filename;
        return true;
    }
    return false;
}


spl_autoload_register("framework4_autoload");
