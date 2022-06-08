<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

include_once('defs.php');
include_once('db.inc.php');

$gDb = new DB_con($dbServer,$dbUser, $dbPass, 'MySQLi', $dbName );