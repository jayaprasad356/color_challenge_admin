<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['query_id'])) {
    $queryId = $_POST['query_id'];
    $_SESSION['current_query_id'] = $queryId;
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>
