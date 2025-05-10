<?php
require_once __DIR__ . '/../dao/CarDAO.php';

class CarService {
    private $carDAO;

    public function __construct() {
        $this->carDAO = new CarDAO();
    }

    public function getAll() {
        return $this->carDAO->getAll();
    }

    public function getById($id) {
        return $this->carDAO->getById($id);
    }

    public function create($make, $model, $year, $price, $status) {
        return $this->carDAO->create($make, $model, $year, $price, $status);
    }

    public function update($id, $make, $model, $year, $price, $status) {
        return $this->carDAO->update($id, $make, $model, $year, $price, $status);
    }

    public function delete($id) {
        return $this->carDAO->delete($id);
    }
}
?>
