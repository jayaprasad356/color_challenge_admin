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

if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User Id is Empty";
    print_r(json_encode($response));
    return false;
}
$user_id = $db->escapeString($_POST['user_id']);

$sql = "SELECT *,challenges.status AS status FROM challenges,colors WHERE challenges.color_id = colors.id AND challenges.user_id = $user_id ORDER BY challenges.id DESC";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num >= 1){
    foreach($res as $row){
        $temp['coins'] = $row['coins'];
        $temp['name'] = $row['name'];
        $temp['code']= $row['code'];
        $temp['status'] = $row['status'];
        $date = date('j M, Y (g:i A)', strtotime($row['datetime']));
        $currentDate = date('j M, Y');
        $yesterdayDate = date('j M, Y', strtotime('yesterday'));
        
        if ($date == $currentDate) {
            $temp['datetime'] = 'Today';
        } elseif ($date == $yesterdayDate) {
            $temp['datetime'] = 'Yesterday';
        } else {
            $temp['datetime'] = $date;
        }
        
        $rows[] = $temp;
    }
    $response['success'] = true;
    $response['message'] = "My Challenges Listed Successfully";
    $response['data'] = $rows;
    print_r(json_encode($response));
}
else{
    $response['success'] = false;
    $response['message'] = "You have not created any challenge yet";
    print_r(json_encode($response));

}
