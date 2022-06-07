<?php
header('Content-Type: application/json');
$_REQUEST = json_decode(file_get_contents("php://input"),true);

include_once('includes.php');

$storeData = array(
    'name' => $_REQUEST['Name'],
    'cnpj' => $_REQUEST['Cnpj']
);

if(!isset($_REQUEST['I'])){
    die("{'Status': 'ERRO1'}");
}

if($storeData['name'] == null){
    die("{'Status': 'ERRO1', 'Text':'preencha o campo: nome'}");
}

if($storeData['cnpj'] == null){
    die("{'Status': 'ERRO2', 'Text':'preencha o campo: cnpj'}");
}

if(!cnpj_validate($storeData['cnpj'])){
    die("{'Status': 'ERRO3', 'Text':'Digite um CNPJ válido'}");
}

$cnpj = preg_replace('/[^0-9]/', '', (string) $storeData['cnpj']);

$gDb->query("SELECT * FROM store s WHERE s.name = '". $storeData['name']."' OR s.cnpj = '". $cnpj."';");

if($gDb -> affected_rows()>0){
    die("{'Status': 'ERRO4', 'Text': 'Loja já cadastrada'}");
}

$resS = $gDb->query("SELECT u.type FROM user u WHERE u.id = ".$_REQUEST['I'].";");
$rowS = $gDb->fetch_assoc($resS);

if($rowS['type'] != 'ADM')
    die('{"Status": "ERRO4", "Text": "Apenas administradores podem criar lojas"}');


$gDb->query("SELECT * FROM store s WHERE s.cnpj = ". $cnpj);

if($gDb -> affected_rows() > 0){
    die("{'Status': 'ERRO5', 'Text':'CNPJ já cadastrado'}");
}

$resS = $gDb->query("INSERT INTO store(name, cnpj) VALUES ('".$storeData['name']."', '".$cnpj."');");

die('{"Status": "OK"}');