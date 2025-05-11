<?php
// Start the session
session_start();

if (!isset($_SESSION['user']))
    header("location:login.php");

$_SESSION['table'] = 'suppliers';
$show_table = 'suppliers';
$suppliers = include 'database/showAll.php';
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

            <!-- Main content section -->
            <div class="dashboardContent">
                <div class="dashboardContentMain">
                    <div class="rowInfo">
                        <div class="column column-12">
                            <h1><i class="fa-solid fa-users"></i> List of Purchased Orders</h1>
                            <div class="userListContent">
                                <div class="poListContainers">
                                    <div class="poList">
                                        <p>Batch #: 4646</p>
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Product</th>
                                                    <th>Quantity Ordered</th>
                                                    <th>Supplier</th>
                                                    <th>Status</th>
                                                    <th>Ordered By</th>
                                                    <th>Created Date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>Product</td>
                                                    <td>Quantity Ordered</td>
                                                    <td>Supplier</td>
                                                    <td>Status</td>
                                                    <td>Ordered By</td>
                                                    <td>Created Date</td>
                                                    <td>Action</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="poOrderButtonContainer alignRight">
                                            <button class="button updatePoButton">Update</button>
                                        </div>
                                    </div>
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
            }
        }

        var script = new script;
        script.initialize();
    </script>
</body>

</html>