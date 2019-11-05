<?php 
include('config.php');
$value = json_decode(file_get_contents('php://input'));

$teamA = $_POST['teamA']; # $value->teamA;
$resultA = $_POST['resultA']; # $value->resultA;
$teamB = $_POST['teamB']; # $value->teamB;
$resultB = $_POST['resultB']; # $value->resultB;
$game_id = $_POST['game_id']; # $value->game_id;
$type = $_POST['type'] # $value->game_id;


if ($type == 'S') {
	# code...
} elseif ($type == 'V') {
	# code...
}

?>