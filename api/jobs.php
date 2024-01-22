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
    $response['message'] = "user ID is Empty";
    echo json_encode($response);
    return;
}

$user_id = $db->escapeString($_POST['user_id']);




$sql_check = "SELECT * FROM user_jobs WHERE user_id = $user_id";
$db->sql($sql_check);
$res_check = $db->getResult();

if (empty($res_check)) {
    $response['success'] = false;
    $response['message'] = "User ID not found";
    echo json_encode($response);
    return false;
}

$sql = "SELECT jobs.id AS job_id, jobs.title, jobs.description, jobs.total_slots,jobs.slots_left, jobs.client_id, jobs.appli_fees, jobs.highest_income, jobs.status, jobs.ref_image,jobs.applied_status,jobs.upload_status,jobs.job_update, clients.*
        FROM jobs
        LEFT JOIN clients ON jobs.client_id = clients.id
        WHERE jobs.id NOT IN (SELECT jobs_id FROM user_jobs WHERE user_id = '$user_id')
        AND jobs.status = 1"; 
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);

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
    $response['message'] = "No jobs Found for the User";
    echo json_encode($response);
}
?>
