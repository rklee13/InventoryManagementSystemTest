<?php
$type = $_GET['report'];
$mapping_filenames = [
    'products' => 'Products Report',
    'suppliers' => 'Suppliers Report',
    'deliveries' => 'Deliveries Report',
    'orders' => 'Product Orders Report',
];

$file_name = $mapping_filenames[$type] . ' ' . gmdate('Y-m-d H:i:s') . '.csv';

// Pull data from the database
include 'connection.php';

$select_query = '';
if ($type === 'products') {
    $select_query = "SELECT products.*,UserLoginInformation.first_name,UserLoginInformation.last_name FROM products INNER JOIN UserLoginInformation ON products.created_by=UserLoginInformation.id ORDER BY products.created_at DESC";
} else if ($type === 'suppliers') {
    $select_query = "SELECT suppliers.*,UserLoginInformation.first_name,UserLoginInformation.last_name FROM suppliers INNER JOIN UserLoginInformation ON suppliers.created_by=UserLoginInformation.id ORDER BY suppliers.created_at DESC";
} else if ($type === 'orders') {
    $select_query = "SELECT product_order.*,suppliers.supplier_name,products.product_name,UserLoginInformation.first_name,UserLoginInformation.last_name 
        FROM product_order 
        INNER JOIN products ON product_order.product=products.id
        INNER JOIN suppliers ON product_order.supplier=suppliers.id
        INNER JOIN UserLoginInformation ON product_order.created_by=UserLoginInformation.id
        ORDER BY product_order.batch DESC";
} else if ($type === 'deliveries') {
    // $select_query = "SELECT product_order_history.*,suppliers.supplier_name,products.product_name 
    //     FROM product_order_history 
    //     INNER JOIN product_order ON product_order_history.product_order_id=product_order.id
    //     INNER JOIN suppliers ON product_order.supplier=suppliers.id
    //     INNER JOIN products ON product_order.product=products.id
    //     ORDER BY product_order_history.date_received DESC";
    $select_query = "SELECT product_order_history.date_received, product_order_history.quatity_received as quantity_received,product_order.batch,
            UserLoginInformation.first_name,UserLoginInformation.last_name, products.product_name, suppliers.supplier_name 
        FROM product_order_history,product_order,products,suppliers,UserLoginInformation
        WHERE product_order_history.product_order_id=product_order.id 
        AND product_order.created_by=UserLoginInformation.id 
        AND product_order.supplier=suppliers.id 
        AND product_order.product=products.id
        ORDER BY product_order.batch DESC";
}

$stmt = $connection->prepare($select_query);
$stmt->execute();
$stmt->setFetchMode(PDO::FETCH_ASSOC);
$dataRows = $stmt->fetchAll();

// echo '<pre>';
// var_dump($dataRows);
// echo '</pre>';
// die;

// Create and exort to csv file
header("Content-Type: text/csv; charset=utf-8");
header("Content-Disposition: attachment; filename=\"$file_name\"");

// Insert data into csv
$obtain_header = true;
$row_header = '';
$add_line_break=false;
$batchId='';
foreach ($dataRows as $index=>$row) {

    // Clean up the data
    if ($type === 'products' || $type === 'suppliers') {
        $row['created_by'] = $row['first_name'] . ' ' . $row['last_name'];
        $row['updated_at'] = date('Y-d-M H:i:s A', strtotime($row['updated_at']));
        $row['created_at'] = date('Y-d-M H:i:s A', strtotime($row['created_at']));
        unset($row['first_name'], $row['last_name']);
    } else if ($type === 'orders') {
        if ($batchId !== $row['batch']) {
            $batchId = $row['batch'];
            
            // Don't add a line break for the first row
            if ($index > 0) $add_line_break=true;
        }
        $row['created_by'] = $row['first_name'] . ' ' . $row['last_name'];
        $row['product']=$row['product_name'];
        $row['supplier']=$row['supplier_name'];
        if (!$row['quantity_ordered']) $row['quantity_ordered']=0;
        if (!$row['quantity_received']) $row['quantity_received']=0;
        if (!$row['quantity_remaining']) $row['quantity_remaining']=0;
        $row['updated_at'] = date('Y-d-M H:i:s A', strtotime($row['updated_at']));
        $row['created_at'] = date('Y-d-M H:i:s A', strtotime($row['created_at']));
        unset($row['first_name'], $row['last_name'], $row['product_name'],$row['supplier_name']);
    } else if ($type === 'deliveries') {
        if ($batchId !== $row['batch']) {
            $batchId = $row['batch'];
            
            // Don't add a line break for the first row
            if ($index > 0) $add_line_break=true;
        }
        $row['date_received'] = date('Y-d-M H:i:s A', strtotime($row['date_received']));
    }

    // Insert the header only once
    if ($obtain_header) {
        $row_header = array_keys($row);
        $obtain_header = false;
        echo implode("\t", array_values($row_header)) . "\n";
    }

    // detect double-quotes and escape any values that contains them
    array_walk($row, function (&$str) {
        $str = preg_replace("/\t/", "\\t", $str);
        $str = preg_replace("/\r?\n/", "\\n", $str);
        if (strstr($str, '"'))
            $str = '"' . str_replace('"', '""', $str) . '"';
    });

    // Add new line for additional specificity
    if ($add_line_break) {
        echo "\n";
        $add_line_break=false;
    }

    // Insert data
    //echo implode("\t", array_values($row)) . "\n";
    echo implode("\t", $row) . "\n";
}
?>