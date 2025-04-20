<?php

session_start();
include "connection.php";
// Capture the table mappings
include 'tableColumns.php';

// Capture the table name
$table_name = $_SESSION['table'];
$columns = $table_columns_mapping[$table_name];

// Loop through the columns
$databaseArray = [];
$user = $_SESSION['user'];
foreach ($columns as $column) {
    // Reset the value variable
    $value = null;

    if (in_array($column, ['created_at', 'updated_at'])) {
        $value = date('Y-m-d H:i:s');
    } else if ($column == 'created_by') {
        $value = $user['id'];
    } else if ($column == 'password' && isset($_POST[$column])) {
        // Hash the password if provided
        $value = password_hash($_POST[$column], PASSWORD_DEFAULT);
    } else if ($column == 'image') {
        // Upload/move file to our directory
        $target_directory = "../uploads/products/";
        $file_data = $_FILES[$column];

        if ($file_data['tmp_name'] !== '') {
            // Give unique file name with timestamp
            $file_name = 'product-' . time() . '-' . $file_data['name'];
            // $file_extension = pathinfo($file_name,PATHINFO_EXTENSION); // This will get the file extension

            // Check if image is valid by seeing if we get a valid image size data
            $check = getimagesize($file_data['tmp_name']);
            if ($check) {
                // Move the file
                if (move_uploaded_file($file_data['tmp_name'], $target_directory . $file_name)) {
                    // Save path to our database
                    $value = $file_name;
                }
            }
        }
    } else {
        $value = isset($_POST[$column]) ? $_POST[$column] : '';
    }
    $databaseArray[$column] = $value;
}

// Convert the column names into a string for SQL
$table_properties = implode(', ', array_keys($databaseArray));
$table_values = ":" . implode(", :", array_keys($databaseArray));

// Adding the record to the main table
try {
    $insert_query = "INSERT INTO $table_name($table_properties) VALUES ($table_values)";

    $connection->prepare($insert_query)->execute($databaseArray);

    // Add suppliers
    if ($table_name === 'products') {
        $suppliers = isset($_POST['suppliers']) ? $_POST['suppliers'] : [];
        if ($suppliers && count($suppliers) > 0) {
            $productId = $connection->lastInsertId();

            // Loop through the suppliers and add record
            foreach ($suppliers as $supplier) {
                $supplier_data = [
                    'supplier_id' => $supplier,
                    'product_id' => $productId,
                    'updated_at' => date("Y-m-d H:i:s"),
                    'created_at' => date("Y-m-d H:i:s"),
                ];

                $insert_query = "INSERT INTO productSupplier(supplier, product, created_at, updated_at) VALUES (:supplier_id, :product_id, :created_at, :updated_at)";
                $connection->prepare($insert_query)->execute($supplier_data);
            }
        }
    }

    $response = [
        'success' => true,
        'message' => 'Successfully added to the system.',
    ];

} catch (PDOException $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage(),
    ];
}

$_SESSION['response'] = $response;
$_SESSION['redirect_to'];
header('location: ../' . $_SESSION['redirect_to']);
?>