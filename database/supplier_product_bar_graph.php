<?php
include('connection.php');

$columnColors =['#FF0000','#0000FF', '#ADD8E6','#800080','#00FF00','#FF00FF','#FFA500','#800000'];

// Query suppliers name & id
$stmt = $connection->prepare("SELECT id,supplier_name FROM suppliers");
$stmt->execute();
$stmt->setFetchMode(PDO::FETCH_ASSOC);
$supplierRows = $stmt->fetchAll();


// Build query string to get the count of the supplier
$supplierNames=[];
$queryString = "SELECT supplier";
foreach($supplierRows as $row) {
    $id = $row['id'];
    $name=$row['supplier_name'];

    array_push($supplierNames,$name);
    $queryString = $queryString . ", SUM(CASE WHEN supplier=".$id." THEN 1 ELSE 0 END) ". $name;
}
$queryString = $queryString . " FROM productSupplier";


// Query for supplier's count
$stmt = $connection->prepare($queryString);
$stmt->execute();
$supplierCountData = $stmt->fetch(PDO::FETCH_ASSOC);

// Organize the data - build the array for the bar chart
$barChartData=[];
$counter=0;
foreach($supplierNames as $key=> $name) {
    if (!isset($columnColors[$key])) $counter=0;

    if (array_key_exists($name, $supplierCountData)) {
        // array_push($barChartData,(int)$supplierCountData[$name]);
        $barChartData[] = [
            'y'=>(int)$supplierCountData[$name],
            'color' => $columnColors[$counter]
        ];
        $counter++;
    } else {
        // array_push($barChartData,0);
        $barChartData[] = [
            'y'=>0
        ];
    }
}

// echo '<pre>';
// var_dump($supplierNames);
// echo '</pre>';
// die;

// // Query supplier product count
// foreach($supplierRows as $row) {
//     $id = $row['id'];

//     // Query counts
//     $stmt = $connection->prepare("SELECT count(*) as product_count FROM productSupplier WHERE productSupplier.supplier=?");
//     $stmt->execute([$id]);
//     $supplierCounts = $stmt->fetch(PDO::FETCH_ASSOC);

//     $count = $supplierCounts['product_count'];
//     var_dump($count);
// }
?>