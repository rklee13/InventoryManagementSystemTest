<?php
// Start the session
session_start();

if (!isset($_SESSION['user']))
    header("location:login.php");

$_SESSION['table'] = 'products';
$_SESSION['redirect_to'] = 'product-add.php';
$user = $_SESSION['user'];

?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Product - Inventory Management System</title>
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
                    <div class="row">
                        <div class="column column-12">
                            <h1><i class="fa-solid fa-user-plus"></i> Create Product</h1>
                            <div id="userAddFormContainer">
                                <!-- enctype=multipart/form-data is needed for input of type files  -->
                                <form action="database/add.php" method="POST" class="appForm" enctype="multipart/form-data">
                                    <div class="appFormInputContainer">
                                        <label for="product_name">Product Name:</label>
                                        <input type="text" id="product_name" name="product_name" class="appFormInput" placeholder="Enter product name"/>
                                    </div>
                                    <div class="appFormInputContainer">
                                        <label for="product_image">Product Image:</label>
                                        <input type="file" id="product_image" name="image"/>
                                    </div>
                                    <div class="appFormInputContainer">
                                        <label for="description">Description:</label>
                                        <textarea id="description" name="description" class="appFormInput" placeholder="Enter product description"></textarea>
                                    </div>
                                    <div class="appFormInputContainer">
                                        <label for="suppliers">Suppliers:</label>
                                         <select name="suppliers[]" id="suppliersSelect" class="appFormInput" multiple>
                                            <?php
                                            $show_table = 'suppliers';
                                            $suppliers = include('database/showAll.php');
                                            foreach($suppliers as $supplier) {
                                                echo "<option value='".$supplier['id']."'>". $supplier['supplier_name'] ."</option>";
                                            }
                                            ?>
                                         </select>
                                    </div>
                                    <button type="Submit" class="addUserButton"><i class="fa-solid fa-plus"></i> Add
                                        Product</button>
                                </form>
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
        </div>
    </div>
    <?php include('partials/app-scripts.php')?>
</body>
</html>