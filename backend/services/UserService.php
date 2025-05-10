<?php
require_once __DIR__ . '/../dao/UserDAO.php';

class UserService {
    private $userDAO;

    public function __construct() {
        $this->userDAO = new UserDAO();
    }

    public function createUser($name, $email, $password, $role) {
        // Basic validation
        if (empty($name) || empty($email) || empty($password) || empty($role)) {
            throw new Exception("All fields are required.");
        }

        // Check for existing email
        $existingUsers = $this->userDAO->getAll();
        foreach ($existingUsers as $user) {
            if ($user['email'] === $email) {
                throw new Exception("Email already exists.");
            }
        }

        // TODO: hash password (for production)
        return $this->userDAO->create($name, $email, $password, $role);
    }

    public function getAllUsers() {
        return $this->userDAO->getAll();
    }

    public function getUserById($id) {
        return $this->userDAO->getById($id);
    }

    public function updateUser($id, $name, $email, $password, $role) {
        if (empty($name) || empty($email) || empty($password) || empty($role)) {
            throw new Exception("All fields are required.");
        }
        return $this->userDAO->update($id, $name, $email, $password, $role);
    }

    public function deleteUser($id) {
        return $this->userDAO->delete($id);
    }
}
?>
