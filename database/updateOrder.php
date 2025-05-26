<?php
session_start();
include "connection.php";

$batchId = $_POST['batchId'];
$purchase_update_orders = $_POST['data'];

try {

    foreach ($purchase_update_orders as $purchase_order) {
        $delivered = (int) $purchase_order['qtyDelivered'];

        // Only save if they are ordering
        if ($delivered > 0) {
            $qtyReceived = (int) $purchase_order['qtyReceived'];
            $status = $purchase_order['status'];
            $id = $purchase_order['id'];
            $productId = $purchase_order['productId'];
            $update_at = date("Y-m-d H:i:s");

            // Calculate values
            $remaining = (int) $purchase_order['qtyOrdered'] - $delivered;
            $updatedQtyReceived = $qtyReceived + $delivered;
            if (($remaining - $qtyReceived) <= 0) {
                $status="COMPLETE";
            }


            $insert_query = "UPDATE product_order 
            SET 
                quantity_received=?, status=?, quantity_remaining=?, updated_at=? 
            WHERE
                id=?";
            $connection->prepare($insert_query)->execute([$updatedQtyReceived, $status, $remaining, $update_at, $id]);

            // Script updating the product_order_history
            $delivery_history = [
                'product_order_id' => $id,
                'quatity_received' => $delivered,
                'date_received' => date('Y-m-d H:i:s'),
                'date_updated' => date('Y-m-d H:i:s')
            ];

            $insert_query = "INSERT INTO product_order_history(product_order_id, quatity_received, date_received, date_updated) VALUES (:product_order_id, :quatity_received, :date_received, :date_updated)";
            $connection->prepare($insert_query)->execute($delivery_history);

            // Script updating the main product quantity
            $stmt = $connection->prepare("SELECT products.stock FROM products WHERE id=$productId");
            $stmt->execute();
            $product_stock_data = $stmt->fetch();
            $current_stock=(int)$product_stock_data['stock'];

            // Update the stock
            $updated_stock = $current_stock + $delivered;
            $update_query = "UPDATE products SET stock=? WHERE id=?";
            $connection->prepare($update_query)->execute([$updated_stock, $productId]);
        }
    }

    $response = [
        'success' => true,
        'message' => "Batch <strong>#$batchId</strong> was successfully updated.",
    ];
} catch (\Exception $e) {
    $response = [
        'success' => false,
        'message' => 'Error processing your request!',
    ];
}

echo json_encode($response);

?>