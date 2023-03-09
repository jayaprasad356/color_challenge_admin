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
// Get the current date and time
$date = new DateTime('now');

// Round up to the next hour
$date->setTime($date->format('H') + 1, 0, 0);

// Format the date and time as a string
$date_string = $date->format('Y-m-d H:i:s');

$diff = $date->getTimestamp() - time();
$diff_in_minutes = round($diff / 60);

$response['success'] = true;
$response['result_announce_time'] = $date_string;
$response['time_diff'] = $diff_in_minutes;
$response['message'] = "Result Time Successfully Taken";

print_r(json_encode($response));

