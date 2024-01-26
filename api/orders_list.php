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

$sql = "SELECT product.id AS product_id, product.name, product.image, product.description, product.status, product.category_id, product.ads, product.original_price,  category.id AS category_id, category.name AS category_name, category.image AS category_image, category.status AS category_status,orders.delivery_date   FROM orders  
LEFT JOIN product ON orders.product_id = product.id LEFT JOIN category ON product.category_id = category.id  WHERE orders.user_id = '$user_id' AND product.id IS NOT NULL";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);

if ($num >= 1) {
    foreach ($res as &$product) {
        $imagePath = $product['image'];
        $imageURL = DOMAIN_URL . $imagePath;
        $product['image'] = $imageURL;
    
        $CategoryImagePath = $product['category_image'];
        $CategoryImageURL = DOMAIN_URL . $CategoryImagePath;
        $product['category_image'] = $CategoryImageURL;
    }
    $response['success'] = true;
    $response['message'] = "Orders Listed Successfully";
    $response['data'] = $res;
    print_r(json_encode($response));
} else {
    $response['success'] = false;
    $response['message'] = "No Orders Found for the User";
    print_r(json_encode($response));
}
?>