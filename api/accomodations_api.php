<?php
require_once "./accommodations.php";

$accommodations = new Accommodations();

// Check the request method
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Fetch all accommodations
        $stmt = $accommodations->read();
        $num = $stmt->rowCount();
        
        // Check if any accommodations found
        if ($num > 0) {
            $accommodations_arr = array();
            $accommodations_arr['data'] = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $accommodation_item = array(
                    'accomodationUniqueId' => $accomodationUniqueId,
                    'name' => $name,
                    'description' => $description,
                    'location' => $location,
                    'price_per_night' => $price_per_night
                );
                array_push($accommodations_arr['data'], $accommodation_item);
            }
            // Convert to JSON and output
            echo json_encode($accommodations_arr);
        } else {
            // No accommodations found
            echo json_encode(array('message' => 'No accommodations found'));
        }
        break;
    case 'POST':
        // Retrieve data from the request body
        $data = json_decode(file_get_contents("php://input"), true);

        // Check if all required fields are present
        if (!isset($data['name']) || !isset($data['description']) || !isset($data['location']) || !isset($data['price_per_night'])) {
            // Return an error response if any required field is missing
            http_response_code(400);
            echo json_encode(array("message" => "Incomplete data provided"));
            break;
        }

        // Create the accommodation
        if ($accommodations->create($data['name'], $data['description'], $data['location'], $data['price_per_night'])) {
            // Accommodation created successfully
            http_response_code(201); // 201 Created
            echo json_encode(array("message" => "Accommodation created successfully"));
        } else {
            // Failed to create accommodation
            http_response_code(500); // 500 Internal Server Error
            echo json_encode(array("message" => "Unable to create accommodation"));
        }
        break;
    case 'PUT':
        // Retrieve data from the request body
        $data = json_decode(file_get_contents("php://input"), true);

        // Check if all required fields are present
        if (!isset($data['accomodationUniqueId']) || !isset($data['name']) || !isset($data['description']) || !isset($data['location']) || !isset($data['price_per_night'])) {
            // Return an error response if any required field is missing
            http_response_code(400);
            echo json_encode(array("message" => "Incomplete data provided"));
            break;
        }

        // Update the accommodation
        if ($accommodations->update($data['accomodationUniqueId'], $data['name'], $data['description'], $data['location'], $data['price_per_night'])) {
            // Accommodation updated successfully
            http_response_code(200); // 200 OK
            echo json_encode(array("message" => "Accommodation updated successfully"));
        } else {
            // Failed to update accommodation
            http_response_code(500); // 500 Internal Server Error
            echo json_encode(array("message" => "Unable to update accommodation"));
        }
        break;
    case 'DELETE':
         // Retrieve data from the request body
         $data = json_decode(file_get_contents("php://input"), true);

        // Delete the accommodation
        if ($accommodations->delete($data['accomodationUniqueId'])) {
            // Accommodation deleted successfully
            http_response_code(200); // 200 OK
            echo json_encode(array("message" => "Accommodation deleted successfully"));
        } else {
            // Failed to delete accommodation
            http_response_code(500); // 500 Internal Server Error
            echo json_encode(array("message" => "Unable to delete accommodation"));
        }
        break;
    // Handle other request methods (PUT, DELETE) similarly
}
