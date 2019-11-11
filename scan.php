<?php 
include('config.php');
$value = json_decode(file_get_contents('php://input'));
$play_id = $_POST['play_id'];
$user_id = $_POST['user_id'];

$game_check = $db->query("SELECT * FROM games WHERE code = '$play_id'"); // cek if code is game_id
$gameFetch = mysqli_fetch_assoc($game_check);
$photo_check = $db->query("SELECT * FROM photos WHERE code = '$play_id'"); // cek if code is photo_id
$photoFetch = mysqli_fetch_assoc($photo_check);

if ($game_check->num_rows == 1) { // if game
	$game_id = $gameFetch["id"];
	$check_play = $db->query("SELECT * FROM histories WHERE game_id = $game_id AND (teamA = $user_id OR teamB = $user_id) AND is_done = '1'"); // count
	if ($check_play->num_rows == 3) {
		send('Max play reached');
	} else { // less than 3 plays
		if ($gameFetch["type"] == 'S') { // single game
			$his_check = $db->query("SELECT * FROM histories WHERE game_id = $game_id AND is_done = '0'"); // cek if game exist
			if ($his_check->num_rows == 1) { // exist
				send('The game is full'); 
			} else { // insert new
				$play = $db->query("INSERT INTO histories VALUES(null, $game_id, $user_id, null, null, 1, null, null, '0', '$time', '$time')");
				if ($play) {
					send('Good luck, have fun!'); // success
				} else {
					send('Something wrong, please try again!'); // error
				}
			}
		} elseif ($gameFetch["type"] == 'V') { // versus game
			$his_check = $db->query("SELECT * FROM histories WHERE game_id = $game_id AND is_done = '0'"); // check game on history
			if ($his_check->num_rows == 1) {
				$fetch_his = mysqli_fetch_assoc($his_check);
				if ($fetch_his['teamA'] == $user_id) {
					send('Already in game!');
				} else {
					if ($fetch_his['teamB'] == null) { // check game full or not
						$update = $db->query("UPDATE histories SET teamB = $user_id, updated_at = '$time' WHERE game_id = $game_id AND is_done = '0'"); // no
						if ($update) {
							send('Good luck, have fun!'); // success
						} else {
							send('Something wrong, please try again!'); // error
						}
					} else {
						send('The game is full'); // yes
					}
				}
			} else { // game not found in history
				$play = $db->query("INSERT INTO histories VALUES(null, $game_id, $user_id, null, null, null, null, null, '0', '$time', '$time')"); // make new
				if ($play) {
					send('Good luck, have fun!'); // success
				} else {
					send('Something wrong, please try again!'); // error
				}
			}
		}
	}
} elseif ($photo_check->num_rows == 1) { // if photo
	$photoId = $photoFetch["id"];
	$play = $db->query("INSERT INTO photo_plays VALUES(null, $photoId, $user_id, '$time', '$time')"); // insert new
	if ($play) {
		send('Success!'); // success
	} else {
		send('Something wrong, please try again!'); // error
	}
} else {
	send('Invalid QR Code');
}

function send($message){	
	$response["message"] = $message;
	echo json_encode($response);
}
?>