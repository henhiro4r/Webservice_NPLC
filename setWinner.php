<?php 
include('config.php');
$value = json_decode(file_get_contents('php://input'));

$teamA = $_POST['teamA']; # $value->teamA;
$resultA = $_POST['resultA']; # $value->resultA;
$teamB = $_POST['teamB']; # $value->teamB;
$resultB = $_POST['resultB']; # $value->resultB;
$game_id = $_POST['game_id']; # $value->game_id;
$type = $_POST['type'] # $value->game_id;

// 100 win
// 50 draw
// 25 lose

$check = $db->query('SELECT * FROM histories WHERE game_id = $game_id AND is_done = "0"');

if ($check->num_rows == 1) {
	if ($type == 'S') { // single
		if ($resultA == 'W') {
			$update = $db->query('UPDATE histories SET resultA = "$resultA", pointA = 100, resultB = "$resultB", pointB = 25, is_done = "1", updated_at = "$time"');
			$upTeam = $db->query("UPDATE users SET point_now = point_now+100 WHERE id = $teamA");
			if ($update && $upTeam) {
				send('Success');
			} else {
				send('Try again!');
			}
		} else {
			$update = $db->query('UPDATE histories SET resultA = "$resultA", pointA = 25, resultB = "$resultB", pointB = 100, is_done = "1", updated_at = "$time"');
			$upTeam = $db->query("UPDATE users SET point_now = point_now+25 WHERE id = $teamA");
			if ($update && $upTeam) {
				send('Success');
			} else {
				send('Try again!');
			}
		}
	} elseif ($type == 'V') { // versus
		if ($resultA == 'W') {
			$update = $db->query('UPDATE histories SET resultA = "$resultA", pointA = 100, resultB = "$resultB", pointB = 25, is_done = "1", updated_at = "$time"');
			$upTeamA = $db->query("UPDATE users SET point_now = point_now+100 WHERE id = $teamA");
			$upTeamB = $db->query("UPDATE users SET point_now = point_now+25 WHERE id = $teamB");
			if ($update && $upTeamA && $upTeamB) {
				send('Success');
			} else {
				send('Try again!');
			}
		} elseif ($resultA == 'D') {
			$update = $db->query('UPDATE histories SET resultA = "$resultA", pointA = 50, resultB = "$resultB", pointB = 50, is_done = "1", updated_at = "$time"');
			$upTeamA = $db->query("UPDATE users SET point_now = point_now+50 WHERE id = $teamA");
			$upTeamB = $db->query("UPDATE users SET point_now = point_now+50 WHERE id = $teamB");
			if ($update && $upTeamA && $upTeamB) {
				send('Success');
			} else {
				send('Try again!');
			}
		} else {
			$update = $db->query('UPDATE histories SET resultA = "$resultA", pointA = 25, resultB = "$resultB", pointB = 100, is_done = "1", updated_at = "$time"');
			$upTeamA = $db->query("UPDATE users SET point_now = point_now+25 WHERE id = $teamA");
			$upTeamB = $db->query("UPDATE users SET point_now = point_now+100 WHERE id = $teamB");
			if ($update && $upTeamA && $upTeamB) {
				send('Success');
			} else {
				send('Try again!');
			}
		}
	}	
} else {
	send('Data not found!');
}


function send($message){
    $response["winner"] = $message;
	// $his = array();
	// $his["msg"] = $message;
	// array_push($response["history"], $his);
	echo json_encode($response);
}


?>