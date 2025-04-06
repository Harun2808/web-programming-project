<?php
require_once __DIR__ . '/../config/db.php';

class CarDAO {
    private $pdo;

    public function __construct() {
        // Create a new instance of the Database class and get the PDO connection
        $database = new Database();
        $this->pdo = $database->connect();
    }

    public function create($make, $model, $year, $price, $status) {
        $stmt = $this->pdo->prepare("INSERT INTO cars (make, model, year, price, status, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        return $stmt->execute([$make, $model, $year, $price, $status]);
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM cars");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM cars WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $make, $model, $year, $price, $status) {
        $stmt = $this->pdo->prepare("UPDATE cars SET make=?, model=?, year=?, price=?, status=? WHERE id=?");
        return $stmt->execute([$make, $model, $year, $price, $status, $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM cars WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
