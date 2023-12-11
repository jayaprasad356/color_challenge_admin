<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $queryId = isset($_POST['query_id']) ? $_POST['query_id'] : null;

    if ($queryId !== null) {
        $_SESSION['selected_query_id'] = $queryId;
        echo "Query ID saved to session successfully";
    } else {
        echo "Error: Missing query ID";
    }
} else {
    echo "Error: Invalid request method";
}

?>
