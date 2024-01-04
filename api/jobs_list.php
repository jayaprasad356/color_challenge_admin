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
    $response['message'] = "Jobs ID is Empty";
    echo json_encode($response);
    return;
}

$jobs_id = $db->escapeString($_POST['jobs_id']);

$sql = "SELECT clients.* FROM jobs LEFT JOIN clients ON jobs.client_id = clients.id WHERE jobs.id = '$jobs_id'";
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
    $response['message'] = "No jobs Found for the specified ID";
    echo json_encode($response);
}
?>
