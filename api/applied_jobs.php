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

$sql_check = "SELECT * FROM upload_jobs WHERE id = $jobs_id";
$db->sql($sql_check);
$res_check = $db->getResult();

if (empty($res_check)) {
    $response['success'] = false;
    $response['message'] = "Job ID not found";
    print_r(json_encode($response));
    return false;
}

$sql = "SELECT * FROM users WHERE id = $user_id ";
$db->sql($sql);
$user = $db->getResult();

if (empty($user)) {
    $response['success'] = false;
    $response['message'] = "User not found";
    print_r(json_encode($response));
    return false;
}


$sql_insert = "INSERT INTO applied_jobs (jobs_id,user_id) VALUES ('$jobs_id','$user_id')";
$db->sql($sql_insert);

$sql_select = "SELECT * FROM upload_jobs WHERE id = $jobs_id";
$db->sql($sql_select);
$res_select = $db->getResult();

$response['success'] = true;
$response['message'] = "Applied Jobs Successfully";
print_r(json_encode($response));
?>
