<?php

# strip namespaces
use framework5\request as request;
use framework5\init\request as init_request;

# prepare $GLOBALS
$GLOBALS['services'] = [];
$GLOBALS['data']     = [];
$GLOBALS['db']       = [];

# load globally required files
require 'sys/headers/enum.php';
require 'sys/functions/globals.php';
require 'sys/headers/request.php';
require 'sys/init/request.php';

# setup config

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

// includes root-dir and get parameters ?x=z
var_dump($_SERVER['REQUEST_URI']);

// only includes sections relevant for routing
var_dump($_SERVER['QUERY_STRING']);


var_dump(request::test());


var_dump(request());
