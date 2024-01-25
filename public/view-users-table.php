<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;

$db = new Database();
$db->connect();

// Check if whatsapp id is provided in the URL parameters
if (isset($_GET['id'])) {
    $whatsappID = $db->escapeString($_GET['id']);

    // Fetch user_id from the whatsapp table based on the provided whatsapp ID
    $sql_query = "SELECT user_id FROM whatsapp WHERE id = $whatsappID";
    $db->sql($sql_query);
    $res = $db->getResult();

    if (empty($res)) {
        echo "User not found.";
        return false;
        exit(0);
    }

    $user_id = $res[0]['user_id'];
   

    // Fetch all images for the given user_id
    $sql_query_images = "SELECT id, image FROM whatsapp WHERE user_id = $user_id";
    $db->sql($sql_query_images);
    $res_images = $db->getResult();
    
    // Check if images are found
    if (empty($res_images)) {
        echo "No images found for the user.";
        return false;
        exit(0);
    }
    
    // Reverse the array to display images in reverse order
    $res_images = array_reverse($res_images);

    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>View User Images</title>
    </head>
    <body>
        <section class='content-header'>
            <h1>View User Images / <small><a href='home.php'><i class='fa fa-home'></i> Home</a></small></h1>
        </section>
    
        <section class='content'>
            <div class='row'>
                <div class='col-xs-12'>"; // Single column for all images
    
    foreach ($res_images as $row) {
        $imagePath = 'https://a1ads.site/' . $row['image'];
        $imageID = $row['id'];
    
        // Display each image with ID in the same box, one by one
        echo "<div class='box'>
                <div class='box-header'>
                    <!-- Add any header content you need -->
                </div>
                <div class='box-body'>
                <p>ID: $imageID</p>
                    <img src='$imagePath' alt='User Image' width='150' height='150'>
    
                </div>";
    }
    
    // Closing tags for the HTML
    echo "</div>
    </div>
    </section>
    </body>
    </html>";
    } else {
        // If whatsapp id is not provided, handle the case accordingly.
        echo "WhatsApp ID not provided.";
        return false;
        exit(0);
    }
    ?>
    