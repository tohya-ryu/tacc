<?php

# strip namespaces
use framework5\request as request;
use framework5\init\request as init_request;
use framework5\init\config as init_config;

# prepare $GLOBALS
$GLOBALS['services'] = [];
$GLOBALS['data']     = [];
$GLOBALS['db']       = [];
$GLOBALS['config']   = [];

# load globally required files
require 'sys/headers/enum.php';
require 'sys/functions/globals.php';
require 'sys/headers/request.php';
require 'sys/init/config.php';
require 'sys/init/request.php';

# setup config
init_config\read_config('src/config/app.php');
init_config\read_env('src/config/env.php');

# setup request
$request = new request();
$GLOBALS['request'] = $request;
$request = init_request\set_useragent($request);
$request = init_request\set_ip($request);
$request = init_request\set_transport($request);
$request = init_request\set_method($request);

# setup routing
// init router
// read routes
// match route
// get match and fetch corresponding interface

// includes root-dir (if placed in subdir of docroot) and get parameters ?x=z
var_dump($_SERVER['REQUEST_URI']);

// only includes sections relevant for routing
var_dump($_SERVER['QUERY_STRING']);


var_dump(request::test());


var_dump(request());
