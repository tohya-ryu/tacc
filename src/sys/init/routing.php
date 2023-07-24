<?php

namespace framework5\init\routing;

use framework5\router as router;
use framework5\route as route;
use framework5\route_token as route_token;

# utility functions in global namespace

function read_routes(router $router): void
{
    $data = @file_get_contents('sys/cache/routes.txt');
    if ($data) {
        $router->prepared_routes = unserialize($data);
    } else {
        require 'app/routes/_config.php';
        $router->prepared_routes = prepare_routes($router);
        file_put_contents('sys/cache/routes.txt',
            serialize($router->prepared_routes));
    }
}

function register_routes(array $routes, router $router): void
{
    foreach ($routes as $route) {
        $record = new route();
        $record->method = $route[0];
        $record->uri    = $route[1];
        $record->target = $route[2];
        $record->tokens = tokenize_route($record);
        array_push($router->routes, $record);
    }
}

function tokenize_route(route $route): array
{
    $tokens = [];
    $target = explode('#', $route->target);
    $token = new route_token();
    $token->name = strtoupper($route->method);
    array_push($tokens, $token);
    $ar = explode('/', $route->uri);
    if ($route->uri == '/') {
        $tok = new route_token();
        $tok->name = '/';
        $tok->interface_name = $target[0];
        $tok->action_name =    $target[1];
        array_push($tokens, $tok);
    } else {
        if ($ar[count($ar)-1] == '') 
            array_pop($ar);
        $last_key = array_key_last($ar);
        foreach ($ar as $key => $str) {
            $tok = new route_token();
            if (substr($str, 0, 1) == ':') {
                //wildcard token (parameter)
                $start = strpos($str, '{');
                if ($start !== false) {
                    $end = strpos($str, '}');
                    $tok->name = substr($str, 1, $start-1);
                    $tok->match_condition = substr(
                        $str, $start+1, $end-$start-1);
                } else {
                    $tok->name = substr($str, 1);
                }
                $tok->is_wildcard = true;
            } else {
                // regular token
                $tok->name = $str;
            }
            if ($key === $last_key) {
                $tok->interface_name = $target[0];
                $tok->action_name    = $target[1];
            }
            array_push($tokens, $tok);
        }
    }
    return $tokens;
}

function prepare_routes(router $router): array
{
    # create and write route tree
    $t = new route_token();
    $t->name = 'START';
    $tokens = [$t];

    foreach ($router->routes as $route) {
        $current = $tokens[0];
        foreach ($route->tokens as $tok) {
            $found = false;
            foreach ($current->next as $check) {
                if (tokens_are_equal($tok, $check)) {
                    $current = $check;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                array_push($current->next, $tok);
                $current = $tok;
            }
        }
    }
    # fallback route
    $tok = new route_token();
    $tok->name = 'FALLBACK';
    $target = explode('#', $router->fallback);
    $tok->interface_name = $target[0];
    $tok->action_name = $target[1];
    array_push($tokens, $tok);
    return $tokens;
}

function tokens_are_equal(route_token $tok1, route_token $tok2): bool
{
    return (
        ($tok1->name === $tok2->name) &&
        ($tok1->is_wildcard === $tok2->is_wildcard) &&
        ($tok1->match_condition === $tok2->match_condition)
    );
}

function attempt_match(router $router)
{
    # prepare from uri
    $router->params = [];
    $uri_route      = [];
    $ar = explode('/', $_SERVER['QUERY_STRING']);
    $ar = array_filter($ar, 'strlen');
    if (count($ar) === 0)
        array_push($ar, '/');
    array_push($uri_route, request()->method);
    foreach ($ar as $fragment) 
        array_push($uri_route, $fragment);

    # attempt match
    $last_key = array_key_last($uri_route);
    $tokens = $router->prepared_routes[0]->next;
    foreach ($uri_route as $key => $str) {
        $uri_fragment_matched_token = false;
        foreach ($tokens as $tok) {
            if ($tok->is_wildcard) {
                if ($tok->match_condition) {
                    if (preg_match("/".$tok->match_condition."/", $str)) {
                        $router->params[$tok->name] = $str;
                        if (($key === $last_key) && $tok->interface_name) {
                            $router->match_interface = $tok->interface_name;
                            $router->match_action = $tok->action_name;
                            break 2;
                        } else {
                            $tokens = $tok->next;
                            $uri_fragment_matched_token = true;
                            break;
                        }
                    } else {
                        continue;
                    }
                } else {
                    $router->params[$tok->name] = $str;
                    if (($key === $last_key) && $tok->interface_name) {
                        $router->match_interface = $tok->interface_name;
                        $router->match_action = $tok->action_name;
                        break 2;
                    } else {
                        $tokens = $tok->next;
                        $uri_fragment_matched_token = true;
                        break;
                    }
                }
            } else {
                if ($tok->name === $str) {
                    if (($key === $last_key) && $tok->interface_name) {
                        $router->match_interface = $tok->interface_name;
                        $router->match_action = $tok->action_name;
                        break 2;
                    } else {
                        $tokens = $tok->next;
                        $uri_fragment_matched_token = true;
                        break;
                    }
                } else {
                    continue;
                }
            }
        }
        if (!$uri_fragment_matched_token)
            break;
    }
    if (!$router->match_interface) {
        $router->match_interface = $router->prepared_routes[1]->interface_name;
        $router->match_action = $router->prepared_routes[1]->action_name;
    }
}
