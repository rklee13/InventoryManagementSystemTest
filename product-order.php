<?php
// Start the session
session_start();

if (!isset($_SESSION['user']))
    header("location:login.php");

$_SESSION['table'] = 'products';
$show_table = 'products';
$products = include 'database/showAll.php';
$products = json_encode($products);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Order Product - Inventory Management System</title>
    <?php include('partials/app-headers-script.php'); ?>
</head>

<body>
    <div id="dashboardContainer">
        <!-- Sidebar -->
        <?php include 'partials/app-sidebar.php' ?>
        <div class="dashboardContentContainer" id="dashboardContentContainer">
            <!-- Top Navigator bars -->
            <?php include 'partials/app-topnav.php' ?>

            <!-- Main content section -->
            <div class="dashboardContent">
                <div class="dashboardContentMain">
                    <div class="rowInfo">
                        <div class="column column-12">
                            <h1><i class="fa-solid fa-user-plus"></i> Order Product</h1>
                            <div id="userAddFormContainer">
                                <!-- enctype=multipart/form-data is needed for input of type files  -->
                                <!-- <form action="" method="POST" class="appForm">
                                     <div class="appFormInputContainer">
                                        <label for="product_name">Product Name:</label>
                                        <input type="text" id="product_name" name="product_name" class="appFormInput" placeholder="Select product"/>
                                    </div>
                                    <div class="appFormInputContainer">
                                        <label for="supplier_name">Supplier Name:</label>
                                        <input type="text" id="supplier_name" name="supplier" class="appFormInput" placeholder="Select supplier"/>
                                    </div>
                                    <div class="appFormInputContainer">
                                        <label for="quantity_order">Quantity Order:</label>
                                        <input type="text" id="quantity_order" name="quantity_order" class="appFormInput" placeholder="Enter Quantity"/>
                                    </div>
                                    <div class="appFormInputContainer">
                                        <label for="suppliers">Suppliers:</label>
                                         <select name="suppliers[]" id="suppliersSelect" class="appFormInput" multiple>
                                         <?php
                                         $show_table = 'suppliers';
                                         $suppliers = include('database/showAll.php');
                                         foreach ($suppliers as $supplier) {
                                             echo "<option value='" . $supplier['id'] . "'>" . $supplier['supplier_name'] . "</option>";
                                         }
                                         ?>
                                         </select>
                                    </div>
                                    <button type="Submit" class="addUserButton"><i class="fa-solid fa-plus"></i> Add New Product Order</button>
                                </form> -->
                                <div>
                                    <div class="alignRight">
                                        <button class="orderButton orderProductsButton">Order Product</button>
                                    </div>
                                    <div id="orderProductLists">
                                        <div class="orderProductRow">
                                            <div>
                                                <label for="product_name">PRODUCT NAME: </label>
                                                <select id="product_name" name="product_name">
                                                    <option value="">Product 1</option>
                                                </select>
                                            </div>

                                            <div class="suppliersRows">
                                                <div class="rowInfo">
                                                    <div style="width: 50%;">
                                                        <p class="supplierRowName">Supplier 1</p>
                                                        <!-- <label for="supplier_name">Supplier Name:</label>
                                                        <select id="supplier_name" name="supplier"
                                                            class="productNameSelect"></select> -->
                                                    </div>
                                                    <div style="width: 50%;">
                                                        <label for="quantityOrder">Quantity: </label>
                                                        <input type="number" id="quantityOrder" name="quantity_ordered"
                                                            class="appFormInput" placeholder="Enter quantity" />
                                                    </div>
                                                </div>
                                                <div class="rowInfo">
                                                    <div style="width: 50%;">
                                                        <p class="supplierRowName">Supplier 2</p>
                                                    </div>
                                                    <div style="width: 50%;">
                                                        <label for="quantityOrder">Quantity: </label>
                                                        <input type="number" id="quantityOrder" name="quantity_ordered"
                                                            class="appFormInput" placeholder="Enter quantity" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="alignRight marginTop20">
                                        <button class="orderButton submitOrderProductsButton">Submit Order</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('partials/app-scripts.php') ?>
    <script>
        var products = <?= $products ?>;

    </script>
</body>

</html>