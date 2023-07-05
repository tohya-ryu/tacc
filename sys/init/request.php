<?php

namespace framework5\init\request;
use framework5\request as request;

function set_useragent(request $request): request
{
if (isset($_SERVER['HTTP_USER_AGENT']))
    $request->useragent = $_SERVER['HTTP_USER_AGENT'];
return $request;
}

function set_ip(request $request): request
{
$request->ip = $_SERVER['REMOTE_ADDR'];
return $request;
}

function set_transport(request $request): request
{
if (isset($_SERVER['HTTPS'])) {
    $request->transport = 'https://';
    $request->is_secure = true;
} else {
    $request->transport = 'http://';
    $request->is_secure = false;
}
return $request;
}

function set_method(request $request): request
{
$request->method = $_SERVER['REQUEST_METHOD'];
if (isset($_POST['_method'])) {
    if (Enum.IsDefined(typeof(Method), $_POST['_method'])) {
        $request->method = $_POST['_method'];
    } else {
        die('Invalid method from $_POST: ' . htmlspecialchars(
            $_POST['method'], ENT_QUOTES));
    }
}
return $request;
}

function set_languages(request $request): request
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
        $request->languages = $lang;
    }
}

// uri
function set_uri(request $request): request
{
}

// cookies
function set_ip(request $request): request
{
}

// session (after routing)
function set_ip(request $request): request
{
}

// parameters (after routing)
function set_ip(request $request): request
{
}
