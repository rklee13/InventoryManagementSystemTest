<?php
// Start the session
session_start();

if (!isset($_SESSION['user']))
    header("location:login.php");

$_SESSION['table'] = 'product_order';
$show_table = 'product_order';
$product_orders_all = include 'database/showAll.php';
?>

<!DOCTYPE html>
<html>

<head>
    <title>View Orders - Inventory Management System</title>
    <?php include('partials/app-headers-script.php'); ?>
</head>

<body>
    <div id="dashboardContainer">
        <!-- Sidebar -->
        <?php include 'partials/app-sidebar.php' ?>
        <div class="dashboardContentContainer" id="dashboardContentContainer">
            <!-- Top Navigator bars -->
            <?php include 'partials/app-topnav.php' ?>
            <?php if (in_array('purchaseOrder_view', $user['permissions'])) { ?>
                <!-- Main content section -->
                <div class="dashboardContent">
                    <div class="dashboardContentMain">
                        <div class="rowInfo">
                            <div class="column column-12">
                                <h1><i class="fa-solid fa-users"></i> List of Purchased Orders</h1>
                                <div class="userListContent">
                                    <div class="poListContainers">
                                        <?php 
                                            $stmt = $connection->prepare("SELECT product_order.id, product_order.product, products.product_name, product_order.quantity_ordered, suppliers.supplier_name, product_order.status, 
                                                product_order.batch, UserLoginInformation.first_name, UserLoginInformation.last_name, product_order.quantity_received, product_order.created_at 
                                                FROM product_order, suppliers, products, UserLoginInformation 
                                                WHERE product_order.supplier = suppliers.id AND product_order.product = products.id AND product_order.created_by = UserLoginInformation.id
                                                ORDER BY product_order.created_at DESC");

                                            $stmt->execute();
                                            $purchase_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                            $data=[];
                                            foreach($purchase_orders as $order) {
                                                $data[$order['batch']][]=$order;
                                            }
                                        ?>

                                        <?php foreach ($data as $batchId => $product_orders): ?>
                                            <div class="poList" id="<?= $batchId ?>">
                                                <p>Batch #: <?= $batchId ?></p>
                                                <table>
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Product</th>
                                                            <th>Quantity Ordered</th>
                                                            <th>Quantity Received</th>
                                                            <th>Supplier</th>
                                                            <th>Status</th>
                                                            <th>Ordered By</th>
                                                            <th>Created Date</th>
                                                            <th>Delivery History</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($product_orders as $index=>$product_order): ?>
                                                            <tr>
                                                                <td><?= $index+1 ?></td>
                                                                <td id="po_product"><?= $product_order['product_name'] ?></td>
                                                                <td id="po_qty_ordered"><?= $product_order['quantity_ordered'] ?></td>
                                                                <td id="po_qty_received"><?= $product_order['quantity_received'] ?></td>
                                                                <td id="po_supplier"><?= $product_order['supplier_name'] ?></td>
                                                                <td><span id="po_status" class="productOrder_badge productOrder_badge_<?= $product_order['status'] ?>"><?= $product_order['status'] ?></span></td>
                                                                <td><?= $product_order['first_name'] . ' ' . $product_order['last_name']?></td>
                                                                <td>
                                                                    <?= date('M d, Y h:i:s A e', strtotime($product_order['created_at'])) ?>
                                                                    <input type="hidden" id="po_row_id" value="<?= $product_order['id']?>">
                                                                    <input type="hidden" id="po_productId" value="<?= $product_order['product']?>">
                                                                </td>
                                                                <td>
                                                                    <button id="showDeliveryHistoryButton" class="button deliveryHistoryButton" data-id="<?= $product_order['id'] ?>">Delivery History</button>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach ?>
                                                    </tbody>
                                                </table>
                                                <?php if (in_array('purchaseOrder_edit', $user['permissions'])) { ?>
                                                    <div class="poOrderButtonContainer alignRight">
                                                        <button id="updatePoButton" class="button updatePoButton" data-id="<?= $batchId ?>">Update</button>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        <?php endforeach ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    if (isset($_SESSION['response'])) {
                        $response_message = $_SESSION['response']['message'];
                        $is_success = $_SESSION['response']['success'];
                        ?>
                        <div class="responseMessage">
                            <p class="<?= $is_success ? 'responseMessageSuccess' : 'responseMessageFailure' ?>">
                                <?= $response_message ?>
                            </p>
                        </div>
                        <?php unset($_SESSION['response']);
                    } ?>
                </div>
            <?php } else { ?>
                <div id="accessDeniedErrorMessage">Access denied.</div>
            <?php } ?>
        </div>
    </div>

    <?php
    include('partials/app-scripts.php');
    ?>
    <script>

        function script() {
            this.initialize = function () {
                this.registerEvents();
            }
            var vm = this;

            this.registerEvents = function () {
                document.addEventListener('click', function (e) {
                    const targetElement = e.target;
                    const targetElememtId = targetElement.id;

                    if (targetElememtId === 'updatePoButton') {
                        e.preventDefault(); // This prevents the automatic page refresh from the <a> element

                        const batchId = targetElement.dataset.id;

                        // Get all purchased order product records
                        const tableRows = document.getElementById(batchId).querySelector('tbody').querySelectorAll('tr');
                        
                        let poListsArr=[];
                        tableRows.forEach((row,key) => {
                            const productName=row.querySelector('#po_product').textContent;
                            const quantity_ordered=row.querySelector('#po_qty_ordered').textContent;
                            const quantity_received=row.querySelector('#po_qty_received').textContent;
                            const supplierName=row.querySelector('#po_supplier').textContent;
                            const status=row.querySelector('#po_status').textContent;
                            const productOrder_id =row.querySelector('#po_row_id').value;
                            const productOrder_productId = row.querySelector('#po_productId').value;

                            poListsArr.push({
                                name:productName,
                                qtyOrdered: quantity_ordered,
                                qtyReceived: quantity_received,
                                supplier: supplierName,
                                status: status,
                                id:productOrder_id,
                                productId: productOrder_productId,
                            });
                        });

                        // Store in html
                        let poListHtml = '<table id="formTable_'+batchId+'">\
                                                <thead>\
                                                    <tr>\
                                                        <th>Product</th>\
                                                        <th>Quantity Ordered</th>\
                                                        <th>Quantity Received</th>\
                                                        <th>Quantity Delivered</th>\
                                                        <th>Supplier</th>\
                                                        <th>Status</th>\
                                                    </tr>\
                                                </thead>\
                                                <tbody>';
                        
                        poListsArr.forEach(poData => {
                            const qtyReceived = !poData.qtyReceived ? '0' : poData.qtyReceived;
                            poListHtml+='\
                                        <tr>\
                                            <td id="po_product">'+ poData.name +'</td>\
                                            <td id="po_qty_ordered">'+ poData.qtyOrdered +'</td>\
                                            <td id="po_qty_received">'+ qtyReceived +'</td>\
                                            <td id="po_qty_delivered"><input type="number" value="'+0+'"/></td>\
                                            <td id="po_supplier">'+ poData.supplier +'</td>\
                                            <td><select id="po_status">\
                                                <option value="PENDING" '+(poData.status == 'PENDNG' ? 'selected':'')+'>PENDING</option>\
                                                <option value="INCOMPLETE" '+(poData.status == 'INCOMPLETE' ? 'selected':'')+'>INCOMPLETE</option>\
                                                <option value="COMPLETE" '+(poData.status == 'COMPLETE' ? 'selected':'')+'>COMPLETE</option>\
                                            </select>\
                                            <input type="hidden" id="po_row_id" value="'+poData.id+'">\
                                            <input type="hidden" id="po_productId" value="'+poData.productId+'">\
                                        </tr>';
                        });

                        poListHtml+="</tbody></table>";

                        BootstrapDialog.confirm({
                            size: BootstrapDialog.SIZE_WIDE,
                            type: BootstrapDialog.TYPE_PRIMARY,
                            title: 'Update Product Batch Order: <strong>#'+batchId+'</strong>',
                            message: poListHtml,
                            callback: function (isUpdate) {
                                if (isUpdate) {
                                    // Get all purchased order product records
                                    const formTableRows = document.getElementById('formTable_'+batchId).querySelector('tbody').querySelectorAll('tr');
                                    
                                    let updateListsArr=[];
                                    formTableRows.forEach((row,key) => {
                                        // const productName=row.querySelector('#po_product').textContent;
                                        // const supplierName=row.querySelector('#po_supplier').textContent;
                                        const quantity_ordered=row.querySelector('#po_qty_ordered').textContent;
                                        const quantity_received=row.querySelector('#po_qty_received').textContent;
                                        const quantity_delivered=row.querySelector('#po_qty_delivered input').value;
                                        const status=row.querySelector('#po_status').value;
                                        const productOrder_id =row.querySelector('#po_row_id').value;
                                        const productOrder_productId = row.querySelector('#po_productId').value;

                                        updateListsArr.push({
                                            qtyOrdered: quantity_ordered,
                                            qtyReceived: quantity_received,
                                            qtyDelivered: quantity_delivered,
                                            status: status,
                                            id: productOrder_id,
                                            productId: productOrder_productId
                                        });
                                    });
                                    
                                    // Send request to update database
                                    $.ajax({
                                        method: "POST",
                                        data: {
                                            batchId: batchId,
                                            data: updateListsArr
                                        },
                                        url: 'database/updateOrder.php',
                                        dataType: 'JSON',
                                        success: function (data) {
                                            BootstrapDialog.alert({
                                                type: data.success ? BootstrapDialog.TYPE_SUCCESS : BootstrapDialog.TYPE_DANGER,
                                                message: data.message,
                                                callback: function () {
                                                    if (data.success) location.reload();
                                                }
                                            });
                                        }
                                    });
                                }
                            }
                        });
                    } else if (targetElememtId === 'showDeliveryHistoryButton') {
                        const productOrder_Id = targetElement.dataset.id;

                        $.get('database/viewDeliveryHistory.php', {id: productOrder_Id}, function(data) {
                            if (data.length) {
                                rows = '';
                                data.forEach((row, id) => {
                                    rows += '\
                                        <tr>\
                                            <td>'+ ++id +'</td>\
                                            <td>'+ new Date(row['date_received']).toUTCString() +'</td>\
                                            <td>'+ row['quatity_received'] +'</td>\
                                        </tr>';
                                });

                                let deliveryHistoryHtml='<table class="deliveryHistoryTable">\
                                        <thead>\
                                            <tr>\
                                                <th>#</th>\
                                                <th>Date Received</th>\
                                                <th>Quantity Received</th>\
                                            </tr>\
                                        </thead>\
                                        <tbody>' + rows + '</tbody></table>';


                                BootstrapDialog.alert({
                                    title: "<strong>Delivery History</strong>",
                                    type: BootstrapDialog.TYPE_PRIMARY,
                                    message: deliveryHistoryHtml
                                });
                            } else {
                                BootstrapDialog.alert({
                                    title: "<strong>No Delivery History</strong>",
                                    type: BootstrapDialog.TYPE_INFO,
                                    message: "No delivery history found."
                                });
                            }
                        }, 'JSON');
                    }
                });
            }
        }

        var script = new script;
        script.initialize();
    </script>
</body>

</html>