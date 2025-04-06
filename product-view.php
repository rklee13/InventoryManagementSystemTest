<?php
// Start the session
session_start();

if (!isset($_SESSION['user']))
    header("location:login.php");

$_SESSION['table'] = 'products';
$products = include 'database/showAll.php';
?>

<!DOCTYPE html>
<html>

<head>
    <title>View Products - Inventory Management System</title>
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
                            <h1><i class="fa-solid fa-users"></i> List of Current Products</h1>
                            <div class="userListContent">
                                <div class="users">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Image</th>
                                                <th>Product Name</th>
                                                <th>Description</th>
                                                <th>Created By</th>
                                                <th>Created At</th>
                                                <th>Updated At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($products as $index => $product): ?>
                                                <tr>
                                                    <td><?= $index + 1 ?></td>
                                                    <td id="image">
                                                        <img class="productImages" src="uploads/products/<?= $product['image'] ?>" />
                                                    </td>
                                                    <td id="productName"><?= $product['product_name'] ?></td>
                                                    <td id="description"><?= $product['description'] ?></td>
                                                    <td id="createdBy""><?= $product['created_by']?></td>
                                                    <td><?= date('M d, Y h:i:s A e', strtotime($product['created_at'])) ?></td>
                                                    <td><?= date('M d, Y h:i:s A e', strtotime($product['updated_at'])) ?></td>
                                                    <td>
                                                        <a href="" id="editButton" data-userid="<?= $product['id'] ?>"
                                                            class="editButton"><i class="fa-solid fa-pencil"></i>
                                                            Edit</a>
                                                        <a href="" id="deleteButton" data-userid="<?= $product['id'] ?>"
                                                            data-pname="<?= $product['product_name'] ?>"
                                                            class="deleteButton"><i class="fa-solid fa-trash"></i>
                                                            Delete</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                    <p class="totalUserCount">Total Products: <?= count($products) ?></p>
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

    <?php include('partials/app-scripts.php')?>
    <script>
    </script>
</body>

</html>