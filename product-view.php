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
                                                        <a href="" id="editProductButton" data-productid="<?= $product['id'] ?>"
                                                            class="editButton"><i class="fa-solid fa-pencil"></i>
                                                            Edit</a>
                                                        <a href="" id="deleteProductButton" data-productid="<?= $product['id'] ?>"
                                                            data-productname="<?= $product['product_name'] ?>"
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
        function script() {
            this.initialize = function () {
                this.registerEvents();
            }

            this.registerEvents = function () {
                    document.addEventListener('click', function (e) {
                        const targetElement = e.target;
                        const targetElememtId = targetElement.id;

                        if (targetElememtId === 'deleteProductButton') {
                            e.preventDefault(); // This prevents the automatic page refresh from the <a> element

                            const productId = targetElement.dataset.productid;
                            const productName = targetElement.dataset.productname;

                            BootstrapDialog.confirm({
                                type: BootstrapDialog.TYPE_DANGER,
                                title: 'Delete Product',
                                message: 'Are you sure you want to delete <strong>' + productName + '</strong>?',
                                callback: function (isDelete) {
                                    if (isDelete) {
                                        $.ajax({
                                            method: "POST",
                                            data: {
                                                id: productId,
                                                name: productName,
                                                table: 'products',
                                            },
                                            url: './database/delete.php',
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
                        } else if (targetElememtId === 'editProductButton') {
                            e.preventDefault(); // This prevents the automatic page refresh from the <a> element and loading

                            // Get the data
                            const productId = targetElememt.dataset.productid;

                            const userId = targetElement.dataset.userid;
                            const firstName = targetElement.closest('tr').querySelector("#firstName").textContent;
                            const lastName = targetElement.closest('tr').querySelector("#lastName").textContent;
                            const email = targetElement.closest('tr').querySelector("#email").textContent;


                            BootstrapDialog.confirm({
                                title: 'Update ' + firstName + ' ' + lastName,
                                message: '<form>\
                                <div class="form-group">\
                                <label for="firstName">First Name:</label>\
                                <input type="text" class="form-control" id="firstNameUpdate" value="'+ firstName + '">\
                                </div>\
                                <div class="form-group">\
                                <label for="lastName">Last Name:</label>\
                                <input type="text" class="form-control" id="lastNameUpdate" value="'+ lastName + '">\
                                </div>\
                                <div class="form-group">\
                                <label for="email">email:</label>\
                                <input type="email" class="form-control" id="emailUpdate" value="'+ email + '">\
                                </div>\
                                </form>',
                                callback: function (isUpdate) {
                                    if (isUpdate) { // if user clicked "Ok" button
                                        $.ajax({
                                            method: "POST",
                                            data: {
                                                user_id: userId,
                                                first_name: document.getElementById("firstNameUpdate").value,
                                                last_name: document.getElementById("lastNameUpdate").value,
                                                email: document.getElementById("emailUpdate").value,
                                            },
                                            url: './database/update.php',
                                            dataType: 'JSON',
                                            success: function (data) {
                                                if (data.success) {
                                                    BootstrapDialog.alert({
                                                        type: BootstrapDialog.TYPE_SUCCESS,
                                                        message: data.message,
                                                        callback: function () {
                                                            location.reload();
                                                        }
                                                    });
                                                } else {
                                                    wBootstrapDialog.alert({
                                                        type: BootstrapDialog.TYPE_DANGER,
                                                        message: data.message,
                                                        callback: function () {
                                                            location.reload();
                                                        }
                                                    });
                                                }
                                            }
                                        });
                                    }
                                }
                            });
                        }
                    });
                }
        }

        var script = new script;
        script.initialize();
    </script>
</body>

</html>