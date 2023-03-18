<?php
// Set response headers
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Include database functions
include_once('../includes/crud.php');

// Connect to database
$db = new Database();
$db->connect();
date_default_timezone_set('Asia/Kolkata');

$random_id = rand(1,1000);


$sql="SELECT * FROM dummy_users WHERE id=$random_id";
$db->sql($sql);
$res = $db->getResult();
$name = $res[0]['name'];
$color_id = $res[0]['color_id'];
$coins = $res[0]['coins'];
$datetime = date('Y-m-d H:i:s');


$sql = "INSERT INTO dummy_challengers (`name`,`color_id`,`coins`,`datetime`)  VALUES ('$name','$color_id','$coins','$datetime')";
$db->sql($sql);

$response = array();
$response['success'] = true;
$response['message'] = "dummy_challengers added successfully";


print_r(json_encode($response));
?>
