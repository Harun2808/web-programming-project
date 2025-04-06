<?php

require_once '../dao/SalesDAO.php';




$salesDao = new SalesDAO();

// Test: Create a new sale
if (isset($_POST['create'])) {
    $car_id = $_POST['car_id'];
    $customer_id = $_POST['customer_id'];
    $employee_id = $_POST['employee_id'];
    $sale_date = $_POST['sale_date'];
    $price = $_POST['price'];

    if ($salesDao->create($car_id, $customer_id, $employee_id, $sale_date, $price)) {
        echo "<p>Sale created successfully!</p>";
    } else {
        echo "<p>Failed to create sale.</p>";
    }
}

// Test: Get all sales
$sales = $salesDao->getAll();

// Test: Get sale by ID (if any ID is passed)
if (isset($_GET['id'])) {
    $sale = $salesDao->getById($_GET['id']);
    echo "<pre>"; print_r($sale); echo "</pre>";
}

// Test: Update a sale (if any ID is passed and form is submitted)
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $car_id = $_POST['car_id'];
    $customer_id = $_POST['customer_id'];
    $employee_id = $_POST['employee_id'];
    $sale_date = $_POST['sale_date'];
    $price = $_POST['price'];

    if ($salesDao->update($id, $car_id, $customer_id, $employee_id, $sale_date, $price)) {
        echo "<p>Sale updated successfully!</p>";
    } else {
        echo "<p>Failed to update sale.</p>";
    }
}

// Test: Delete a sale (if any ID is passed)
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if ($salesDao->delete($id)) {
        echo "<p>Sale deleted successfully!</p>";
    } else {
        echo "<p>Failed to delete sale.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SalesDAO Test</title>
</head>
<body>
    <h1>SalesDAO Test</h1>

    <h2>Create Sale</h2>
    <form method="POST">
        <input type="number" name="car_id" placeholder="Car ID" required>
        <input type="number" name="customer_id" placeholder="Customer ID" required>
        <input type="number" name="employee_id" placeholder="Employee ID" required>
        <input type="date" name="sale_date" placeholder="Sale Date" required>
        <input type="number" name="price" placeholder="Sale Price" required>
        <button type="submit" name="create">Create Sale</button>
    </form>

    <h2>All Sales</h2>
    <pre><?php print_r($sales); ?></pre>

    <h2>Update Sale</h2>
    <form method="POST">
        <input type="number" name="id" placeholder="Sale ID" required>
        <input type="number" name="car_id" placeholder="Car ID" required>
        <input type="number" name="customer_id" placeholder="Customer ID" required>
        <input type="number" name="employee_id" placeholder="Employee ID" required>
        <input type="date" name="sale_date" placeholder="Sale Date" required>
        <input type="number" name="price" placeholder="Sale Price" required>
        <button type="submit" name="update">Update Sale</button>
    </form>

    <h2>Delete Sale</h2>
    <form method="GET">
        <input type="number" name="delete" placeholder="Sale ID to Delete" required>
        <button type="submit">Delete Sale</button>
    </form>

    <h2>Get Sale by ID</h2>
    <form method="GET">
        <input type="number" name="id" placeholder="Sale ID to View" required>
        <button type="submit">Get Sale</button>
    </form>
</body>
</html>
