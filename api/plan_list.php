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


$sql = "SELECT * FROM plan";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);

if ($num >= 1){
    foreach ($res as $row) {
        $temp['id'] = $row['id'];
        $temp['products'] = $row['products'];
        $temp['price'] = $row['price'];
        $temp['total_income'] = $row['total_income'];
        $temp['image'] = DOMAIN_URL . $row['image'];
        $temp['daily_income'] = $row['daily_income'];
        $temp['validity'] = $row['validity'];
        $temp['invite_bonus'] = $row['invite_bonus'];
        $temp['level_income'] = $row['level_income'];
        $offerStartTime = new DateTime($row['offer_start_time']);
        $offerEndTime = new DateTime($row['offer_end_time']);
        $diff = $offerStartTime->diff($offerEndTime);
        $hours = $diff->h;
        $minutes = $diff->i;
        $hours = $hours + ($diff->days*24);
        $temp['current_offer_time'] = $hours . ' hrs ' . $minutes . ' min';


        $rows[] = $temp;
    }
    $response['success'] = true;
    $response['message'] = "Plan Details Listed Successfully";
    $response['data'] = $rows;
    print_r(json_encode($response));
}
else{
    $response['success'] = false;
    $response['message'] = "Plan Not found";
    print_r(json_encode($response));
}


