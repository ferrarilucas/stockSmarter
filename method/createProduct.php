<?php

header('Content-Type: application/json');
$_REQUEST = json_decode(file_get_contents("php://input"),true);

include_once('includes.php');

if(!isset($_REQUEST['name']) || !isset($_REQUEST['store']) || !isset($_REQUEST['qtd']))
    die("{'Status': 'ERRO1'}");

if(isset($_REQUEST['id']) && $_REQUEST['id'] > 0){
    $sql = "UPDATE products SET name = '".$_REQUEST['name']."', storeId = '".$_REQUEST['store']."', qtd = '".$_REQUEST['qtd']."' WHERE id = '".$_REQUEST['id']."';";

}else{
    $sql = "INSERT INTO products (name, storeid, qtd) "
        ."VALUES ('".$_REQUEST['name']."', '".$_REQUEST['store']."', '".$_REQUEST['qtd']."')";
}

$resS = $gDb->query($sql);

if($gDb->affected_rows() == 0)
    die("{'Status': 'ERRO2'}");

die('{"Status": "OK"}');