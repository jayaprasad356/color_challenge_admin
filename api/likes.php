<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");

include_once('../includes/crud.php');

$db = new Database();
$db->connect();

if (empty($_POST['user_id']) || empty($_POST['post_id']) || !isset($_POST['like'])) {
    $response['success'] = false;
    $response['message'] = "Invalid data provided";
    echo json_encode($response);
    return;
}

$user_id = $db->escapeString($_POST['user_id']);
$post_id = $db->escapeString($_POST['post_id']);
$like = (int)$_POST['like']; 

// Check if the user has already liked the post
$sql = "SELECT * FROM likes WHERE user_id = $user_id AND post_id = $post_id";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);

if ($num >= 1) {
    $response['success'] = false;
    $response['message'] = "User has already liked the post";
    echo json_encode($response);
} else {
    // User has not liked the post before, insert a new like record
    $insertSql = "INSERT INTO likes (user_id, post_id, `like`) VALUES ($user_id, $post_id, $like)";
    $db->sql($insertSql);

    // Fetch the newly inserted data
    $selectSql = "SELECT * FROM likes WHERE user_id = $user_id AND post_id = $post_id";
    $db->sql($selectSql);
    $insertedRes = $db->getResult();

    $response['success'] = true;
    $response['message'] = "Like Added Successfully";
    $response['data'] = $insertedRes; 
    echo json_encode($response);
}
?>
