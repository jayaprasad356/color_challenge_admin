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
    $response['message'] = "User Id is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['post_id'])) {
    $response['success'] = false;
    $response['message'] = "Post Id is Empty";
    print_r(json_encode($response));
    return false;
}
if (!isset($_POST['like'])) {
    $response['success'] = false;
    $response['message'] = "Like is Empty";
    print_r(json_encode($response));
    return false;
}

$user_id = $db->escapeString($_POST['user_id']);
$post_id = $db->escapeString($_POST['post_id']);
$likeChange = (int)$_POST['like']; 

$sql = "SELECT `like` FROM likes WHERE user_id = $user_id AND post_id = $post_id";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);

if (!empty($res)) {
    $existingLike = $res[0]['like'];

    $newLike = $likeChange === 1 ? $existingLike + 1 : ($likeChange === -1 ? max($existingLike - 1, 0) : $existingLike);

    $Sql = "UPDATE likes SET `like` = $newLike WHERE user_id = $user_id AND post_id = $post_id";
    $db->sql($Sql);

    $Sql = "SELECT * FROM likes WHERE user_id = $user_id AND post_id = $post_id";
    $db->sql($Sql);
    $Sql = $db->getResult();

    $response['success'] = true;
    $response['message'] = "Like Updated Successfully";
    $response['data'] = $Sql; 
    print_r(json_encode($response));
} else {
    $response['success'] = false;
    $response['message'] = "User has not liked the post before";
    print_r(json_encode($response));
}
?>
