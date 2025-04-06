<?php
require_once __DIR__ . '/../config/db.php';

class SalesDAO {
    private $pdo;

    public function __construct() {
        // Create a new instance of the Database class and get the PDO connection
        $database = new Database();
        $this->pdo = $database->connect();
    }

    public function create($car_id, $customer_id, $employee_id, $sale_date, $price) {
        $stmt = $this->pdo->prepare("INSERT INTO sales (car_id, customer_id, employee_id, sale_date, price) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$car_id, $customer_id, $employee_id, $sale_date, $price]);
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM sales");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM sales WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $car_id, $customer_id, $employee_id, $sale_date, $price) {
        $stmt = $this->pdo->prepare("UPDATE sales SET car_id=?, customer_id=?, employee_id=?, sale_date=?, price=? WHERE id=?");
        return $stmt->execute([$car_id, $customer_id, $employee_id, $sale_date, $price, $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM sales WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
