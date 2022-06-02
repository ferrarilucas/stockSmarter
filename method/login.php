<?php
header('Access-Control-Allow-Origin: *' );
header('Access-Control-Allow-Headers: *' );


include_once('includes.php');

$request_body = file_get_contents('php://input');

print_r($request_body);

if(!isset($_REQUEST['l']))
    die('{"Status": "ERRO1", "Text": "Preencha todos os campos"}');
if(!isset($_REQUEST['p']))
    die('{"Status": "ERRO2", "Text": "Preencha todos os campos"}');

$login = $_REQUEST['l'];
$pass = $_REQUEST['p'];

if(!$pass || !$login){
    die('{"Status": "ERRO3", "Text": "Preencha todos os campos"}');
}

$res = $gDb->query("SELECT * FROM user u WHERE u.login = '". $login. "' AND u.pass = '".$pass."';");

if($gDb->affected_rows() == 0){
    die('{"Status": "ERRO2", "Text": "Usuário ou Senha incorretos"}');
}

$row = $gDb->fetch_assoc($res);

die('{"Status": "OK",
    "User":{
        "Id":'.$row['id'].',
        "Token": "'.$row['token'].'",
        "Name":"'.$row['name'].'",
        "Type": "'.$row['type'].'"
        }}');