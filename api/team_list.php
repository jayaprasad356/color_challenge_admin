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
    $response['message'] = "User Id is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['level'])) {
    $response['success'] = false;
    $response['message'] = "Level is Empty";
    print_r(json_encode($response));
    return false;
}

$user_id = $db->escapeString($_POST['user_id']);
$level = $db->escapeString($_POST['level']);


$sql = "SELECT *,DATE(registered_date) AS registered_date,CONCAT(SUBSTRING(mobile, 1, 2), '******', SUBSTRING(mobile, LENGTH(mobile)-1, 2)) AS mobile FROM users ORDER BY id DESC LIMIT 5 ";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);

if ($num >= 1) {
    $response['success'] = true;
    $response['message'] = "Users Listed Successfully";
    $response['count'] = $num;
    $response['data'] = $res;
    print_r(json_encode($response));
} else {
    $response['success'] = false;
    $response['message'] = "No Users found with the specified refer_code";
    print_r(json_encode($response));
}
?>
