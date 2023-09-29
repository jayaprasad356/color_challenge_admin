<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");

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
if (empty($_POST['like'])) {
    $response['success'] = false;
    $response['message'] = "Like is Empty";
    print_r(json_encode($response));
    return false;
}
$user_id = $db->escapeString($_POST['user_id']);
$post_id = $db->escapeString($_POST['post_id']);
$like = (int)$_POST['like']; 


$sql = "SELECT * FROM likes WHERE user_id = $user_id AND post_id = $post_id";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);

if ($num >= 1) {
 
    $existingLike = $res[0]['like'];
    $newLike = $existingLike + $like;

    $sql = "UPDATE likes SET `like` = $newLike WHERE user_id = $user_id AND post_id = $post_id";
    $db->sql($sql);

    $sql = "SELECT * FROM likes WHERE user_id = $user_id AND post_id = $post_id";
    $db->sql($sql);
    $res = $db->getResult();

    $response['success'] = true;
    $response['message'] = "Like Updated Successfully";
    $response['data'] = $res;
    echo json_encode($response);
} else {

    $sql = "INSERT INTO likes (user_id, post_id, `like`) VALUES ($user_id, $post_id, $like)";
    $db->sql($sql);

    $sql = "SELECT * FROM likes WHERE user_id = $user_id AND post_id = $post_id";
    $db->sql($sql);
    $res = $db->getResult();

    $response['success'] = true;
    $response['message'] = "Like Added Successfully";
    $response['data'] = $res;
    echo json_encode($response);
}
?>
