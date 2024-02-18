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
include_once('../includes/custom-functions.php');
include_once('../includes/functions.php');
$fn = new functions;

$response = array();

if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User Id is Empty";
    echo json_encode($response);
    return;
}
if (empty($_POST['no_of_views'])) {
    $response['success'] = false;
    $response['message'] = "No Of Views is Empty";
    echo json_encode($response);
    return;
}

$user_id = $db->escapeString($_POST['user_id']);
$no_of_views = $db->escapeString($_POST['no_of_views']);

$sql_check = "SELECT * FROM whatsapp WHERE `user_id` = '$user_id'";
$db->sql($sql_check);
$res_check = $db->getResult();

if (empty($res_check)) {
    $response['success'] = false;
    $response['message'] = "whatsapp not found for the given user_id";
    echo json_encode($response);
} else {
    $today = date("Y-m-d");
    $existing_image_date = date("Y-m-d", strtotime($res_check[0]['datetime']));
    if ($today == $existing_image_date) {
        $response['success'] = false;
        $response['message'] = "An image has already been uploaded today for the given User Id";
        echo json_encode($response);
    } else {
        if (isset($_FILES['image']) && !empty($_FILES['image']) && $_FILES['image']['error'] == 0 && $_FILES['image']['size'] > 0) {
            $uploadDirectory = '../upload/images/';
            if (!is_dir($uploadDirectory)) {
                mkdir($uploadDirectory, 0777, true);
            }
            
            $filename = microtime(true) . '.' . pathinfo($_FILES["image"]["name"])['extension'];
            $full_path = $uploadDirectory . $filename;
            
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $full_path)) {
                $response["success"]   = false;
                $response["message"] = "Failed to upload image!";
                echo json_encode($response);
                return;
            }

            $upload_image= 'upload/images/' . $filename;
            $sql = "UPDATE whatsapp SET `image` = '$upload_image', `no_of_views` = '$no_of_views', `datetime` = NOW() WHERE `user_id` = '$user_id'";
            $db->sql($sql);

            $response['success'] = true;
            $response['message'] = "Whatsapp Update Successfully";
            echo json_encode($response);
        } else {
            $response['success'] = false;
            $response['message'] = "Image is not provided or is invalid";
            echo json_encode($response);
        }
    }
}
?>