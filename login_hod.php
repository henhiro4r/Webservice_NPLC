<?php 
include ('config.php');
$value = json_decode(file_get_contents('php://input'));
//get data user from login activity
$kode = $value->kode;
//compare data on website
$response["hod"] = array();

$cek_hod = $db->query("SELECT * FROM hod WHERE kode = '$kode' ");
$jum_hod = mysqli_num_rows($cek_hod);
$get_hod = mysqli_fetch_assoc($cek_hod);

if ($jum_hod == 1) {
    if($get_hod['is_login'] == '0'){
        $update = $db->query("UPDATE hod SET is_login = '1', last_login = $now, updated_at = $now WHERE kode = '$kode'");
        $hod = array();
        $hod["id"] = $get_hod['id'];
        $hod["kode"] = $get_hod['kode'];
        $hod["level"] = $get_hod['level'];
        $hod["nim"] = $get_hod['nim'];
        $hod["nama"] = $get_hod['nama'];
        $hod["pic"] = $get_hod['pic'];
        $hod["msg"] = "welcome";
        array_push($response["hod"], $hod);
        echo json_encode($response);
    }else{ 
        kirim("alreadylogin"); //already login
    }
}else{
    kirim("notfound"); //not exist
}

function kirim($message){
    $response["hod"] = array();
	$hod = array();
    $hod["id"] = "";
    $hod["kode"] = "";
    $hod["level"] = "";
    $hod["nim"] = "";
    $hod["nama"] = "";
    $hod["pic"] = "";
    $hod["msg"] = $message;
    array_push($response["hod"], $hod);
    echo json_encode($response);
}
?>