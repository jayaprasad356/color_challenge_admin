<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
date_default_timezone_set('Asia/Kolkata');
include_once('../includes/crud.php');

$db = new Database();
$db->connect();

if (empty($_POST['jobs_id'])) {
    $response['success'] = false;
    $response['message'] = "Jobs Id is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User Id is Empty";
    print_r(json_encode($response));
    return false;
}

$jobs_id = $db->escapeString($_POST['jobs_id']);
$user_id = $db->escapeString($_POST['user_id']);

$sql = "SELECT * FROM users WHERE id = $user_id ";
$db->sql($sql);
$user = $db->getResult();

if (empty($user)) {
    $response['success'] = false;
    $response['message'] = "User not found";
    print_r(json_encode($response));
    return false;
}
$balance = $user[0]['balance'];
$status = $user[0]['status'];
$plan = $user[0]['plan'];

if ($status != 1) {
    $response['success'] = false;
    $response['message'] = "Your Are Not Verified User";
    print_r(json_encode($response));
    return false;
}

if ($plan != 'A1U' || $plan != 'ALW') {
    $response['success'] = false;
    $response['message'] = "Join unlimited plan or welcome plan";
    echo json_encode($response);
    return false;
}

$sql_check = "SELECT * FROM jobs WHERE id = $jobs_id";
$db->sql($sql_check);
$res_check = $db->getResult();

if (empty($res_check)) {
    $response['success'] = false;
    $response['message'] = "Job ID not found";
    print_r(json_encode($response));
    return false;
}
$appli_fees = $res_check[0]['appli_fees'];
$slots_left = $res_check[0]['slots_left'];
$datetime = date('Y-m-d H:i:s');

$sql_check = "SELECT * FROM user_jobs WHERE user_id = $user_id AND jobs_id = $jobs_id";
$db->sql($sql_check);
$res_check_user = $db->getResult();

if (!empty($res_check_user)) {
    $response['success'] = false;
    $response['message'] = "You have already applied for this job";
    print_r(json_encode($response));
    return false;
}
if ($balance >= $appli_fees) {

    $sql = "UPDATE users SET balance = balance - $appli_fees  WHERE id = $user_id";
    $db->sql($sql);

    $sql = "UPDATE jobs SET slots_left = slots_left - 1  WHERE id = $jobs_id";
    $db->sql($sql);

    $sql = "INSERT INTO user_jobs (`user_id`, `jobs_id`) VALUES ('$user_id', '$jobs_id')";
    $db->sql($sql);

    $sql = "INSERT INTO transactions (`user_id`, `amount`, `datetime`, `type`) VALUES ('$user_id', '$appli_fees', '$datetime', 'appli_fees')";
    $db->sql($sql);

    $response['success'] = true;
    $response['message'] = "Apply Jobs successfully";
} else {    
    $response['success'] = false;
    $response['message'] = "Insufficient balance to apply for this job";
}

print_r(json_encode($response));
?>
