<?php

# strip namespaces
use framework5\request as request;
use framework5\router as router;
use framework5\init\request as init_request;
use framework5\init\config as init_config;

use framework5\init\routing as init_routing;

# prepare $GLOBALS
$GLOBALS['services'] = [];
$GLOBALS['data']     = [];
$GLOBALS['db']       = [];
$GLOBALS['config']   = [];

# load globally required files
require 'sys/headers/enum.php';
require 'sys/functions/globals.php';
require 'sys/headers/request.php';
require 'sys/headers/router.php';
require 'sys/headers/route.php';
require 'sys/headers/route_token.php';
require 'sys/init/config.php';
require 'sys/init/request.php';
require 'sys/init/routing.php';

# setup config
init_config\read_config('app/config/app.php');
init_config\read_env('app/config/env.php');

# setup request
$request = new request();
$GLOBALS['request'] = $request;
$request = init_request\set_useragent($request);
$request = init_request\set_ip($request);
$request = init_request\set_transport($request);
$request = init_request\set_method($request);

# setup routing
$router = new router();
init_routing\read_routes($router);
//var_dump($router->routes);
#$GLOBALS['route'] = init_routing\get_match($router);
init_routing\attempt_match($router);

require "app/interfaces/".$router->match_interface."/".
    $router->match_action.".php";

// includes root-dir (if placed in subdir of docroot) and get parameters ?x=z
//var_dump($_SERVER['REQUEST_URI']);

// only includes sections relevant for routing
//var_dump($_SERVER['QUERY_STRING']);

//var_dump(request());
