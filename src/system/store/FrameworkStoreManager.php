<?php

class FrameworkStoreManager {
    use FrameworkSingleton;

    private $objects;
    private $default_db_key;

    private final function __construct()
    {
        $this->objects = array();
        $this->database_setup();
    }

    public function store($key = null)
    {
        if ($key) {
            if (array_key_exists($key, $this->objects)) {
                return $this->objects[$key];
            } else {
                return false;
            }
        } else {
            return $this->objects[$this->default_db_key];
        }
    }

    public function set_default_store($key)
    {
        if (array_key_exists($key, $this->objects)) {
            $default_db_key = $key;
        } else {
            //TODO throw error
        }
    }

    private function database_setup()
    {
        // create database objects from config files
        foreach (new FilesystemIterator(
            realpath('app/config/store/db/')) as $i)
        {
            if (is_file($i)) {
                $config = array();
                $config['connections'] = array();
                require ($i->getPathname());
                switch ($config['type']) {
                case 'mysql/mariadb':
                    $this->objects[$config['key']] =
                        new FrameworkMariadb($config);
                    break;
                }
                if ($config['default'])
                    $this->default_db_key = $config['key'];
            }
        }
    }
}
