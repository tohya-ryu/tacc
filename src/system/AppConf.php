<?php

class AppConf {

    private static $version;
    private static $name;
    private static $root_dir;
    private static $environment;
    private static $modules;
    private static $available_languages;
    private static $default_language;
    private static $locales;

    public static function set($config)
    {
        self::$version = $config['version'];
        self::$name = $config['name'];
        self::$root_dir = $config['root_dir'];
        self::$environment = $config['environment'];
        self::$modules = $config['modules'];
        self::$available_languages = $config['available_languages'];
        self::$default_language = $config['default_language'];
        self::$locales = $config['locales'];
    }

    public static function get($attr)
    {
        switch ($attr) {
        case 'version':
        case 'name':
        case 'root_dir':
        case 'environment':
        case 'modules':
        case 'available_languages':
        case 'default_language':
        case 'locales':
            return self::$$attr;
            break;
        default:
            debug_print_backtrace(0,1);
            trigger_error("Unknown config property <b>$attr</b>.",
                E_USER_ERROR);
            return null;
            break;
        }
    }

}
