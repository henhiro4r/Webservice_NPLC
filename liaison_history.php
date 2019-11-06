<?php 
include('config.php');
$value = json_decode(file_get_contents('php://input'));
//data from fragment history
$game_id = $_GET['game_id']; #$value->game_id;
$type = $_GET['type']; #$value->type;

$data = $db->query("SELECT * from histories WHERE game_id = $game_id AND is_done = '1'");
$jum = mysqli_num_rows($data);
$response = array();

if($jum > 0){
    $response['message'] = $jum;
    while ($val = mysqli_fetch_assoc($data)) {
        $idA = $val['teamA'];
        $idB = $val['teamB'];
        $tA = $db->query("SELECT id,name FROM users WHERE id = $idA");
        $teamA = mysqli_fetch_assoc($tA);
        $tB = $db->query("SELECT id,name FROM users WHERE id = $idB");
        $teamB = mysqli_fetch_assoc($tB);
        $his = array();
        $his["time_start"] = $val['created_at'];
        $his["nameA"] = $teamA['name'];
        $his["statusA"] = $val['resultA'];
        $his["pointA"] = $val['pointA'];
        $his["nameB"] = $teamB['name'];
        $his["statusB"] = $val['resultB'];
        $his["pointB"] = $val['pointB'];
        array_push($response["history"], $his);
    }
    echo json_encode($response);
}else{
    send("null"); //empty
}

function send($message){
    $response["message"] = $message;
	// $his = array();
	// $his["msg"] = $message;
	// array_push($response["history"], $his);
	echo json_encode($response);
}

?>