<?php

require_once '../dao/CarDAO.php';


require_once '../config/db.php';
$database = new Database();
$pdo = $database->connect();

// Instantiate the CarDAO class
$carDao = new CarDAO($pdo);

// Test: Create a new car
if (isset($_POST['create'])) {
    $make = $_POST['make'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $price = $_POST['price'];
    $status = $_POST['status'];

    if ($carDao->create($make, $model, $year, $price, $status)) {
        echo "<p>Car created successfully!</p>";
    } else {
        echo "<p>Failed to create car.</p>";
    }
}

// Test: Get all cars
$cars = $carDao->getAll();

// Test: Get car by ID (if any ID is passed)
if (isset($_GET['id'])) {
    $car = $carDao->getById($_GET['id']);
    echo "<pre>"; print_r($car); echo "</pre>";
}

// Test: Update a car (if any ID is passed and form is submitted)
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $make = $_POST['make'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $price = $_POST['price'];
    $status = $_POST['status'];

    if ($carDao->update($id, $make, $model, $year, $price, $status)) {
        echo "<p>Car updated successfully!</p>";
    } else {
        echo "<p>Failed to update car.</p>";
    }
}

// Test: Delete a car (if any ID is passed)
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if ($carDao->delete($id)) {
        echo "<p>Car deleted successfully!</p>";
    } else {
        echo "<p>Failed to delete car.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CarDAO Test</title>
</head>
<body>
    <h1>CarDAO Test</h1>

    <h2>Create Car</h2>
    <form method="POST">
        <input type="text" name="make" placeholder="Make" required>
        <input type="text" name="model" placeholder="Model" required>
        <input type="number" name="year" placeholder="Year" required>
        <input type="number" name="price" placeholder="Price" required>

        <!-- Dropdown for status -->
        <label for="status">Status:</label>
        <select name="status" required>
            <option value="available">Available</option>
            <option value="sold">Sold</option>
        </select>

        <button type="submit" name="create">Create Car</button>
    </form>

    <h2>All Cars</h2>
    <pre><?php print_r($cars); ?></pre>

    <h2>Update Car</h2>
    <form method="POST">
        <input type="number" name="id" placeholder="Car ID" required>
        <input type="text" name="make" placeholder="Make" required>
        <input type="text" name="model" placeholder="Model" required>
        <input type="number" name="year" placeholder="Year" required>
        <input type="number" name="price" placeholder="Price" required>

        <!-- Dropdown for status -->
        <label for="status">Status:</label>
        <select name="status" required>
            <option value="available">Available</option>
            <option value="sold">Sold</option>
        </select>

        <button type="submit" name="update">Update Car</button>
    </form>

    <h2>Delete Car</h2>
    <form method="GET">
        <input type="number" name="delete" placeholder="Car ID to Delete" required>
        <button type="submit">Delete Car</button>
    </form>

    <h2>Get Car by ID</h2>
    <form method="GET">
        <input type="number" name="id" placeholder="Car ID to View" required>
        <button type="submit">Get Car</button>
    </form>
</body>
</html>
