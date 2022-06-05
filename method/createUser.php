<?php
header('Content-Type: application/json');
$_REQUEST = json_decode(file_get_contents("php://input"),true);
include_once('includes.php');

if(!isset( $_REQUEST['name']) || !isset( $_REQUEST['email']) || !isset( $_REQUEST['p']))
    die('{"Status": "Erro01", "Text": "Preencha todos os campos"}');

$cpf = preg_replace( '/[^0-9]/is', '', $_REQUEST['cpf'] );

if(!validaCPF($cpf))
    die('{"Status": "Erro02", "Text": "CPF inválido"}');

$res = $gDb->query("SELECT * FROM user u WHERE u.cpf = '".$cpf."' OR login = '".$_REQUEST['email']."';");

if($gDb->affected_rows($res) > 0 && $_REQUEST['e'] == 'add')
    die('{"Status": "Erro03", "Text": "CPF ou Email já cadastrado"}');

$gDb -> query("INSERT INTO user (name, login, pass,type, storeId, cpf) VALUES ('$_REQUEST[name]', '$_REQUEST[email]', '$_REQUEST[p]','$_REQUEST[t]' , '$_REQUEST[storelist]' ,'$cpf')");

die('{"Status": "OK", "Text": "Usuario cadastrado com sucesso"}');

