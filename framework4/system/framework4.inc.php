<?php

require 'system/autoload.inc.php';

Framework::init();

$session = FrameworkSession::get();
$session->init();

$request = FrameworkRequest::get();
$request->init_useragent();
$request->init_address();
$request->init_https_f();
$request->init_method();
$request->init_uri();
$request->init_cookies();

$router = new FrameworkRouter();

$router->read_routes();
$router->match_route();

$match = $router->get_match();
$match_controller = $match['controller'];
$match_action = $match['action'];
$request->init_parameters($router->params);

$controller = new $match_controller;

$controller->init_response();
$controller->set_request();

$controller->$match_action();
