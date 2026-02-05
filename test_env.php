<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$env = parse_ini_file(__DIR__ . '/.env');
var_dump($env);
