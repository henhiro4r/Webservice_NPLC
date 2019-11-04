<?php 
include('config.php');
$value = json_decode(file_get_contents('php://input'));
//data from fragment history
$id = $value->id;

$data = $db->query("SELECT * from histories WHERE (teamA = $id OR teamB = $id) AND is_done = '1'");
$jum = mysqli_num_rows($data);
$response["history"] = array();

if($jum > 0){
    while ($val = mysqli_fetch_array($data)) {
        if($val['tim1'] == $id){
            $his = array();
            $his["id"] = $val['id'];
            $his["id_pos"] = $val['id_pos'];
            $his["htim1"] = $val['htim1'];
            $his["tim2"] = $val['tim2'];
            $his["htim2"] = $val['htim2'];
            $his["created_at"] = $val['created_at'];
            $his["msg"] = "ok";
            array_push($response["history"], $his);
        }else if($val['tim2'] == $id){
            $his = array();
            $his["id"] = $val['id'];
            $his["id_pos"] = $val['id_pos'];
            $his["htim1"] = $val['htim2'];
            $his["tim2"] = $val['tim1'];
            $his["htim2"] = $val['htim1'];
            $his["created_at"] = $val['created_at'];
            $his["msg"] = "ok";
            array_push($response["history"], $his);
        }
        
    }
    echo json_encode($response);
}else{
    kirim("null"); //empty
}


function kirim($message){
    $response["history"] = array();
	$his = array();
    $his["id"] = "";
    $his["id_pos"] = "";
	$his["htim1"] = "";
	$his["tim2"] = "";
	$his["htim2"] = "";
	$his["created_at"] = "";
	$his["msg"] = $message;
	array_push($response["history"], $his);
	echo json_encode($response);
}

?>