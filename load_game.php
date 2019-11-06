<?php 
include('config.php');
$value = json_decode(file_get_contents('php://input'));
//data from fragment history
$game_id = $_GET['game_id']; #$value->game_id;
// $type = $_GET['type']; #$value->type;

$data = $db->query("SELECT * from histories WHERE game_id = $game_id AND is_done = '0'");
$val = mysqli_fetch_assoc($data);
$response = array();

if ($data) {
	$idA = $val['teamA'];
	$idB = $val['teamB'];
	$tA = $db->query("SELECT id,name FROM users WHERE id = $idA");
	$teamA = mysqli_fetch_assoc($tA);
	$tB = $db->query("SELECT id,name FROM users WHERE id = $idB");
	$teamB = mysqli_fetch_assoc($tB);
	$game = array();
	$game['teamA'] = $teamA['id'];
	$game['nameA'] = $teamA['name'];
	$game['teamB'] = $teamB['id'];
	$game['nameB'] = $teamB['name'];
	$response['current'] = $game;
	echo json_encode($response);
} else {
	send('No running game');
}


function send($message){
    $response["message"] = $message;
	// $his = array();
	// $his["msg"] = $message;
	// array_push($response["history"], $his);
	echo json_encode($response);
}
?>