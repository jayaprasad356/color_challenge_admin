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
    $response['message'] = "user ID is Empty";
    echo json_encode($response);
    return;
}

$user_id = $db->escapeString($_POST['user_id']);

$sql_check = "SELECT * FROM user_jobs WHERE user_id = $user_id";
$db->sql($sql_check);
$res_check = $db->getResult();

if (empty($res_check)) {
    $response['success'] = false;
    $response['message'] = "User ID not found";
    print_r(json_encode($response));
    return false;
}

$sql = "SELECT jobs.*, clients.* 
        FROM jobs 
        LEFT JOIN clients ON jobs.client_id = clients.id
        WHERE jobs.id NOT IN (SELECT jobs_id FROM user_jobs WHERE user_id = '$user_id')";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);

if ($num >= 1) {
    $response['success'] = true;
    $response['message'] = "Jobs Listed Successfully";
    $response['data'] = $res;
    echo json_encode($response);
} else {
    $response['success'] = false;
    $response['message'] = "No jobs Found for the User";
    echo json_encode($response);
}
?>
