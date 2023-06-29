<?php

class FrameworkRoute {
    use FrameworkMagicGet;
    private static $magic_get_attr = array('tokens');

    private static $collection = array();
    public static $default_target = null;

    private $tokens;


    public function __construct($method, $uri, $target)
    {
        $this->tokens = $this->tokenize($method, $uri, $target);
    }

    public static function set($method, $uri, $target)
    {
        array_push(self::$collection, new FrameworkRoute(
            strtoupper($method), $uri, $target));
    }

    public static function set_default($target)
    {
        self::$default_target = $target;
    }

    public static function pass_collection()
    {
        return self::$collection;
    }

    private function tokenize($method, $uri, $target)
    {
        $tokens = array();
        $target = explode('#', $target);
        array_push($tokens, new FrameworkRouteToken($method));
        $ar = explode('/', $uri);
        if ($uri == '/') {
            $tok = new FrameworkRouteToken('/');
            $tok->controller_name = ucfirst($target[0]).'Controller';
            $tok->action_name = $target[1];
            array_push($tokens, $tok);
        } else {
            if ($ar[count($ar)-1] == '') {
                array_pop($ar);
            }
            $last_key = ArrayUtil::last_key($ar);
            foreach ($ar as $key => $str) {
                $tok = new FrameworkRouteToken();
                if (substr($str, 0, 1) == ':') {
                    // wildcard token (parameter)
                    $start = strpos($str, '{');
                    if ($start !== false) {
                        $end = strpos($str, '}');
                        $tok->name = substr($str, 1, $start-1);
                        $tok->match_condition = substr(
                            $str, $start+1, $end-$start-1);
                    } else {
                        $tok->name = substr($str, 1);
                    }
                    $tok->wildcard_f = true;
                } else {
                    // regular token
                    $tok->name = $str;
                }
                if ($key === $last_key) {
                    $tok->controller_name = ucfirst($target[0]).'Controller';
                    $tok->action_name = $target[1];
                }
                array_push($tokens, $tok);
            }
        }
        return $tokens;
    }

}
