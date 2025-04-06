<?php
require_once '../dao/UserDAO.php';

$userDao = new UserDAO();

// Insert test
$userDao->create('John Doe 2', 'john@example2.com', '1234526', 'admin');

// Read all users
$users = $userDao->getAll();
echo "<pre>"; print_r($users); echo "</pre>";
?>
