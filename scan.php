<?php 
include('config.php');
$value = json_decode(file_get_contents('php://input'));
$play_id = $_POST['play_id']; # $value->game_id
$user_id = $_POST['user_id']; # $value->user_id

$game_check = $db->query('SELECT id,type,code FROM games WHERE id = $play_id'); // cek if code is game_id
$photo_check = $db->query('SELECT id,code,badge FROM photos WHERE id = $play_id'); // cek if code is photo_id

if ($game_check || $photo_check) {
	if ($game_check) { // if game
		$check_play = $db->query('SELECT * FROM histories WHERE game_id = $play_id AND (teamA = $id OR teamB = $id) AND is_done = "1"'); // count plays on game
		$sum = myqli_num_rows($check_play);
		if ($sum == 3) {
			send('Max play reached');
		} else { // less than 3 plays
			$type = mysqli_fetch_assoc($game_check);
			if ($type['type'] = 'S') { // single game
				$his_check = $db->query('SELECT * FROM histories WHERE game_id = $play_id AND is_done = "0"'); // cek if game exist
				if ($his_check) { // exist
					send('The game is full'); 
				} else { // insert new
					$play = $db->query("INSERT INTO histories VALUES(null, $play_id, $user_id, null, null, 1, null, null, '0', '$time', '$time')");
					if ($play) {
						send('Good luck, have fun!'); // success
					} else {
						send('Something wrong, please try again!'); // error
					}
				}
			} elseif ($type['type'] = 'V') { // versus game
				$his_check = $db->query('SELECT * FROM histories WHERE game_id = $play_id AND is_done = "0"'); // check game on history
				if ($his_check) {
					$fetch_his = mysqli_fetch_assoc($his_check);
					if ($fetch_his['teamB'] == null) { // check game full or not
						$update = $db->query("UPDATE histories SET teamB = $user_id, updated_at = '$time' WHERE game_id = $play_id AND is_done = '0'"); // no
						if ($update) {
							send('Good luck, have fun!'); // success
						} else {
							send('Something wrong, please try again!'); // error
						}
					} else {
						send('The game is full'); // yes
					}
				} else { // game not found in history
					$play = $db->query("INSERT INTO histories VALUES(null, $play_id, $user_id, null, null, null, null, null, '0', '$time', '$time')"); // make new
					if ($play) {
						send('Good luck, have fun!'); // success
					} else {
						send('Something wrong, please try again!'); // error
					}
				}
			}
		}
	} elseif ($photo_check) { // if photo
		$play = $db->query("INSERT INTO photo_plays VALUES(null, $play_id, $user_id, '$time', '$time')"); // insert new
		if ($play) {
			send('Success!'); // success
		} else {
			send('Something wrong, please try again!'); // error
		}
	}
} else {
	send('Invalid QR Code');
}

function send($message){	
    $response["msg"] = $message;
	// $user = array();
	// $user["msg"] = $message;
	// array_push($response["user"], $user);
	echo json_encode($response);
}
?>