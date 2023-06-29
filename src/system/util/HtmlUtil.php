<?php

class HtmlUtil {

    public static function escape($str)
    {
        return htmlspecialchars($str, ENT_QUOTES);
    }
}
