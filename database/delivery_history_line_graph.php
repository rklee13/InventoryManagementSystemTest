<?php
include('connection.php');

// Query suppliers name & id
$stmt = $connection->prepare("SELECT quatity_received,date_received FROM product_order_history ORDER BY date_received ASC");
$stmt->execute();
$stmt->setFetchMode(PDO::FETCH_ASSOC);
$historyRows = $stmt->fetchAll();

$line_categories = [];
$line_data=[];
foreach($historyRows as $row) {
    $key=date('Y-m-d', strtotime($row['date_received']));
    $line_data[$key]= isset($line_data[$key]) ? $line_data[$key] + (int)$row['quatity_received'] : (int)$row['quatity_received'];
}

$line_categories= array_keys($line_data);
$line_data=array_values($line_data);

// echo '<pre>';
// var_dump($line_categories);
// var_dump($line_data);
// echo '</pre>';
// die;

?>