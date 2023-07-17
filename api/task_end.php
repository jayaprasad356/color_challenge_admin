<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include_once('../includes/crud.php');

$db = new Database();
$db->connect();



if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User ID is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['result'])) {
    $response['success'] = false;
    $response['message'] = "Result is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['time'])) {
    $response['success'] = false;
    $response['message'] = "Time is Empty";
    print_r(json_encode($response));
    return false;
}

$user_id = $db->escapeString($_POST['user_id']);
$task_result = $db->escapeString($_POST['result']);
$time = $db->escapeString($_POST['time']);

$sql = "SELECT * FROM `tasks` WHERE id=1";
$db->sql($sql);
$result = $db->getResult();
$prize=$result[0]['prize'];


if($task_result == 'won'){
    $sql = "UPDATE users SET balance= balance + $prize WHERE id=$user_id";
    $db->sql($sql);
}
$sql = "SELECT id FROM `users_task` WHERE user_id = $user_id ORDER BY id DESC LIMIT 1";
$db->sql($sql);
$result = $db->getResult();
$task_id=$result[0]['id'];


$sql = "UPDATE users_task SET result = '$task_result',time = $time WHERE id=$task_id";
$db->sql($sql);

$response['success'] = true;
$response['message'] = "Result Updated";
print_r(json_encode($response));



?>