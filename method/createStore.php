<?php

include_once('includes.php');

$storeData = array(
    'name' => $_REQUEST['name'],
    'cnpj' => $_REQUEST['cnpj']
);

if($storeData['name'] == null){
    die("{'Status': 'ERRO1', 'Text':'preencha o campo: nome'}");
}

if($storeData['cnpj'] == null){
    die("{'Status': 'ERRO2', 'Text':'preencha o campo: cnpj'}");
}

if(!cnpj_validate($storeData['cnpj'])){
    die("{'Status': 'ERRO3', 'Text':'Digite um CNPJ válido'}");
}

$gDb->query("SELECT * FROM store s WHERE s.name = ". $storeData['name']);

if($gDb -> affected_rows()>0){
    die("{'Status': 'ERRO4', 'Text': 'Loja já cadastrada'}");
}

$gDb->query("SELECT * FROM store s WHERE s.cnpj = ". $storeData['cnpj']);

if($gDb -> affected_rows()>0){
    die("{'Status': 'ERRO5', 'Text':'CNPJ já cadastrado'}");
}

$resS = $gDb->query("INSERT INTO store(name, cnpj) VALUES (".$storeData['name'].", ".$storeData['cnpj'].");");

die("{'Status': 'OK', 'Text':'Unidade Adicionda'}");