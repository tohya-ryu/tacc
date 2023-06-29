<?php

class FrameworkRequest {
    use FrameworkMagicGet;
    use FrameworkSingleton;
    private static $magic_get_attr = array(
        'useragent', 'method', 'address', 'https_f', 'acclang', 'uri',
        'uri_elements', 'param', 'cookies'
    );

    private $useragent;
    private $method;
    private $address; // client IP
    private $https_f; // https?
    private $acclang; // Accepted Languages
    private $param; // parameters
    private $uri;
    private $uri_elements;
    private $cookies;

    private final function __construct()
    {
        $this->useragent = null;
        $this->address = null;
        $this->https_f = null;
        $this->method = null;
        $this->acclang = null; // accepted languages
        $this->param = new FrameworkRequestParamManager();
        $this->uri_elements = array();
        $this->cookies = array();
    }

    public function init_cookies()
    {
        $cookies = array();
        require 'system/cookies/cookies.conf.php';
        require 'app/config/cookies.conf.php';
        foreach ($cookies as $key => $cookie_data) {
            $cookie = new FrameworkCookie($key, $cookie_data);
            $this->cookies[$key] = $cookie;
        }
    }

    public function init_parameters($uri_param)
    {
        $this->param->init_get();
        $this->param->init_post();
        $this->param->init_uri($uri_param);
    }

    public function init_uri()
    {
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->uri_elements = $_SERVER['REQUEST_URI'];
        // remove get parameters
        if (strpos($_SERVER['REQUEST_URI'], '?')) {
            $this->uri_elements = substr($_SERVER['REQUEST_URI'], 0,
                strpos($_SERVER['REQUEST_URI'], '?'));
        }
        $this->uri_elements = explode('/', $this->uri_elements);
        $this->uri_elements = array_filter($this->uri_elements, 'strlen');
        if (count($this->uri_elements) == 0) {
            array_push($this->uri_elements, '/');
        }
    }

    public function init_useragent()
    {
        if (isset($_SERVER['HTTP_USER_AGENT']))
            $this->useragent = $_SERVER['HTTP_USER_AGENT'];
    }

    public function init_address()
    {
        $this->address = $_SERVER['REMOTE_ADDR'];
    }

    public function init_https_f()
    {
        $this->https_f = isset($_SERVER['HTTPS']);
    }

    public function init_method()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
    }

    public function init_accepted_languages()
    {
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $lang = array();

            $GET_NAME = 0;
            $GET_WEIGHT = 1;

            $str = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
            $len = strlen($str);
            $tmp = "";
            $a = array();
            $t = $GET_NAME;
            
            // parse HTTP Accepted Languages header into objects
            // e.g.: en-US,en;q=0.8,ja;q=0.5,de;q=0.3
            for ($i=0;$i<$len;$i++) {
                if ($str[$i] == ',') {
                    if ($t == $GET_NAME) {
                        array_push($a, $tmp);
                        $tmp = "";
                    } else if ($t == $GET_WEIGHT) {
                        $weight = substr($tmp, strpos($tmp, '=')+1);
                        foreach ($a as $v) {
                            array_push($lang,
                                array($v, $weight));
                        }
                        $a = array();
                        $tmp = "";
                        $t = $GET_NAME;
                    }
                } elseif ($str[$i] == ';') {
                    array_push($a, $tmp);
                    $t = $GET_WEIGHT;
                    $tmp = "";
                } else {
                    $tmp .= $str[$i];
                }
            }
            // push last element because of missing , after weight
            $weight = substr($tmp, strpos($tmp, '=')+1);
            foreach ($a as $v) {
                array_push($lang,
                    array($v, $weight));
            }
            usort($lang, function($a, $b) {
                if ($a[1] == $b[1]) {
                    return 0;
                } else {
                    return ($a[1] < $b[1]) ? 1 : -1;
                }
            });
            $this->acclang = $lang;
        }
    }

    public static function redirect($uri = '')
    {
        // TODO adjust to new framework
        if (isset($_SERVER['HTTPS'])) {
            $p = 'https://';
        } else {
            $p = 'http://';
        }
        header("Location:".$p.
            $_SERVER['SERVER_NAME']."/$uri");
    }

}

