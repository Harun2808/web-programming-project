<?php
require_once __DIR__ . '/../config/db.php';

class CustomerDAO {
    private $pdo;

    public function __construct() {

        $database = new Database();
        $this->pdo = $database->connect();
    }

    public function create($name, $email, $phone) {
        $stmt = $this->pdo->prepare("INSERT INTO customers (name, email, phone, created_at) VALUES (?, ?, ?, NOW())");
        return $stmt->execute([$name, $email, $phone]);
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM customers");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM customers WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $name, $email, $phone) {
        $stmt = $this->pdo->prepare("UPDATE customers SET name=?, email=?, phone=? WHERE id=?");
        return $stmt->execute([$name, $email, $phone, $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM customers WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
