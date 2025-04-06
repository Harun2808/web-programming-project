<?php
// Include the CustomerDAO class and set up the PDO connection
require_once '../dao/CustomerDAO.php';


// Instantiate the CustomerDAO class
$customerDao = new CustomerDAO();

// Test: Create a new customer
if (isset($_POST['create'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    if ($customerDao->create($name, $email, $phone)) {
        echo "<p>Customer created successfully!</p>";
    } else {
        echo "<p>Failed to create customer.</p>";
    }
}

// Test: Get all customers
$customers = $customerDao->getAll();

// Test: Get customer by ID (if any ID is passed)
if (isset($_GET['id'])) {
    $customer = $customerDao->getById($_GET['id']);
    echo "<pre>"; print_r($customer); echo "</pre>";
}

// Test: Update a customer (if any ID is passed and form is submitted)
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    if ($customerDao->update($id, $name, $email, $phone)) {
        echo "<p>Customer updated successfully!</p>";
    } else {
        echo "<p>Failed to update customer.</p>";
    }
}

// Test: Delete a customer (if any ID is passed)
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if ($customerDao->delete($id)) {
        echo "<p>Customer deleted successfully!</p>";
    } else {
        echo "<p>Failed to delete customer.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CustomerDAO Test</title>
</head>
<body>
    <h1>CustomerDAO Test</h1>

    <h2>Create Customer</h2>
    <form method="POST">
        <input type="text" name="name" placeholder="Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="phone" placeholder="Phone" required>
        <button type="submit" name="create">Create Customer</button>
    </form>

    <h2>All Customers</h2>
    <pre><?php print_r($customers); ?></pre>

    <h2>Update Customer</h2>
    <form method="POST">
        <input type="number" name="id" placeholder="Customer ID" required>
        <input type="text" name="name" placeholder="Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="phone" placeholder="Phone" required>
        <button type="submit" name="update">Update Customer</button>
    </form>

    <h2>Delete Customer</h2>
    <form method="GET">
        <input type="number" name="delete" placeholder="Customer ID to Delete" required>
        <button type="submit">Delete Customer</button>
    </form>

    <h2>Get Customer by ID</h2>
    <form method="GET">
        <input type="number" name="id" placeholder="Customer ID to View" required>
        <button type="submit">Get Customer</button>
    </form>
</body>
</html>
