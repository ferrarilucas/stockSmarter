<?php
header('Content-Type: application/json');

$_REQUEST = json_decode(file_get_contents("php://input"),true);

include_once('includes.php');

if(!isset( $_REQUEST['Name']) || !isset( $_REQUEST['Phone']) || !isset( $_REQUEST['Cnpj']))
    die('{"Status": "Erro01", "Text": "Preencha todos os campos"}');

$phone = preg_replace( '/[^0-9]/is', '', $_REQUEST['Phone'] );
$cnpj = preg_replace( '/[^0-9]/is', '', $_REQUEST['Cnpj'] );

if(!cnpj_validate($cnpj))
    die('{"Status": "Erro02", "Text": "CNPJ inválido"}');

$res = $gDb->query("SELECT u.storeId FROM user u WHERE u.id = '".$_REQUEST['I']."';");
$row = $gDb->fetch_assoc($res);

$gDb -> query("INSERT INTO providers (name, phone, description, cnpj, storeId) VALUES ('".$_REQUEST['Name']."', '".$phone."','".$_REQUEST['Description']."', '".$cnpj."', '".$row['storeId']."');");

die('{"Status": "OK"}');