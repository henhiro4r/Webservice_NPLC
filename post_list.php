<?php 
include('config.php');

$data = $db->query("SELECT * from games");
$response["list"] = array();
while ($val = mysqli_fetch_array($data)) { 
    $game = array();
    $game["title"] = $val['title'];
    $game["type"] = $val['type'];
    $game["location"] = $val['location'];
    array_push($response["list"], $game);  
}
echo json_encode($response);

?>