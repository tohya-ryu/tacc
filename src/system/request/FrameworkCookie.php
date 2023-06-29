<?php

class FrameworkCookie {
    
    public $key;
    public $name;
    public $value;
    public $duration; # in seconds
    public $domain;
    public $path;
    public $tls_only;
    public $http_only;
    public $samesite;
    public $state;

    public function __construct($key, $data)
    {
        $this->key = $key;
        $this->value = $data['value'];
        $this->name = AppConf::get('name').'-'.$key;
        $this->duration = $data['duration-seconds'] + 
            ($data['duration-minutes'] * 60) +
            ($data['duration-hours'] * 60 * 60) +
            ($data['duration-days'] * 24 * 60 * 60);
        $this->domain = $data['domain'];
        $this->path = '/'; # other values are useless because of mod rewrite
        $this->tls_only = $data['tls-only'];
        $this->http_only = $data['http-only'];
        $this->samesite = $data['samesite'];
        $this->state = (array_key_exists($this->name, $_COOKIE));
        if ($this->state)
            $this->value = $_COOKIE[$this->name];
    }

    public function set($val = null)
    {
        if (isset($val)) {
            $this->value = $val;
        }
        if (version_compare(strtok(phpversion(), '-'), '7.3.0') >= 0) {
            # 7.3 onwards
            $ar = array(
                    'expires' => time() + $this->duration,
                    'path' => $this->path,
                    'domain' => $this->domain,
                    'secure' => $this->tls_only,
                    'httponly' => $this->http_only
                );
            if (isset($this->samesite))
                $ar['samesite'] = $this->samesite;

            return setcookie($this->name, $this->value, $ar);
        } else {
            # legacy support
            return setcookie(
                $this->name,
                $this->value,
                time() + $this->duration,
                $this->path,
                $this->domain,
                $this->tls_only,
                $this->http_only
            );
        }
    }

    public function unset()
    {
        $this->value = null;
        $this->state = false;
        unset($_COOKIE[$this->name]);
        setcookie($this->name, '', time() - 3600, $this->path, $this->domain);
    }

}
