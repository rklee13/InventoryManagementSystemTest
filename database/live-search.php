<?php 
include 'connection.php';

$search_term = isset($_GET['search_term']) ? trim(strtolower($_GET['search_term'])) : '';

// Query database
$tables = [
    'UserLoginInformation' => '', 
    'products' => 'product_name', 
    'suppliers' => 'supplier_name'
];

$results=[];
$length = 0;
foreach($tables as $table_name => $column) {
    if ($table_name === 'UserLoginInformation') {
        $stmt = $connection->prepare("SELECT * FROM $table_name WHERE first_name LIKE '%". $search_term ."%' OR last_name LIKE '%". $search_term ."%' ORDER BY created_at DESC");
    } else {
        $stmt = $connection->prepare("SELECT * FROM $table_name WHERE $column LIKE '%". $search_term ."%' ORDER BY created_at DESC");
    }
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $rows= $stmt->fetchAll();

    $length+=count($rows);
    $results[$table_name] = $rows;
}

echo json_encode([
    'length'=> $length,
    'data'=> $results
]);
?>