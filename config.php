<?php 
define('DB_NAME','rally');
define('DB_USER','root');
define('DB_PASSWORD','');
define('DB_HOST', 'localhost');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');

$db = new mysqli("localhost", "root", "", "rally");
$now = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
$time = $now->format('Y-m-d H:i:s');
// $now = "ADDTIME(now(),'07:00')";

if (!$db) {
	die("Bad Server Connection");
}

?>