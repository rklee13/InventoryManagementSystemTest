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
foreach($columns as $column) {
    if (in_array($column, ['created_at','updated_at'])) {
        $value = date('Y-m-d H:i:s');
    } else if ($column == 'created_by') {
        $value = $user['id'];
    } else if ($column == 'password' && isset($_POST[$column])) {
        // Hash the password if provided
        $value=password_hash($_POST[$column], PASSWORD_DEFAULT);
    } else {
        $value = isset($_POST[$column]) ? $_POST[$column] : '';
    }
    $databaseArray[$column]=$value;
}

// Convert the column names into a string for SQL
$table_properties = implode(', ', array_keys($databaseArray)); 
$table_values = ":". implode(", :", array_keys($databaseArray));

// Adding the record
try {
    $insert_query = "INSERT INTO $table_name($table_properties) VALUES ($table_values)";

    $connection->prepare($insert_query)->execute($databaseArray);

    $response = [
        'success'=> true,
        'message'=> 'Successfully added to the system.',
    ];

} catch (PDOException $e) {
    $response = [
        'success'=> false,
        'message'=> $e->getMessage(),
    ];
}

$_SESSION['response'] = $response;
$_SESSION['redirect_to'];
header('location: ../' . $_SESSION['redirect_to']);
?>