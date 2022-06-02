<?php

include("includes.php");

$ret = array(
    'status' => 'ok',
    'Texto' => "Aqui"
);

die(json_encode($ret));