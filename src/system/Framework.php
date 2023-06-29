<?php

class Framework {

    const ENV_DEV  = 1;
    const ENV_PRD  = 2;

    public static function init()
    {
        self::load_config();
    }

    public static function locale()
    {
        $key = FrameworkLanguage::get()->tag;
        $loc = AppConf::get('locales')[$key];
        return new $loc();
    }

    private static function load_config()
    {
        $config = array();
        $config['locales'] = array();
        require 'app/config/app.conf.php';
        require 'app/config/locale.conf.php';
        AppConf::set($config);
    }

}
