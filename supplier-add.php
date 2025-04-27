<?php
// Start the session
session_start();

if (!isset($_SESSION['user']))
    header("location:login.php");

$_SESSION['table'] = 'suppliers';
$_SESSION['redirect_to'] = 'supplier-add.php';
$user = $_SESSION['user'];

?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Supplier - Inventory Management System</title>
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
                            <h1><i class="fa-solid fa-user-plus"></i> Add Supplier</h1>
                            <div id="userAddFormContainer">
                                <!-- enctype=multipart/form-data is needed for input of type files  -->
                                <form action="database/add.php" method="POST" class="appForm" enctype="multipart/form-data">
                                    <div class="appFormInputContainer">
                                        <label for="supplier_name">Supplier Name:</label>
                                        <input type="text" id="supplier_name" name="supplier_name" class="appFormInput" placeholder="Enter supplier's name"/>
                                    </div>
                                    <div class="appFormInputContainer">
                                        <label for="supplier_location">Location:</label>
                                        <input type="text" id="supplier_location" name="supplier_location" class="appFormInput" placeholder="Enter supplier's location"/>
                                    </div>
                                    <div class="appFormInputContainer">
                                        <label for="supplier_email">Email:</label>
                                        <input type="email" id="supplier_email" name="email" class="appFormInput" placeholder="Enter supplier's email"></input>
                                    </div>
                                    <button type="Submit" class="addUserButton"><i class="fa-solid fa-plus"></i> Add Supplier</button>
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