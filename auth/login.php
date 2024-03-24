<?php
session_start();

require_once("../config/dbconfig.php");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve data from the request body
    $data = json_decode(file_get_contents("php://input"), true);

    // Check if username and password are provided
    if (isset($data["username"]) && isset($data["password"])) {
        $username = $data["username"];
        $password = $data["password"];

        // Connect to the database
        $database = new Database();
        $conn = $database->connect();

        // Prepare SQL statement to check username and password
        $query = "SELECT * FROM admin WHERE username = :username";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if username exists and password is correct
        if ($user && password_verify($password, $user['password'])) {
            // Authentication successful, set session variables
            $_SESSION["username"] = $username;

            // Return success response
            http_response_code(200);
            echo json_encode(array("message" => "Login successful"));
            exit;
        } else {
            // Authentication failed, return error response
            http_response_code(401);
            echo json_encode(array("message" => "Invalid username or password"));
            exit;
        }
    }
}

// Return error response if form not submitted properly
http_response_code(400);
echo json_encode(array("message" => "Incomplete data provided"));
?>
