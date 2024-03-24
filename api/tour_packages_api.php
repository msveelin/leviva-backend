<?php
// tour_packages_api.php
require_once './tour_packages.php';

// Instantiate TourPackage object
$tourPackage = new TourPackage();

// Check the request method
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Fetch all tour packages
        $stmt = $tourPackage->read();
        $num = $stmt->rowCount();
        
        // Check if any tour packages found
        if ($num > 0) {
            $tourPackages_arr = array();
            $tourPackages_arr['data'] = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $tourPackage_item = array(
                    'tourPackageUniqueId' => $tourPackageUniqueId,
                    'name' => $name,
                    'description' => $description,
                    'destinationUniqueId' => $destinationUniqueId,
                    'price' => $price,
                    'duration' => $duration,
                    'included_services' => $included_services,
                    'itinerary' => $itinerary,
                    'prices' => $prices,
                    'price_inclusion' => $price_inclusion,
                    'price_exclusion' => $price_exclusion
                );
                array_push($tourPackages_arr['data'], $tourPackage_item);
            }
            // Convert to JSON and output
            echo json_encode($tourPackages_arr);
        } else {
            // No tour packages found
            echo json_encode(array('message' => 'No tour packages found'));
        }
        break;
    case 'POST':
        // Retrieve data from the request body
        $data = json_decode(file_get_contents("php://input"), true);

        // Check if all required fields are present
        if (!isset($data['name']) || !isset($data['description']) || !isset($data['destinationUniqueId']) || !isset($data['price']) || !isset($data['duration']) || !isset($data['included_services']) || !isset($data['itinerary']) || !isset($data['prices']) || !isset($data['price_inclusion']) || !isset($data['price_exclusion'])) {
            // Return an error response if any required field is missing
            http_response_code(400);
            echo json_encode(array("message" => "Incomplete data provided"));
            break;
        }

        // Create the tour package
        if ($tourPackage->create($data['name'], $data['description'], $data['destinationUniqueId'], $data['price'], $data['duration'], $data['included_services'], $data['itinerary'], $data['prices'], $data['price_inclusion'], $data['price_exclusion'])) {
            // Tour package created successfully
            http_response_code(201); // 201 Created
            echo json_encode(array("message" => "Tour package created successfully"));
        } else {
            // Failed to create tour package
            http_response_code(500); // 500 Internal Server Error
            echo json_encode(array("message" => "Unable to create tour package"));
        }
        break;
    case 'PUT':
        // Retrieve data from the request body
        $data = json_decode(file_get_contents("php://input"), true);

        // Check if all required fields are present
        if (!isset($data['name']) || !isset($data['description']) || !isset($data['destinationUniqueId']) || !isset($data['price']) || !isset($data['duration']) || !isset($data['included_services']) || !isset($data['itinerary']) || !isset($data['prices']) || !isset($data['price_inclusion']) || !isset($data['price_exclusion'])) {
            // Return an error response if any required field is missing
            http_response_code(400);
            echo json_encode(array("message" => "Incomplete data provided"));
            break;
        }

        // update tour package
        if ($tourPackage->update($data['tourPackageUniqueId'], $data['name'], $data['description'], $data['destinationUniqueId'], $data['price'], $data['duration'], $data['included_services'], $data['itinerary'], $data['prices'], $data['price_inclusion'], $data['price_exclusion'])) {
            // Tour package created successfully
            http_response_code(201); // 201 Created
            echo json_encode(array("message" => "Tour package updated successfully"));
        } else {
            // Failed to create tour package
            http_response_code(500); // 500 Internal Server Error
            echo json_encode(array("message" => "Unable to update tour package"));
        }
        break;
    case 'DELETE':
        // retrieve tour packageUniqueId
        // $tourPackageUniqueId = isset($_GET['tourPackageUniqueId']) ? $_GET['tourPackageUniqueId'] : die();

        // Retrieve data from the request body
        $data = json_decode(file_get_contents("php://input"), true);

        if($tourPackage->delete($data['tourPackageUniqueId'])) {
            // Destination deleted successfully
            http_response_code(200); // 200 OK
            echo json_encode(array("message" => "Tour Package with deleted successfully"));
        } else {
            // Failed to delete destination
            http_response_code(500); // 500 Internal Server Error
            echo json_encode(array("message" => "Unable to delete Tour Package"));
        }
        break;
    }

        
    