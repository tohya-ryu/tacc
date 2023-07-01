<?php

$config['env']['type'] = Env::Development; # Env::Production
$config['env']['root_dir'] = '/';

$config['env']['db']['main'] = [
    'type' => 'mysql',
    'host' => 'localhost',
    'port' => '3306',
    'name' => 'my_database',
    'user' => 'user',
    'pswd' => 'password',
    'spec' => [
        'engine' => 'InnoDB',
        'charset' => 'utf8mb4',
        'collate' => 'utf8mb4_unicode_ci'
    ]
];

