<?php

# Expected arguments
#--------------------
# 1. Path of directory holding migration files
# 2. Key of database to perform migrations on
#

require 'system/autoload.inc.php';

Framework::init();

$store_manager = FrameworkStoreManager::get();

if ($argc != 3) {
    echo "invalid number of arguments: 2 required \n";
    exit();
}

$dir = $argv[1];
$dbkey = $argv[2];

$db = $store_manager->store($dbkey);

if (!is_dir($dir)) {
    echo "Not a directory '$dir' \n";
    exit();
}

if (!$db) {
    echo "invalid database key $dbkey \n";
    exit();
}

$db->migrate($dir);
