<?php
require_once '../config/dbconfig.php';

class TourPackage
{
    private $conn;
    private $table = 'tour_packages';

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // Add methods for CRUD operations
    public function create($name, $description, $destinationUniqueId, $price, $duration, $included_services, $itinerary, $prices, $price_inclusion, $price_exclusion)
    {
        $query = 'INSERT INTO ' . $this->table . ' (tourPackageUniqueId, name, description, destinationUniqueId, price, duration, included_services, itinerary, prices, price_inclusion, price_exclusion) VALUES (UUID(), :name, :description, :destinationUniqueId, :price, :duration, :included_services, :itinerary, :prices, :price_inclusion, :price_exclusion)';
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':destinationUniqueId', $destinationUniqueId);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':duration', $duration);
        $stmt->bindParam(':included_services', $included_services);
        $stmt->bindParam(':itinerary', $itinerary);
        $stmt->bindParam(':prices', $prices);
        $stmt->bindParam(':price_inclusion', $price_inclusion);
        $stmt->bindParam(':price_exclusion', $price_exclusion);

        if ($stmt->execute()) {
            return true;
        }

        printf("Error: %s.\n", $stmt->error);

        return false;
    }

    // Read Tour Package
    public function read()
    {
        $query = 'SELECT * FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Update Tour Package
    public function update($tourPackageUniqueId, $name, $description, $destinationUniqueId, $price, $duration, $included_services, $itinerary, $prices, $price_inclusion, $price_exclusion)
    {
        $query = 'UPDATE ' . $this->table . '
                  SET name = :name, description = :description, destinationUniqueId = :destinationUniqueId, price = :price, duration = :duration, included_services = :included_services, itinerary = :itinerary, prices = :prices, price_inclusion = :price_inclusion, price_exclusion = :price_exclusion
                  WHERE tourPackageUniqueId = :tourPackageUniqueId';
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':tourPackageUniqueId', $tourPackageUniqueId);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':destinationUniqueId', $destinationUniqueId);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':duration', $duration);
        $stmt->bindParam(':included_services', $included_services);
        $stmt->bindParam(':itinerary', $itinerary);
        $stmt->bindParam(':prices', $prices);
        $stmt->bindParam(':price_inclusion', $price_inclusion);
        $stmt->bindParam(':price_exclusion', $price_exclusion);

        if ($stmt->execute()) {
            return true;
        }

        printf("Error: %s.\n", $stmt->error);

        return false;
    }

    // Delete Tour Package
    public function delete($tourPackageUniqueId)
    {
        $query = 'DELETE FROM ' . $this->table . ' WHERE tourPackageUniqueId = :tourPackageUniqueId';
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':tourPackageUniqueId', $tourPackageUniqueId);

        if ($stmt->execute()) {
            return true;
        }

        printf("Error: %s.\n", $stmt->error);

        return false;
    }
}
