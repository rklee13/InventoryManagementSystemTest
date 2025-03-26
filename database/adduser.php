<?php

session_start();
include "connection.php";

$table_name = $_SESSION['table'];
// These should be coming from elements with the specified name attributes (such as user-add.php)
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$password = $_POST['password'];

// Hash the password
$encrypted = password_hash($password, PASSWORD_DEFAULT);

// Adding the record
try {
    $insert_query = "INSERT INTO $table_name(`first_name`, `last_name`, `password`, `email`, `created_at`, `updated_at`) VALUES ('$first_name', '$last_name', '$encrypted','$email', NOW(), NOW())";
    $connection->exec($insert_query);

    $response = [
        'success'=> true,
        'message'=> $first_name. ' '. $last_name . ' was successfully added to the system.',
    ];

} catch (PDOException $e) {
    $response = [
        'success'=> false,
        'message'=> $e->getMessage(),
    ];
}

$_SESSION['response'] = $response;
header('location: ../users-add.php');
?>