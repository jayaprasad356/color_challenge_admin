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

$currentdate = date('Y-m-d');
$sql = "SELECT ut.id,u.name,ut.time FROM `users_task` ut,users u WHERE u.id = ut.user_id AND DATE(ut.datetime) = '$currentdate' ORDER BY ut.time";
$db->sql($sql);
$res= $db->getResult();
$num = $db->numRows($res);

if ($num >= 1){
    foreach ($res as $row) {
        $temp['rank'] = $row['rank'];
        $temp['name'] = $row['name'];
        $temp['time'] = $row['time'];
        $temp['prize'] = $row['id'];
        $rows[] = $temp;
    }
    $response['success'] = true;
    $response['message'] = "Contests Listed Successfully";
    $response['data'] = $rows;
    print_r(json_encode($response));
}
else{
    $response['success'] = false;
    $response['message'] = "Not found";
    print_r(json_encode($response));

}
