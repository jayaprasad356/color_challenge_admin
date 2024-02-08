<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
date_default_timezone_set('Asia/Kolkata');
include_once('../includes/crud.php');

$db = new Database();
$db->connect();

if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User Id is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['product_id'])) {
    $response['success'] = false;
    $response['message'] = "Product Id is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['address'])) {
    $response['success'] = false;
    $response['message'] = "Address is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['pincode'])) {
    $response['success'] = false;
    $response['message'] = "Pincode is Empty";
    print_r(json_encode($response));
    return false;
}

$user_id = $db->escapeString($_POST['user_id']);
$product_id = $db->escapeString($_POST['product_id']);
$address = $db->escapeString($_POST['address']);
$pincode = $db->escapeString($_POST['pincode']);

$sql = "SELECT * FROM users WHERE id = $user_id ";
$db->sql($sql);
$user = $db->getResult();

if (empty($user)) {
    $response['success'] = false;
    $response['message'] = "User not found";
    print_r(json_encode($response));
    return false;
}

$balance = $user[0]['balance'];

$sql = "SELECT * FROM product WHERE id = $product_id ";
$db->sql($sql);
$product = $db->getResult();

if (empty($product)) {
    $response['success'] = false;
    $response['message'] = "Product not found";
    print_r(json_encode($response));
    return false;
}

$ads = $product[0]['ads'];
$datetime = date('Y-m-d H:i:s');

$response['success'] = false;
$response['message'] = "Order Not Placed";
print_r(json_encode($response));
return false;
if ($balance >= $ads) {
    $sql = "UPDATE users SET balance = balance - $ads, address = '$address', pincode = '$pincode' WHERE id = $user_id";
    $db->sql($sql);

    $sql = "INSERT INTO orders (`user_id`, `product_id`) VALUES ('$user_id', '$product_id')";
    $db->sql($sql);

    $sql = "INSERT INTO transactions (`user_id`, `ads`, `datetime`, `type`) VALUES ('$user_id', '$ads', '$datetime', 'order')";
    $db->sql($sql);

    $response['success'] = true;
    $response['message'] = "Orders Placed successfully";
} else {    
    $response['success'] = false;
    $response['message'] = "Insufficient balance to place the order";
}

print_r(json_encode($response));
?>
