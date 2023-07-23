<?php

# utility functions in global namespace

function request(): framework5\request
{
    return $GLOBALS['request'];
}

function service(string $service): framework5\service
{
    return $GLOBALS['services'][$service];
}

function register_service(string $key, framework5\service $service): void
{
    $GLOBALS['services'][$key] = $service;
}

function data(string $data)
{
    return $GLOBALS['data'][$data];
}

function register_data(string $key, $data): void
{
    $GLOBALS['data'][$key] = $data;
}

function config(string $key)
{
    return $GLOBALS['config'][$key];
}

function env(string $key)
{
    return $GLOBALS['env'][$key];
}

function db(string $key)
{
    if (isset($GLOBALS['db'][$key])) {
        return $GLOBALS['db'][$key];
    } else {
        # TODO get connection and store at $GLOBALS['db'][$key]
    }
}
