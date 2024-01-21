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
    echo json_encode($response);
    return;
}

$user_id = $db->escapeString($_POST['user_id']);

$sql = "SELECT jobs.id AS job_id, jobs.title, jobs.description, jobs.total_slots, jobs.slots_left, jobs.client_id, jobs.appli_fees, jobs.highest_income, jobs.status, jobs.ref_image, jobs.applied_status, clients.*
        FROM user_jobs
        LEFT JOIN jobs ON user_jobs.jobs_id = jobs.id
        LEFT JOIN clients ON jobs.client_id = clients.id
        WHERE user_jobs.user_id = '$user_id'";
$db->sql($sql);

$res = $db->getResult();
$num = $db->numRows($res);

    if ($num >= 1) {
        foreach ($res as &$job) {
            $imagePath = $job['ref_image'];
            $imageURL = DOMAIN_URL . $imagePath;
            $job['ref_image'] = $imageURL;
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
