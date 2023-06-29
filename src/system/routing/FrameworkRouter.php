<?php

class FrameworkRouter {
    use FrameworkMagicGet;
    private static $magic_get_attr = array('params');

    private $request;
    private $routes;
    private $prepared_routes;
    private $params;

    private $match_controller;
    private $match_action;

    public function __construct()
    {
        $this->request = FrameworkRequest::get();
        $this->match_controller = null;
        $this->match_action = null;
        $this->routes = array();
        $this->params = array();
    }

    public function get_match()
    {
        return array(
            'controller' => $this->match_controller,
            'action'     => $this->match_action
        );
    }

    public function read_routes()
    {
       $data = @file_get_contents('system/data/routes.txt');
       if ($data) {
           $this->prepared_routes = unserialize($data);
       } else {
           $this->routes = $this->collect_routes();
           $this->prepare_data();
       }
    }

    public function match_route()
    {
        $this->params = array();
        $uri_route = array();
        array_push($uri_route, $this->request->method);
        foreach ($this->request->uri_elements as $el) {
            array_push($uri_route, $el);
        }
        $tokens = $this->prepared_routes[0]->next;
        $last_key = ArrayUtil::last_key($uri_route);
        foreach ($uri_route as $key => $str) {
            foreach ($tokens as $tok) {
                if ($tok->wildcard_f) {
                    if ($tok->match_condition) {
                        if (preg_match("/".$tok->match_condition."/", $str)) {
                            $this->params[$tok->name] = $str;
                            if (($key === $last_key)&& $tok->controller_name) {
                                $this->match_controller =
                                    $tok->controller_name;
                                $this->match_action = $tok->action_name;
                                break 2;
                            } else {
                                $tokens = $tok->next;
                                break;
                            }
                        } else {
                            continue;
                        }
                    } else {
                        $this->params[$tok->name] = $str;
                        if (($key === $last_key) && $tok->controller_name) {
                            $this->match_controller = $tok->controller_name;
                            $this->match_action = $tok->action_name;
                            break 2;
                        } else {
                            $tokens = $tok->next;
                            break;
                        }
                    }
                } else {
                    if ($tok->name == $str) {
                        if (($key === $last_key) && $tok->controller_name) {
                            $this->match_controller = $tok->controller_name;
                            $this->match_action = $tok->action_name;
                            break 2;
                        } else {
                            $tokens = $tok->next;
                            break;
                        }
                    } else {
                        continue;
                    }
                }
            }
        }
        if (!$this->match_controller) {
            $target = FrameworkRoute::$default_target;
            $target = explode('#', $target);
            $this->match_controller =
                $this->prepared_routes[1]->controller_name;
            $this->match_action = $this->prepared_routes[1]->action_name;
        }
    }

    private function collect_routes()
    {
        require 'app/config/routes.conf.php'; 
        return FrameworkRoute::pass_collection();
    }

    private function prepare_data()
    {
        // use $this->routes to build tree data
        // set $this->prepared routes
        // write 'system/data/routes.text' from serialized tree

        $tokens = array(new FrameworkRouteToken('START'));

        foreach ($this->routes as $route) {
            $current = $tokens[0];
            foreach ($route->tokens as $tok) {
                $found = false;
                foreach ($current->next as $check) {
                    if (FrameworkRouteToken::equals($tok, $check)) {
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
        // information for default route
        if (FrameworkRoute::$default_target) {
            $tok = new FrameworkRouteToken('DEFAULT');
            $target = FrameworkRoute::$default_target;
            $target = explode('#', $target);
            $tok->controller_name = ucfirst($target[0]).'Controller';
            $tok->action_name = $target[1];
            array_push($tokens, $tok);
        }
        $this->prepared_routes = $tokens;
        file_put_contents('system/data/routes.txt', serialize($tokens));
    }
}
