<?php

include_once('includes.php');

if(!isset( $_REQUEST['name']) || !isset( $_REQUEST['email']) || !isset( $_REQUEST['p']))
    die('{"Status": "Erro01", "Text": "Preencha todos os campos"}');

$cpf = preg_replace( '/[^0-9]/is', '', $_REQUEST['cpf'] );

if(!validaCPF($cpf))
    die('{"Status": "Erro02", "Text": "CPF inválido"}');

$gDb -> query("INSERT INTO user (name, login, pass, storeId, cpf) VALUES ('$_REQUEST[name]', '$_REQUEST[email]', '$_REQUEST[p]', '$_REQUEST[storelist]' ,'$cpf')");

die('{"Status": "OK", "Text": "Usuario cadastrado com sucesso"}');

