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

$sql = "SELECT * FROM orders WHERE user_id = '$user_id'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);

if ($num >= 1) {
    $productData = array();

    foreach ($res as $row) {
        $product_id = $row['product_id'];

        $sql = "SELECT * FROM product WHERE id = '$product_id'";
        $db->sql($sql);
        $products = $db->getResult();

        $productData[] = array(
            'order_id' => $row['id'],
            'products' => $products,
        );
    }

    $response['success'] = true;
    $response['message'] = "Orders Listed Successfully";
    $response['data'] = $productData;
    print_r(json_encode($response));
} else {
    $response['success'] = false;
    $response['message'] = "No Orders Found for the User";
    print_r(json_encode($response));
}
?>
