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

if (empty($_POST['user_id']) || empty($_POST['jobs_id'])) {
    $response['success'] = false;
    $response['message'] = "User ID or Jobs ID is Empty";
    echo json_encode($response);
    return;
}

$user_id = $db->escapeString($_POST['user_id']);
$jobs_id = $db->escapeString($_POST['jobs_id']);

$sql = "SELECT * FROM jobs WHERE id = '$jobs_id'";
$db->sql($sql);
$user = $db->getResult();

if (empty($user)) {
    $response['success'] = false;
    $response['message'] = "Job and User not found";
    echo json_encode($response);
    return;
}

$status = $user[0]['status'];

if ($status != 1) {
    $response['success'] = false;
    $response['message'] = "You are not a verified user";
    echo json_encode($response);
    return;
}
$sql = "SELECT * FROM users WHERE id = $user_id ";
$db->sql($sql);
$user = $db->getResult();

if (empty($user)) {
    $response['success'] = false;
    $response['message'] = "User not found";
    print_r(json_encode($response));
    return false;
}

$sql = "SELECT jobs.id AS job_id, jobs.title, jobs.description, jobs.total_slots, jobs.slots_left, jobs.client_id, jobs.appli_fees, jobs.highest_income, jobs.status, jobs.ref_image, jobs.applied_status,jobs.upload_status, clients.* 
        FROM jobs 
        LEFT JOIN clients ON jobs.client_id = clients.id 
        WHERE jobs.id = '$jobs_id'";

$db->sql($sql);

$res = $db->getResult();
$num = count($res);

if ($num >= 1) {
    foreach ($res as &$job) {
        $imagePath = $job['ref_image'];
        $imageURL = DOMAIN_URL . $imagePath;
        $job['ref_image'] = $imageURL;

        $clientImagePath = $job['profile'];
        $clientImageURL = DOMAIN_URL . $clientImagePath;
        $job['profile'] = $clientImageURL;
    }

    $response['success'] = true;
    $response['message'] = "Jobs Listed Successfully";
    $response['data'] = $res;
    echo json_encode($response);
} else {
    $response['success'] = false;
    $response['message'] = "No jobs found for the specified ID";
    echo json_encode($response);
}
?>
