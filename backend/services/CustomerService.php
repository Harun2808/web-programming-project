<?php
require_once __DIR__ . '/../dao/CustomerDAO.php';

class CustomerService {
    private $customerDAO;

    public function __construct() {
        $this->customerDAO = new CustomerDAO();
    }

    public function getAll() {
        return $this->customerDAO->getAll();
    }

    public function getById($id) {
        return $this->customerDAO->getById($id);
    }

    public function create($name, $email, $phone) {
        return $this->customerDAO->create($name, $email, $phone);
    }

    public function update($id, $name, $email, $phone) {
        return $this->customerDAO->update($id, $name, $email, $phone);
    }

    public function delete($id) {
        return $this->customerDAO->delete($id);
    }
}
?>
