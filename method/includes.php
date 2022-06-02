<?php

include_once('defs.php');
include_once('db.inc.php');

$gDb = new DB_con($dbServer,$dbUser, $dbPass, 'MySQLi', $dbName );