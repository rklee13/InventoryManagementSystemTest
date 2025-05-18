<?php
session_start();
include "connection.php";

$batchId = $_POST['batchId'];
$purchase_update_orders=$_POST['data'];

try {
    
    foreach($purchase_update_orders as $purchase_order) {
        $received=(int)$purchase_order['qtyReceived'];
        $status=$purchase_order['status'];
        $id=$purchase_order['id'];
        $remaining= (int)$purchase_order['qtyOrdered']-$received;
        $update_at=date("Y-m-d H:i:s");

        $insert_query = "UPDATE product_order 
            SET 
                quantity_received=?, status=?, quantity_remaining=?, updated_at=? 
            WHERE
                id=?";
        $connection->prepare($insert_query)->execute([$received,$status,$remaining, $update_at,$id]);
    }

    $response= [
        'success' => true,
        'message' => "Batch <strong>#$batchId</strong> was successfully updated.",
    ];
} catch (\Exception $e) {
    $response= [
        'success' => false,
        'message' => 'Error processing your request!',
    ];
}

echo json_encode($response);

?>