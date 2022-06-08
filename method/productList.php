<?php

header('Content-Type: application/json');
$_REQUEST = json_decode(file_get_contents("php://input"),true);

include_once('includes.php');

if(!isset($_REQUEST['I']))
    die("{'Status': 'ERRO1'}");

$res = $gDb->query("SELECT * FROM user u WHERE u.id = '".$_REQUEST['I']."';");
$row = $gDb->fetch_assoc($res);
$gDb -> free_result($res);

$WHERE = (isset($_REQUEST['product']) ?' WHERE p.id = '.$_REQUEST['product'] : ($row['type'] == 'ADM' ? "WHERE 1" : "WHERE p.storeid = '".$row['storeId']."'"));

$resS = $gDb->query("SELECT * FROM products p ".$WHERE.";");


if($gDb->affected_rows() == 0)
    die("{'Status': 'ERRO2'}");

$resU = $gDb->query("SELECT * FROM store s WHERE s.id = '".$row['storeId']."';");
$rowU = $gDb->fetch_assoc($resU);

echo '{"Status": "OK", "Products": [';

for($i = 0; $rowS = $gDb->fetch_assoc($resS); $i++){
    echo ($i > 0 ? ", " : "").
        '{
            "Id":'.$rowS['id'].',
            "Name":"'.$rowS['name'].'",
            "StoreId":"'.$rowS['storeId'].'",
            "StoreName":"'.$rowU['name'].'",
            "Qtd":'.$rowS['qtd'].'
        }';
}

echo ']}';


$gDb -> free_result($resS);
