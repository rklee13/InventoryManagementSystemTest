<?php

session_start();
include "connection.php";
// Capture the table mappings
include 'tableColumns.php';

$id =(int)$_POST['id'];
$table_name = $_SESSION['table'];
$columns=$table_update_columns_mapping[$table_name];

// Loop through the columns
$databaseArray['id']=$id;
$queryString="";
foreach ($columns as $column) {
    // Reset the value variable
    $value=null;

    if (in_array($column, ['product_name', 'first_name'])) {
        $name=$_POST[$column];
    }
    if ($column == 'updated_at') {
        $value = date('Y-m-d H:i:s');
    } else if ($column == 'image') {
        // Upload/move file to our directory
        $target_directory = "../uploads/products/";
        $file_data = $_FILES[$column];
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
    } else {
        $value = isset($_POST[$column]) ? $_POST[$column] : '';
    }
    $queryString.= "$column = :$column,";
    $databaseArray[$column] = $value;
}
$queryString = rtrim($queryString, ",");
if (isset($columns['last_name'])) {
    $name = $name + ' ' + $columns['last_name'];
}

// Updating the user
try {
    //$update_query = "UPDATE $table_name SET first_name=?, last_name=?, email=?, updated_at=? WHERE id=?";
    $update_query = "UPDATE $table_name SET $queryString WHERE id=:id";
    $connection->prepare($update_query)->execute($databaseArray);

    echo json_encode([
        'success'=> true,
        'message' => "<strong>$name</strong> was successfully updated.",
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success'=> false,
        'message' => 'Error processing your request!',
    ]);
}

?>