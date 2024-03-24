<?php
// destination.php
require_once '../config/dbconfig.php';

class Destination {
    private $conn;
    private $table = 'destinations';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // Add methods for CRUD operations
    // Create Destination
    public function create($name, $description, $location) {
        $query = 'INSERT INTO ' . $this->table . ' (destinationUniqueId, name, description, location) VALUES (UUID(), :name, :description, :location)';
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':location', $location);

        if ($stmt->execute()) {
            return true;
        }

        // Log or handle the error appropriately
        printf("Error: %s.\n", $stmt->error);

        return false;
    }


    // Read Destination
    public function read() {
        $query = 'SELECT * FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Update Destination
    public function update($destinationUniqueId, $name, $description, $location) {
        $query = 'UPDATE ' . $this->table . '
                  SET name = :name, description = :description, location = :location
                  WHERE destinationUniqueId = :destinationUniqueId';
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':destinationUniqueId', $destinationUniqueId);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':location', $location);

        if ($stmt->execute()) {
            return true;
        }

        printf("Error: %s.\n", $stmt->error);

        return false;
    }

    // Delete Destination
    public function delete($destinationUniqueId) {
        $query = 'DELETE FROM ' . $this->table . ' WHERE destinationUniqueId = :destinationUniqueId';
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':destinationUniqueId', $destinationUniqueId);

        if ($stmt->execute()) {
            return true;
        }

        printf("Error: %s.\n", $stmt->error);

        return false;
    }
}

