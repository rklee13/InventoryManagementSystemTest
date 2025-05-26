<?php
    $type=$_GET['report'];
    $mapping_filenames = [
        'products' => 'Products Report',
        'suppliers' => 'Suppliers Report',
        'deliveries' => 'Deliveries Report',
        'orders' => 'Product Orders Report',
    ];

    $file_name=$mapping_filenames[$type] . ' ' . gmdate('Y-m-d H:i:s') . '.csv';

    // Pull data from the database
    include 'connection.php';

    $select_query='';
    if ($type === 'products') {
        $select_query = "SELECT products.*,UserLoginInformation.first_name,UserLoginInformation.last_name FROM products INNER JOIN UserLoginInformation ON products.created_by=UserLoginInformation.id ORDER BY products.created_at DESC";
    } else if ($type === 'suppliers') {
        $select_query = "SELECT suppliers.*,UserLoginInformation.first_name,UserLoginInformation.last_name FROM suppliers INNER JOIN UserLoginInformation ON suppliers.created_by=UserLoginInformation.id ORDER BY suppliers.created_at DESC";
    } else if ($type === 'deliveries') {
        $select_query = "SELECT * FROM products ORDER BY created_at DESC";
    } else if ($type === 'orders') {
        $select_query = "SELECT * FROM products ORDER BY created_at DESC";
    }

    $stmt = $connection->prepare($select_query);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $dataRows=$stmt->fetchAll();
    
    // echo '<pre>';
    // var_dump($dataRows);
    // echo '</pre>';
    // die;

    // Create and exort to csv file
    header("Content-Type: text/csv; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"$file_name\"");

    // Insert data into csv
     $obtain_header = true;
    $row_header='';
    foreach($dataRows as $row) {

        // Clean up the data
        if ($type === 'products' || $type === 'suppliers') {
            $row['created_by'] = $row['first_name'] . ' ' . $row['last_name'];
            unset($row['first_name'], $row['last_name']);       
        } else if ($type === 'deliveries') {
            
        } else if ($type === 'orders') {
            
        }

        // Insert the header only once
        if ($obtain_header) {
            $row_header = array_keys($row);
            $obtain_header=false;
            echo implode("\t", array_values($row_header)) . "\n";
        }

        // detect double-quotes and escape any values that contains them
        array_walk($row, function(&$str){
            $str = preg_replace("/\t/", "\\t", $str);
            $str = preg_replace("/\r?\n/", "\\n", $str);
            if(strstr($str, '"')) $str='"' . str_replace('"', '""',$str).'"';
        });

        // Insert data
        //echo implode("\t", array_values($row)) . "\n";
        echo implode("\t", $row) . "\n";
    }
?>