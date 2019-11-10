<?php 
include('config.php');
$value = json_decode(file_get_contents('php://input'));

$user_id = $_POST['user_id']; #$value->user_id;
$answer = $_POST['answer']; #$value->answer;
$quiz_id = $_POST['quiz_id']; #$value->quiz_id;

$quiz_check = $db->query("SELECT * FROM quizzes WHERE id = $quiz_id");
$his = $db->query("SELECT * FROM quiz_plays WHERE quiz_id = $quiz_id AND user_id = $user_id");
$dataQuiz = mysqli_fetch_assoc($quiz_check);
$his_data = mysqli_fetch_assoc($his);

if ($quiz_check->num_rows == 1) {
	if ($his_data['is_right'] != 1) {
		if ($answer == $dataQuiz['answer']) {
			$update = $db->query("UPDATE quiz_plays SET try = try-1, is_right = '1', updated_at='$time'");
			if ($update) {
				send('Congratulation'); //Congratulation, you can proceed to next level!
			} else {
				send('Something wrong, please try again!');
			}
		} else {
			if ($his_data['try'] == 0) {
				send('3 attempt used, good luck next time!');
			} else {
				$update = $db->query("UPDATE quiz_plays SET try = try-1, updated_at='$time'");
				if ($update && $his_data['try'] > 1) {
					$response["chance"] = $his_data['try'] - 1;
					$response["message"] = "Oops, Wrong answer!";
					echo json_encode($response);
				} elseif ($update && $his_data['try'] == 1) {
					send('Used all chance');
				} else {
					send('Something wrong, please try again!');
				}
			}
		}
	} else {
		send('Already answered');
	}
} else {
	send('Please buy quiz first!');
}

function send($message){
	$response["message"] = $message;
	echo json_encode($response);
}
?>