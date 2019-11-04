<?php 
include('config.php');
$value = json_decode(file_get_contents('php://input'));
$play_id = $_POST['play_id']; # $value->game_id
$user_id = $_POST['user_id']; # $value->user_id

$game_check = $db->query('SELECT id,type,code FROM games WHERE id = $play_id');
$photo_check = $db->query('SELECT id,code,badge FROM photos WHERE id = $play_id');

if ($game_check || $photo_check) {
	if ($game_check) {
		$his_check = $db->query('SELECT * FROM histories WHERE game_id = $play_id AND is_done = "0"');
		if ($his_check) {
			$fetch_his = mysqli_fetch_assoc($his_check);
			if ($fetch_his['teamB'] == null) {
				# update to team B

			} else {
				send('The game is full');
			}
		} else {
			# insert to hostories
		}
	} elseif ($photo_check) {
		# insert to photo_play
	}
	
} else {
	send('Invalid QR Code');
}

function send($message){
    $response["user"] = array();
	$user = array();
	$user["msg"] = $message;
	array_push($response["user"], $user);
	echo json_encode($response);
}
?>