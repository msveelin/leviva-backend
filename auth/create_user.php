<?php
// Include database configuration
require_once '../config/dbconfig.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve data from the request body
    $data = json_decode(file_get_contents("php://input"), true);

    // Check if all required fields are provided
    if (isset($data ["username"]) && isset($data ["password"]) && isset($data ["email"])) {
        $username = $data ["username"];
        $password = $data ["password"];
        $email = $data ["email"];

        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Connect to the database
        $database = new Database();
        $conn = $database->connect();

        // Prepare SQL statement to insert user into database
        $query = "INSERT INTO admin (username, password, email) VALUES (:username, :password, :email)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":password", $hashed_password);
        $stmt->bindParam(":email", $email);

        // Execute the SQL statement
        if ($stmt->execute()) {
            // User created successfully
            http_response_code(201);
            echo json_encode(array("message" => "User created successfully"));
            exit;
        } else {
            // Failed to create user
            http_response_code(500);
            echo json_encode(array("message" => "Unable to create user"));
            exit;
        }
    }
}

// Return error response if form not submitted properly
http_response_code(400);
echo json_encode(array("message" => "Incomplete data provided"));
?>
