<?php 
include('config.php');
$value = json_decode(file_get_contents('php://input'));

$user_id = $_POST['user_id']; #$value->user_id; 9;
$quiz_id = $_POST['quiz_id']; #$value->quiz_id; 1;

$query = $db->query("SELECT point_now FROM users WHERE id = $user_id");
$quiz_check = $db->query("SELECT id,question,price FROM quizzes WHERE id = $quiz_id");
$dataQuiz = mysqli_fetch_assoc($quiz_check);
$getData = mysqli_fetch_assoc($query);
if ($quiz_check) {
	// echo $getData['point_now'];
	// echo $dataQuiz['price'];
	if ($getData['point_now'] >= $dataQuiz['price']) {
		$his = $db->query("SELECT * FROM quiz_plays WHERE quiz_id = $quiz_id AND user_id = $user_id");
		if ($his->num_rows != 0) {
			send('You already buy this quiz');
		} else {
			$ins = $db->query("INSERT INTO quiz_plays VALUES(null, $quiz_id, $user_id, 3, '0', '$time', '$time')");
			$update = $db->query("UPDATE users SET point_now = point_now-500, point_used = point_used+500 WHERE id = $user_id");
			if ($ins && $update) {
				$response['quiz_id'] = $quiz_id;
				$response["question"] = $dataQuiz['question'];
				// array_push($response['question']);
				$response["msg"] = "Success";
				// array_push($response['msg']);
				echo json_encode($response);
			}
		}
	} else {
		send('Not enough point!');
	}
} else {
	send('Quiz not found');
}


function send($message){
    $response["msg"] = $message;
	// $user = array();
	// $user["msg"] = $message;
	// array_push($response["msg"]);
	echo json_encode($response);
}
?>