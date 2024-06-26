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

include_once('../includes/functions.php');
$fn = new functions;
$currentdate = date('Y-m-d');

$response['success'] = false;
$response['message'] = 'disabled';
print_r(json_encode($response));
return false;

// $response['success'] = false;
// $response['message'] = "You are Account is not Approved";
// print_r(json_encode($response));
// return false;
$sql = "UPDATE settings SET watch_ad_status = 1 ";
$db->sql($sql);

$sql = "SELECT * FROM leaves WHERE date = '$currentdate'";
$db->sql($sql);
$resl = $db->getResult();
$lnum = $db->numRows($resl);
$enable = 1;
if ($lnum >= 1) {
    $sql = "UPDATE settings SET watch_ad_status = 0 ";
    $db->sql($sql);

    return false;

}

$sql = "UPDATE users SET last_today_ads = 0,last_missed_days = 0";
$db->sql($sql);

$sql = "UPDATE users SET last_today_ads = today_ads,last_missed_days = missed_days";
$db->sql($sql);

$sql = "UPDATE users SET today_ads = 0";
$db->sql($sql);

$sql = "UPDATE users
SET worked_days = DATEDIFF('$currentdate', joined_date) - (
    SELECT COUNT(*) 
    FROM leaves
    WHERE date >= users.joined_date  AND date <= '$currentdate'
)
WHERE status = 1";
$db->sql($sql);


// $sql = "UPDATE user_plan SET claim = 1 ";
// $db->sql($sql);

$sql = "UPDATE users SET today_income = 0 ";
$db->sql($sql);


?>