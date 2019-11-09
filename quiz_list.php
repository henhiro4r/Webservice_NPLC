<?php 
include('config.php');
$value = json_decode(file_get_contents('php://input'));

$user_id = 3;#$value->user_id; 9;  $_POST['user_id'];
$query = $db->query("SELECT id,name FROM users WHERE id = $user_id");
$quiz_check = $db->query("SELECT * FROM quizzes");
$response["quiz"] = array();

if ($query->num_rows == 1) {
	while ($quiz = mysqli_fetch_assoc($quiz_check)) {
		$id = $quiz['id'];
		$his = $db->query("SELECT * FROM quiz_plays WHERE quiz_id = $id AND user_id = $user_id");
		$a = mysqli_fetch_assoc($his);
		if ($his->num_rows == 1 && $a["is_right"] == 0 && $a["try"] != 0) {
			$status = 1; //buyed not answered
		} else if ($his->num_rows == 1 && $a["is_right"] == 0 && $a["try"] == 0) {
			$status = 2; //buyed wrong answer 3 times
		} else if ($his->num_rows == 1 && $a["is_right"] == 1 && $a["try"] >= 0 ){
			$status = 3; //buyed correct answer
		} else {
			$status = 0; //not buyed
		}
		$qui = array();
		$qui['id'] = $quiz['id'];
		$qui['title'] = $quiz['title'];
		$qui['question'] = $quiz['question'];
		$qui['price'] = $quiz['price'];
		$qui['status'] = $status;
		array_push($response['quiz'], $qui);
	}
	$response['message'] = "Found";
	echo json_encode($response);
} else {
	send('Quiz not found');
}

function send($message){
    $response["message"] = $message;
	echo json_encode($response);
}

?>