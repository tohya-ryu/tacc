<?php

namespace framework5;

class request {

    public string $useragent;
    public string $method;
    public string $ip;
    public string $transport;
    public bool   $is_secure;
    public array  $languages;
    public array  $input;
    public array  $cookies;

    public function __construct(): void
    {
        $this->useragent = null;
        $this->method    = null;
        $this->ip        = null;
        $this->transport = null;
        $this->is_secure = null;
        $this->languages = null;
        $this->input     = [];
        $this->cookies   = [];
    }

}
