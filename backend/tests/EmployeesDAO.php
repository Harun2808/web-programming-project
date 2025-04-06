<?php
require_once '../dao/EmployeeDAO.php';

// Instantiate the EmployeeDAO class
$employeeDao = new EmployeeDAO();

// Test: Create a new employee
if (isset($_POST['create'])) {
    $user_id = $_POST['user_id'];
    $name = $_POST['name']; // New name field
    $position = $_POST['position'];
    $contact = $_POST['contact'];
    $status = $_POST['status']; // New status field

    if ($employeeDao->create($user_id, $name, $position, $contact, $status)) {
        echo "<p>Employee created successfully!</p>";
    } else {
        echo "<p>Failed to create employee.</p>";
    }
}

// Test: Get all employees
$employees = $employeeDao->getAll();

// Test: Get employee by ID (if any ID is passed)
if (isset($_GET['id'])) {
    $employee = $employeeDao->getById($_GET['id']);
    echo "<pre>"; print_r($employee); echo "</pre>";
}

// Test: Update an employee (if any ID is passed and form is submitted)
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $user_id = $_POST['user_id'];
    $name = $_POST['name']; // New name field
    $position = $_POST['position'];
    $contact = $_POST['contact'];
    $status = $_POST['status']; // New status field

    if ($employeeDao->update($id, $user_id, $name, $position, $contact, $status)) {
        echo "<p>Employee updated successfully!</p>";
    } else {
        echo "<p>Failed to update employee.</p>";
    }
}

// Test: Delete an employee (if any ID is passed)
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if ($employeeDao->delete($id)) {
        echo "<p>Employee deleted successfully!</p>";
    } else {
        echo "<p>Failed to delete employee.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EmployeeDAO Test</title>
</head>
<body>
    <h1>EmployeeDAO Test</h1>

    <h2>Create Employee</h2>
    <form method="POST">
        <input type="number" name="user_id" placeholder="User ID" required>
        <input type="text" name="name" placeholder="Name" required> <!-- Added name input -->
        <input type="text" name="position" placeholder="Position" required>
        <input type="text" name="contact" placeholder="Contact" required>
        <select name="status" required>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select> <!-- Added status select -->
        <button type="submit" name="create">Create Employee</button>
    </form>

    <h2>All Employees</h2>
    <pre><?php print_r($employees); ?></pre>

    <h2>Update Employee</h2>
    <form method="POST">
        <input type="number" name="id" placeholder="Employee ID" required>
        <input type="number" name="user_id" placeholder="User ID" required>
        <input type="text" name="name" placeholder="Name" required> 
        <input type="text" name="position" placeholder="Position" required>
        <input type="text" name="contact" placeholder="Contact" required>
        <select name="status" required>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
        <button type="submit" name="update">Update Employee</button>
    </form>

    <h2>Delete Employee</h2>
    <form method="GET">
        <input type="number" name="delete" placeholder="Employee ID to Delete" required>
        <button type="submit">Delete Employee</button>
    </form>

    <h2>Get Employee by ID</h2>
    <form method="GET">
        <input type="number" name="id" placeholder="Employee ID to View" required>
        <button type="submit">Get Employee</button>
    </form>
</body>
</html>
