<?php

namespace framework5\init\config;

function read_config(string $path): void
{
    $config = [];
    $config['locales'] = [];
    $config['cookies'] = [];
    require $path;
    $GLOBALS['config'] = $config;
}

function read_env(string $path): void
{
    $config = [];
    $config['env'] = [];
    $config['env']['db'] = [];
    require $path;
    $GLOBALS['env'] = $config['env'];
}
