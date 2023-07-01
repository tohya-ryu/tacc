<?php

class FrameworkRequestParamManager {

    private $get_params;
    private $post_params;
    private $uri_params;

    public function __construct()
    {
        $this->get_params = array();
        $this->post_params = array();
        $this->uri_params = array();
    }

    public function get($key = null)
    {
        if (is_null($key)) {
            return $this->get_params;
        } else {
            if (array_key_exists($key, $this->get_params)) {
                return $this->get_params[$key];
            } else {
                $this->get_params[$key] = new FrameworkRequestParam('get',
                    $key, null);
                return $this->get_params[$key];
            }
        }
    }

    public function post($key = null)
    {
        if (is_null($key)) {
            return $this->post_params;
        } else {
            if (array_key_exists($key, $this->post_params)) {
                return $this->post_params[$key];
            } else {
                $this->post_params[$key] = new FrameworkRequestParam('post',
                    $key, null);
                return $this->post_params[$key];
            }
        }
    }

    public function uri($key = null)
    {
        if (is_null($key)) {
            return $this->uri_params;
        } else {
            if (array_key_exists($key, $this->uri_params)) {
                return $this->uri_params[$key];
            } else {
                $this->uri_params[$key] = new FrameworkRequestParam('uri',
                    $key, null);
                return $this->uri_params[$key];
            }
        }
    }

    public function init_get()
    {
        foreach ($_GET as $k => $v) {
            $this->get_params[$k] = new FrameworkRequestParam('get', $k, $v);
        }
    }

    public function init_post()
    {
        foreach ($_POST as $k => $v) {
            $this->post_params[$k] = new FrameworkRequestParam('post', $k, $v);
        }
    }

    public function init_uri($params)
    {
        foreach ($params as $k => $v) {
            $this->uri_params[$k] = new FrameworkRequestParam('uri', $k, $v);
        }
    }

}
