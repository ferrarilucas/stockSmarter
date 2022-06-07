<?php
header('Content-Type: application/json');
$_REQUEST = json_decode(file_get_contents("php://input"),true);

include_once('includes.php');

if(!isset($_REQUEST['I']))
    die("{'Status': 'ERRO1'}");

$resS = $gDb->query("SELECT * FROM user u WHERE u.id = '".$_REQUEST['I']."';");
$rowS = $gDb->fetch_assoc($resS);
$gDb->free_result($resS);

if(!$rowS)
    die("{'Status': 'ERRO2'}");


$where = ($rowS['type'] == 'ADM' ? 1 : 'p.storeId =('.$rowS['storeId'] .')');

$res = $gDb->query("SELECT * FROM providers p WHERE ". $where);

echo('{"Status": "OK", "Providers": [');

for($x = 0; $row = $gDb->fetch_assoc($res); $x++){
    echo(($x > 0 ? ", " : "").
        '{
        "Id":'.$row['id'].',
        "Name":"'.$row['name'].'",
        "Cnpj":"'.$row['cnpj'].'",
        "Phone":"'.$row['phone'].'",
        "Description":"'.$row['description'].'"
    }');
}

echo(']}');