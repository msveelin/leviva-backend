<?php
// destination_api.php
require_once './destinations.php';

// Instantiate Destination object
$destination = new Destination();

// Check the request method
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Fetch all destinations
        $stmt = $destination->read();
        $num = $stmt->rowCount();
        
        // Check if any destinations found
        if ($num > 0) {
            $destinations_arr = array();
            $destinations_arr['data'] = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $destination_item = array(
                    'id' => $id,
                    'destinationUniqueId'=> $destinationUniqueId,
                    'name' => $name,
                    'description' => $description,
                    'location' => $location
                );
                array_push($destinations_arr['data'], $destination_item);
            }
            // Convert to JSON and output
            echo json_encode($destinations_arr);
        } else {
            // No destinations found
            echo json_encode(array('message' => 'No destinations found'));
        }
        break;
    case 'POST':
        // Retrieve data from the request body
        $data = json_decode(file_get_contents("php://input"), true);

        // Check if all required fields are present
        if (!isset($data['name']) || !isset($data['description']) || !isset($data['location'])) {
            // Return an error response if any required field is missing
            http_response_code(400);
            echo json_encode(array("message" => "Incomplete data provided"));
            break;
        }

        // Create the destination
        if ($destination->create($data['name'], $data['description'], $data['location'])) {
            // Destination created successfully
            http_response_code(201); // 201 Created
            echo json_encode(array("message" => "Destination created successfully"));
        } else {
            // Failed to create destination
            http_response_code(500); // 500 Internal Server Error
            echo json_encode(array("message" => "Unable to create destination"));
        }
        break;
        case 'PUT':
            // Retrieve data from the request body
            $data = json_decode(file_get_contents("php://input"), true);
        
            // Check if all required fields are present
            if (!isset($data['destinationUniqueId']) || !isset($data['name']) || !isset($data['description']) || !isset($data['location'])) {
                // Return an error response if any required field is missing
                http_response_code(400);
                echo json_encode(array("message" => "Incomplete data provided"));
                break;
            }
        
            // Update the destination
            if ($destination->update($data['destinationUniqueId'], $data['name'], $data['description'], $data['location'])) {
                // Destination updated successfully
                http_response_code(200); // 200 OK
                echo json_encode(array("message" => "Destination updated successfully"));
            } else {
                // Failed to update destination
                http_response_code(500); // 500 Internal Server Error
                echo json_encode(array("message" => "Unable to update destination"));
            }
            break;
        
        case 'DELETE':
            // Retrieve data from the request body
            $data = json_decode(file_get_contents("php://input"), true);
        
            // Delete the destination
            if ($destination->delete($data['destinationUniqueId'])) {
                // Destination deleted successfully
                http_response_code(200); // 200 OK
                echo json_encode(array("message" => "Destination deleted successfully"));
            } else {
                // Failed to delete destination
                http_response_code(500); // 500 Internal Server Error
                echo json_encode(array("message" => "Unable to delete destination"));
            }
            break;
        
    
    // Handle other request methods (POST, PUT, DELETE) similarly
    // You can add logic for handling POST, PUT, DELETE requests here
}

