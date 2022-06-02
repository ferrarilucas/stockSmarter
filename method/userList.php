<?php

header('Content-Type: application/json');

include_once('includes.php');

if(!isset($_REQUEST['I']))
    die("{'Status': 'ERRO1'}");

$resS = $gDb->query("SELECT * FROM user u WHERE u.id = '".$_REQUEST['I']."';");
$rowS = $gDb->fetch_assoc($resS);
$gDb->free_result($resS);

if(!$rowS)
    die("{'Status': 'ERRO2'}");


$where = ($rowS['type'] == 'ADM' ? 1 : ($rowS['type'] == 'Gerente' ? 's.storeId =('.$rowS['storeId'] .')' : 's.id =('.$rowS['id'] .')'));

$res = $gDb->query("SELECT * FROM user s WHERE ". $where);

echo('{"Status": "OK", "User": [');

for($x = 0; $row = $gDb->fetch_assoc($res); $x++){
    echo(($x > 0 ? ", " : "").
        '{
        "Id":'.$row['id'].',
        "Email":"'.$row['login'].'",
        "Name":"'.$row['name'].'"
    }');
}

echo(']}');