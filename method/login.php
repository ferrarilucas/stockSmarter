<?php

include_once('includes.php');

$login = $_REQUEST['l'];
$pass = $_REQUEST['p'];

if(!$pass || !$login){
    die('{"Status": "ERRO1", "Text": "Preencha todos os campos"}');
}

$gDb->query("SELECT * FROM user u WHERE u.login = ". $login. "AND u.pass = ".$pass.";");

if($gDb->affected_rows() == 0){
    die('{"Status": "ERRO2", "Text": "Usuário ou Senha incorretos"}');
}

die('{"Status": "OK"}');