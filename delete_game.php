<?php 
include ('config.php');
$value = json_decode(file_get_contents('php://input'));

$game_id = $_POST['game_id']; # $value->game_id;

$delete = $db->query("DELETE FROM histories WHERE game_id = $game_id AND is_done = '0'");

if ($delete) {
	send('Success');
} else {
	send('Failed, please try again!');
}

function send($message){
    $response["delete"] = $message;
    // $user = array();
    // $user["msg"] = $message;
    // array_push($response["user"], $user);
    echo json_encode($response);
}
?>