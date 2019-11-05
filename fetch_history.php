<?php 
include('config.php');
$value = json_decode(file_get_contents('php://input'));
//data from fragment history
$user_id = $_GET['user_id']; #$value->id;

$data = $db->query("SELECT * from histories WHERE (teamA = $user_id OR teamB = $user_id) AND is_done = '1'");
$jum = mysqli_num_rows($data);
$response["history"] = array();

if($jum > 0){
    while ($val = mysqli_fetch_assoc($data)) {
        if($val['teamA'] == $user_id){
            $his = array();
            $his["game_id"] = $val['game_id'];
            $his["time_start"] = $val['created_at'];
            $his["status"] = $val['resultA'];
            $his["point"] = $val['pointA'];
            // $his["msg"] = "ok";
            array_push($response["history"], $his);
        }else if($val['teamB'] == $user_id){
            $his = array();
            $his["game_id"] = $val['game_id'];
            $his["time_start"] = $val['created_at'];
            $his["status"] = $val['resultB'];
            $his["point"] = $val['pointB'];
            // $his["msg"] = "ok";
            array_push($response["history"], $his);
        }
    }
    echo json_encode($response);
}else{
    send("null"); //empty
}


function send($message){
    $response["history"] = $message;
	// $his = array();
	// $his["msg"] = $message;
	// array_push($response["history"], $his);
	echo json_encode($response);
}

?>