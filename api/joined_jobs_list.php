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

$sql = "SELECT * FROM jobs WHERE id = $jobs_id ";
$db->sql($sql);
$user = $db->getResult();

if (empty($user)) {
    $response['success'] = false;
    $response['message'] = "Jobs not found";
    print_r(json_encode($response));
    return false;
}
$status = $user[0]['status'];


if ($status != 1) {
    $response['success'] = false;
    $response['message'] = "Your Are Not Verified User";
    print_r(json_encode($response));
    return false;
}

$sql = "SELECT jobs.id AS job_id, jobs.title, jobs.description,jobs.total_slots,jobs.client_id,jobs.appli_fees,jobs.highest_income,jobs.status,jobs.ref_image, clients.*  FROM user_jobs  LEFT JOIN jobs ON user_jobs.jobs_id = jobs.id LEFT JOIN clients ON jobs.client_id = clients.id  WHERE user_jobs.jobs_id = '$jobs_id'";
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
