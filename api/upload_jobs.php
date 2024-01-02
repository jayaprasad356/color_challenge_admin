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

$sql = "SELECT * FROM applied_jobs WHERE user_id = '$user_id'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);

if ($num >= 1) {
    $jobsData = array();

    foreach ($res as $row) {
        $jobs_id = $row['jobs_id'];

        $sqljobs = "SELECT * FROM upload_jobs WHERE id = '$jobs_id'";
        $db->sql($sqljobs);
        $jobs = $db->getResult();

        foreach ($jobs as $jobsRow) {
            $jobsData[] = array(
                'id' => $jobsRow['id'],
                'user_name' => $jobsRow['user_name'],
                'company_name' => $jobsRow['company_name'],
                'title' => $jobsRow['title'],
                'description' => $jobsRow['description'],
                'deadline' => $jobsRow['deadline'],
                'price' => $jobsRow['price'],
            );
        }
    }

    $response['success'] = true;
    $response['message'] = "Upload Jobs Listed Successfully";
    $response['data'] = $jobsData;
    print_r(json_encode($response));
} else {
    $response['success'] = false;
    $response['message'] = "No jobs Found for the User";
    print_r(json_encode($response));
}
?>
