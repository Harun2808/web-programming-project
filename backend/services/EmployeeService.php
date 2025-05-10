<?php
require_once __DIR__ . '/../dao/EmployeeDAO.php';

class EmployeeService {
    private $employeeDAO;

    public function __construct() {
        $this->employeeDAO = new EmployeeDAO();
    }

    public function getAll() {
        return $this->employeeDAO->getAll();
    }

    public function getById($id) {
        return $this->employeeDAO->getById($id);
    }

    public function create($user_id, $name, $position, $contact, $status = 'active') {
        return $this->employeeDAO->create($user_id, $name, $position, $contact, $status);
    }

    public function update($id, $user_id, $name, $position, $contact, $status) {
        return $this->employeeDAO->update($id, $user_id, $name, $position, $contact, $status);
    }

    public function delete($id) {
        return $this->employeeDAO->delete($id);
    }
}
?>
