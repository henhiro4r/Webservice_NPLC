<?php 
include('koneksi.php');

$data = $db->query("SELECT * from bantuan WHERE is_valid = '1' ");
$response["bantuan"] = array();
while ($val = mysqli_fetch_array($data)) { 
    $ban = array();
    $ban["id"] = $val['id'];
    $ban["judul"] = $val['judul'];
    $ban["des"] = $val['des'];
    $ban["updated_at"] = $val['updated_at'];
    array_push($response["bantuan"], $ban);  
}
echo json_encode($response);

?>