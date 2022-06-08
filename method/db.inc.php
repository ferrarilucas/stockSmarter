<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

class DB_con {

public $dbms, $db, $con = null;

protected $host, $user, $pass, $has_transaction;

function __construct($host, $user, $pass, $dbms, $db = '') {

$this->host = $host;

$this->user = $user;

$this->pass = $pass;

$this->dbms = $dbms;

$this->db = $db;

$this->has_transaction = false;

$this->connect();

if($this->db != '' &&($this->dbms == 'MySQLi' || $this->dbms == 'MySQL'))

$this->select_db($this->db);

register_shutdown_function(array(&$this, 'close'));

}

//============================ conexão e seleção de BD ============================//

function connect() {

$this->close();

if($this->dbms == 'MySQLi') {

// don't use db name here, so the create database fails

$this->con = mysqli_connect($this->host, $this->user, $this->pass);

} else if($this->dbms == 'MySQL') {

$this->con = @mysql_connect($this->host, $this->user, $this->pass, true);

} else if($this->dbms == 'PostgreSQL') {

$this->con = pg_connect('host='.$this->host.($this->db != '' ? ' dbname='.$this->db : '')

.' user='.$this->user.' password='.$this->pass);

}

if(!$this->con) {

header('Content-Type: text/html; charset=UTF-8', true);

header('HTTP/1.1 500 Internal Server Error');

die('Erro ao conectar no '.($this->db != '' ? ' BD '.$this->db.' do ' : '').$this->dbms

.' com as credenciais '.$this->user.'@'.$this->host.': '.utf8_encode($this->error()));

}

}

function close() {

if($this->con) {

if($this->has_transaction) // caso o PHP sofra um crash, chama o rollback

$this->rollback(); // na chamada da shutdown_function

if($this->dbms == 'MySQLi') {

mysqli_close($this->con);

} else if($this->dbms == 'MySQL') {

mysql_close($this->con);

} else if($this->dbms == 'PostgreSQL') {

pg_close($this->con);

}

$this->con = null;

}

}

function select_db($db) {

$ret = true;

if($this->dbms == 'MySQLi') {

$ret = mysqli_select_db($this->con, $db);

} else if($this->dbms == 'MySQL') {

$ret = mysql_select_db($db, $this->con);

} else if($this->dbms == 'PostgreSQL' &&($db != $this->db || $db != pg_dbname($this->con))) {

$this->db = $db;  // para trocar o DB no Postgre deve-se fechar a conexão atual

$this->connect(); // e abrir uma nova passando o dbname desejado

}

if($ret)

$this->db = $db;

return $ret;

}

function escape_string($str) {

if($this->dbms == 'MySQLi')

return mysqli_real_escape_string($this->con, $str);

if($this->dbms == 'MySQL')

return mysql_real_escape_string($str, $this->con);

if($this->dbms == 'PostgreSQL')

return pg_escape_string($this->con, $str);

}

function query($q, $echoar = false) {

$q = "# ".$_SERVER['PHP_SELF']."\n" . $q;

//$tempo_ini = time() + microtime();

if($this->dbms == 'MySQLi') {

$res = mysqli_query($this->con, $q);

} else if($this->dbms == 'MySQL') {

$res = mysql_query($q, $this->con);

} else if($this->dbms == 'PostgreSQL') {

$res = pg_query($this->con, $q);

}

if($this->error()) {

echo('Erro de DB em '.$_SERVER['PHP_SELF']." '".$this->db."':".$this->error().'<br/>');

$echoar=true;

}

if($echoar) {

echo('<pre>###### '.durationToStr((time() + microtime()) - $tempo_ini, true)." ######\n"

.$q.";</pre>\r\n");

}

return $res;

}

function fetch_array($res) {

if($this->dbms == 'MySQLi')

return mysqli_fetch_array($res);

if($this->dbms == 'MySQL')

return mysql_fetch_array($res);

if($this->dbms == 'PostgreSQL')

return pg_fetch_array($res);

}

function fetch_assoc($res) {

if($this->dbms == 'MySQLi')

return mysqli_fetch_assoc($res);

if($this->dbms == 'MySQL')

return mysql_fetch_assoc($res);

if($this->dbms == 'PostgreSQL')

return pg_fetch_assoc($res);

}

//======================= checagem sobre as consultas =======================//

function affected_rows() {

if($this->dbms == 'MySQLi') return mysqli_affected_rows($this->con);

else if($this->dbms == 'MySQL') return mysql_affected_rows($this->con);

else if($this->dbms == 'PostgreSQL') return pg_affected_rows($this->con);

}

function error() {

if($this->dbms == 'MySQLi') {

if($this->con)

return mysqli_error($this->con);

return mysqli_connect_error();

}

if($this->dbms == 'MySQL') return mysql_error($this->con);

if($this->dbms == 'PostgreSQL') return pg_last_error($this->con);

}


function free_result($res) {

if($this->dbms == 'MySQLi') return mysqli_free_result($res);

if($this->dbms == 'MySQL') return mysql_free_result($res);

if($this->dbms == 'PostgreSQL') return pg_free_result($res);

}
function num_rows($res) {

if($this->dbms == 'MySQLi') return mysqli_num_rows($res);

if($this->dbms == 'MySQL') return mysql_num_rows($res);

if($this->dbms == 'PostgreSQL') return pg_num_rows($res);

}

function dbToNumber($dt) {

if(is_null($dt) || !$dt)

return 0;

return $dt;

}

function dbToBool($dt) {

return (dbToNumber($dt) > 0 ? 'TRUE' : 'FALSE');

}

// converts from dd/mm/aaaa HH:mm:ss to aaaa-mm-dd HH:mm:ss

function strToDbDate($dt, $aspas = true) {

if(!$dt)

return 'NULL';

if($dt[4] == '-') // already in format aaaa-mm-dd

return($aspas ? "'" : '').str_replace('T', ' ', $dt).($aspas ? "'" : '');

$pos = strpos($dt, '/');

$rpos = strrpos($dt, '/');

$pos_hora = strpos($dt, ' ');

if($pos === false && $pos_hora === false)

return 'NULL';

return($aspas ? "'" : '').($pos === false ? '' : substr($dt, $rpos + 1, 4).'-'.substr($dt, $pos + 1, $rpos - $pos - 1).'-'.substr($dt, 0, $pos))

.($pos_hora !== false ? substr($dt, $pos_hora) : '').($aspas ? "'" : '');

}

// converts from dd/mm/aaaa to timestamp

function strToDbTimestamp($dt) {

if(!$dt)

return '';

$pos = strpos($dt, '/');

$rpos = strrpos($dt, '/');

return strtotime(substr($dt, $rpos + 1).'-'.substr($dt, $pos + 1, $rpos - $pos - 1).'-'.substr($dt, 0, $pos));

}

// converts from aaaa-mm-dd HH:mm:ss to dd/mm/aaaa

function DbToStrDate($dt) {

if(!$dt)

return '';

if($dt[2] == '/') { // already in format dd/mm/aaaa

if(strlen($dt) <= 10)

return $dt;

return substr($dt, 0, 10);

}

$day = substr($dt, strrpos($dt, '-') + 1, 2);

$month = substr($dt, strpos($dt, '-') + 1, strrpos($dt, '-') - strpos($dt, '-') - 1);

$year = substr($dt, 0, strpos($dt, '-'));

if(intval($day) > 0 || intval($month) > 0 || intval($year) > 0)

return $day.'/'.$month.'/'.$year;

return '';

}

// converts from aaaa-mm-dd HH:mm:ss to dd/mm/aaaa HH:mm[:ss]

function DbToStrDateTime($dt, $year4digits = true, $showSeconds = true) {

if(!$dt)

return '';

if(strpos($dt, 'T') !== false)

$dt = str_replace('T', ' ', $dt);

$day = substr($dt, strrpos($dt, '-') + 1, 2);

$month = substr($dt, strpos($dt, '-') + 1, strrpos($dt, '-') - strpos($dt, '-') - 1);

$year = substr($dt, 0, strpos($dt, '-'));

if(!$year4digits)

$year = substr($year, 2);

if(intval($day) > 0 || intval($month) > 0 || intval($year) > 0) {

$idx = strpos($dt, ' ');

return $day.'/'.$month.'/'.$year

.($idx !== false ? substr($dt, $idx, ($showSeconds ? strlen($dt) - $idx : 6)) : '');

}

return '';

}

// converts from aaaa-mm-dd HH:mm:ss to HH:mm:ss

function DbToStrTime($dt) {

return(strpos($dt, ' ') === false ? $dt : substr($dt, strpos($dt, ' ')+ 1));

}

function durationToStr($duration, $showMs = false) {

$ms = $duration - floor($duration);

return str_pad(floor($duration / 3600), 2, '0', STR_PAD_LEFT) // hh

.':'.str_pad(floor(($duration % 3600) / 60), 2, '0', STR_PAD_LEFT) // mm

.':'.str_pad($duration % 60, 2, '0', STR_PAD_LEFT) // ss

.($showMs && $ms > 0 ? substr($ms, 1, 6) : ''); // ms

}
}