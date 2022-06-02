<?php

include_once('includes.php');

if(!isset($_REQUEST['I']))
    die("{'Status': 'ERRO1'}");


$res = $gDb->query("SELECT * FROM user u WHERE u.id = '".$_REQUEST['I']."';");
$row = $gDb->fetch_assoc($res);

$WHERE = ($row['type'] == 'admin') ? "WHERE 1" : "WHERE s.id = '".$row['storeId']."'";


$resS = $gDb->query("SELECT * FROM store s ".$WHERE.";");


echo('{"Status": "OK", "Store": [');

    for($st = 0; $rowS =$gDb->fetch_assoc($resS); $st++){
        echo(($st > 0 ? ", " : "").
            '{
            "Id":'.$rowS['id'].',
            "Name":"'.$rowS['name'].'"
        }');


    }

echo(']}');
exit;
