<?php
/*
 * Copyright (c) 2022.
 * The OptionsResolver component helps you configure objects with option arrays. It supports default values, option constraints and lazy options.
 */

use Wepesi\Demo\Database;

include __DIR__."/../vendor/autoload.php";
include __DIR__."/Database.php";

$database = new Database([
    'dbname' => 'app',
]);
// Uncaught InvalidArgumentException: The required option "username" is missing.

// $database = new Database([
//     'host' => 'localhost',
//     'dbname' => 'app',
//     'username' => 'root',
//     'password' => 'root',
// ]);
var_dump($database->options);