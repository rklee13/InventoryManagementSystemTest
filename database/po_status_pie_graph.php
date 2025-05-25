<?php
include('connection.php');

$stmt = $connection->prepare("SELECT * FROM product_order WHERE 1");
$stmt->execute();
$stmt->setFetchMode(PDO::FETCH_ASSOC);
$response = $stmt->fetchAll();

$statuses = [
    'PENDING' => 0,
    'INCOMPLETE' => 0,
    'COMPLETE' => 0
];
foreach($response as $purchaseOrder) {
    $statuses[$purchaseOrder['status']]++;
}

$poStatusPieResults = [
    [
        'name'=> 'PENDING',
        'y' => $statuses['PENDING'],
    ],
    [
        'name'=> 'INCOMPLETE',
        'y' => $statuses['INCOMPLETE'],
    ],
    [
        'name'=> 'COMPLETE',
        'y' => $statuses['COMPLETE'],
    ]
];

// $statuses = ['PENDING','INCOMPLETE','COMPLETE'];
// $results = [];
// $total=0;
// foreach($statuses as $status) {
//     $stmt = $connection->prepare("SELECT count(*) FROM product_order WHERE status=?");
//     $stmt->execute([$status]);
//     $count=$stmt->fetchColumn();
    
//     $total=$total+$count;
//     $results[] = [
//         'name' => strtoupper($status),
//         'y' => $count
//     ];
// }

// echo '<pre>';
// var_dump($results);
// echo '</pre>';
// die;

?>