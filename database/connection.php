<?php

$HOSTNAME = 'localhost';
$USERNAME = 'root';
$PASSWORD = '';
$DATABASE = 'InventoryManagementSystem';

// Connecting to database
try {
    // PDO is better for portability to different systems
    //$connection = mysqli_connect(hostname: $HOSTNAME, username: $USERNAME, password: $PASSWORD, database: $DATABASE);
    $connection=new PDO("mysql:host=$HOSTNAME; dbname=$DATABASE",$USERNAME, $PASSWORD);

    // set the PDO error mode to exception
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage();
    $error_message= $e->getMessage();
}

?>