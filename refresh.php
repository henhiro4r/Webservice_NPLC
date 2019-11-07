<?php 
include('config.php');
$value = json_decode(file_get_contents('php://input'));
//data from logout
$user_id = $_POST['user_id'];

$check = $db->query("SELECT point_now,point_used FROM users WHERE id = $user_id AND role_id = 3");
$count_badge = $db->query("SELECT COUNT(id) as total FROM photo_plays WHERE user_id = $user_id");
$getNum = mysqli_fetch_assoc($count_badge);
$get = mysqli_fetch_assoc($check);
$response = array();

if ($check->num_rows == 1) {
	$response['message'] = "update";
	$update = array();
	$update['point'] = $get['point_now'];
	$update['used'] = $get['point_used'];
	$update['badge'] = $getNum['total'];
	$response['update'] = $update;
	echo json_encode($response);
} else {
	send('Something wrong, please try again!');
}

function send($message){
	$response["message"] = $message;
	// $out = array();
	// $out["msg"] = $message;
	// array_push($response["out"], $out);
	echo json_encode($response);
}
?>