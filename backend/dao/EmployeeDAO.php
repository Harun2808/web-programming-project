<?php
require_once __DIR__ . '/../config/db.php';

class EmployeeDAO {
    private $pdo;

    public function __construct() {
        // Create a new instance of the Database class and get the PDO connection
        $database = new Database();
        $this->pdo = $database->connect();
    }


    public function create($user_id, $name, $position, $contact, $status = 'active') {
        $stmt = $this->pdo->prepare("INSERT INTO employees (user_id, name, position, contact, status, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        return $stmt->execute([$user_id, $name, $position, $contact, $status]);
    }

    // Get all employees
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM employees");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get employee by ID
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM employees WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update employee details by ID
    public function update($id, $user_id, $name, $position, $contact, $status) {
        $stmt = $this->pdo->prepare("UPDATE employees SET user_id=?, name=?, position=?, contact=?, status=? WHERE id=?");
        return $stmt->execute([$user_id, $name, $position, $contact, $status, $id]);
    }

    // Delete an employee by ID
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM employees WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
