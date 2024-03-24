<?php
// Middleware to check if user is authenticated
session_start();

// Check if user is not authenticated, redirect to login page
if (!isset($_SESSION["username"])) {
    http_response_code(401);
    echo json_encode(array("message" => "Unauthorized"));
    exit;
}
?>
