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
if (empty($_POST['pin'])) {
    $response['success'] = false;
    $response['message'] = "Pin is Empty";
    print_r(json_encode($response));
    return false;
}
$pin = $db->escapeString($_POST['pin']);


$sql = "SELECT * FROM managers WHERE pin = $pin AND status = 1";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num == 1){
    $response['success'] = true;
    $response['message'] = "Logged In Successfully";
    $response['data'] = $res;
    print_r(json_encode($response));
}
else{
    $response['success'] = false;
    $response['message'] = "Invalid Credentials";
    print_r(json_encode($response));

}
