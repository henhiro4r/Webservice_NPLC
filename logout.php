<?php 
include('config.php');
$value = json_decode(file_get_contents('php://input'));
//data from logout
$user_id = $_POST['user_id']; #$value->id

$update = $db->query("UPDATE users SET is_login = '0', last_logout = '$time', updated_at = '$time' WHERE id = $user_id ");
if ($update) {
	kirim("out_ok");
}else{
	kirim("out_no");
}

function kirim($message){
	$response["out"] = array();
	$out = array();
	$out["msg"] = $message;
	array_push($response["out"], $out);
	echo json_encode($response);
}
 ?>