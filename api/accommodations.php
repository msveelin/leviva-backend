<?php
require_once '../config/dbconfig.php';

class Accommodations {
    private $conn;
    private $table = 'accommodations';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // Create Accommodation// Create Accommodation
    public function create($name, $description, $location, $price_per_night) {
        $query = 'INSERT INTO ' . $this->table . ' (accomodationUniqueId, name, description, location, price_per_night) VALUES ( UUID(), :name, :description, :location, :price_per_night)';
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':price_per_night', $price_per_night);

        if ($stmt->execute()) {
            return true;
        } else {
            // Log the error to a file for debugging
            error_log("Error creating accommodation: " . $stmt->error);
            return false;
        }
    }

    // Read Accommodations
    public function read() {
        $query = 'SELECT * FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Update Accommodation
    public function update($accomodationUniqueId, $name, $description, $location, $price_per_night) {
        $query = 'UPDATE ' . $this->table . '
                  SET name = :name, description = :description, location = :location, price_per_night = :price_per_night
                  WHERE accomodationUniqueId = :accomodationUniqueId';
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':accomodationUniqueId', $accomodationUniqueId);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':price_per_night', $price_per_night);

        if ($stmt->execute()) {
            return true;
        }

        printf("Error: %s.\n", $stmt->error);

        return false;
    }

    // Delete Accommodation
    public function delete($accomodationUniqueId) {
        $query = 'DELETE FROM ' . $this->table . ' WHERE accomodationUniqueId = :accomodationUniqueId';
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':accomodationUniqueId', $accomodationUniqueId);

        if ($stmt->execute()) {
            return true;
        }

        printf("Error: %s.\n", $stmt->error);

        return false;
    }
}

