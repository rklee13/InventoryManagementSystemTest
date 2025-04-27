<?php
// Start the session
session_start();

if (!isset($_SESSION['user']))
    header("location:login.php");

$_SESSION['table'] = 'products';
$show_table = 'products';
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
                    <div class="rowInfo">
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
                                                <th>Suppliers</th>
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
                                                        <img class="productImages"
                                                            src="uploads/products/<?= $product['image'] ?>" />
                                                    </td>
                                                    <td id="productName"><?= $product['product_name'] ?></td>
                                                    <td id="description"><?= $product['description'] ?></td>
                                                    <td id="suppliersList">
                                                        <?php
                                                        $productId = $product['id'];
                                                        $stmt = $connection->prepare("SELECT supplier_name FROM suppliers,productSupplier WHERE productSupplier.product=$productId AND productSupplier.supplier=suppliers.id");
                                                        $stmt->execute();
                                                        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                                        $supplier_array = array_column($row, 'supplier_name');
                                                        $supplier_list = $supplier_array && count($supplier_array) > 0 ?
                                                            "<ul><li>" . implode("</li><li>", $supplier_array) . "</li></ul>" : "Not Set";
                                                        echo $supplier_list;
                                                        ?>
                                                    </td>
                                                    <td id="createdBy"">
                                                        <?php
                                                        $productId = $product['created_by'];
                                                        $stmt = $connection->prepare("SELECT * FROM UserLoginInformation WHERE id=$productId");
                                                        $stmt->execute();
                                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);

                                                        $created_by = $row['first_name'] . ' ' . $row['last_name'];
                                                        echo $created_by;
                                                        ?>
                                                    </td>
                                                    <td><?= date('M d, Y h:i:s A e', strtotime($product['created_at'])) ?></td>
                                                    <td><?= date('M d, Y h:i:s A e', strtotime($product['updated_at'])) ?></td>
                                                    <td>
                                                        <a href="" id="editProductButton"
                                                        data-productid="<?= $product['id'] ?>" class="editButton"><i
                                                            class="fa-solid fa-pencil"></i>
                                                        Edit</a> |
                                                        <a href="" id="deleteProductButton"
                                                            data-productid="<?= $product['id'] ?>"
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

    <?php
        include('partials/app-scripts.php');

        $show_table = 'suppliers';
        $suppliers = include('database/showAll.php');
        $suppliers_array=[];
        foreach ($suppliers as $supplier) {
            $suppliers_array[$supplier['id']]=$supplier['supplier_name'];
        }

        if ($suppliers_array && count($suppliers_array)>0) {
            $suppliers_array=json_encode($suppliers_array);
        }
    ?>
    <script>
        var suppliersList = <?= $suppliers_array ?>;

        function script() {
            this.initialize = function () {
                this.registerEvents();
            }
            var vm = this;

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
                        const productId = targetElement.dataset.productid;
                        vm.showEditDialog(productId);
                    }
                });
            }

            this.showEditDialog = function (id) {
                $.get('database/getProduct.php', { id: id }, function (productDetails) {
                    let currentSuppliers = productDetails['suppliers'];
                    let suppliersOption = '';

                    for (const [supplierId, supplierName] of Object.entries(suppliersList)) {
                        let selected = currentSuppliers.includes(parseInt(supplierId)) ? 'selected' : '';
                        suppliersOption +="<option "+ selected +" value='"+supplierId+"'>"+supplierName+"</option>";
                    }

                    BootstrapDialog.confirm({
                        title: 'Update <strong>' + productDetails.product_name + '</strong>',
                        message: '<form id="editDialogForm" method="POST" class="appForm" enctype="multipart/form-data">\
                                <input type="hidden" name="id" value="'+ id + '"/>\
                                <div class="appFormInputContainer">\
                                    <label for="product_name">Product Name:</label>\
                                    <input type="text" id="product_name" name="product_name" class="appFormInput" placeholder="Enter product name" value="'+ productDetails.product_name + '"/>\
                                </div>\
                                <div class="appFormInputContainer">\
                                    <label for="product_image">Product Image:</label>\
                                    <input type="file" id="product_image" name="image" value="'+ productDetails.image + '"/>\
                                </div>\
                                <div class="appFormInputContainer">\
                                    <label for="description">Description:</label>\
                                    <textarea id="description" name="description" class="appFormInput" placeholder="Enter product description">'+ productDetails.description + '</textarea>\
                                </div>\
                                <div class="appFormInputContainer">\
                                        <label for="suppliers">Suppliers:</label>\
                                         <select name="suppliers[]" id="suppliersSelect" class="appFormInput" multiple>\
                                         '+ suppliersOption +'\
                                         </select>\
                                    </div>\
                                </form>\
                                ',
                        callback: function (isUpdate) {
                            if (isUpdate) { // if user clicked "Ok" button
                                $.ajax({
                                    method: "POST",
                                    data: new FormData(document.getElementById('editDialogForm')),
                                    url: './database/update.php',
                                    processData: false,
                                    contentType: false,
                                    dataType: 'JSON',
                                    success: function (data) {
                                        BootstrapDialog.alert({
                                            type: data.success ? BootstrapDialog.TYPE_SUCCESS : BootstrapDialog.TYPE_DANGER,
                                            message: data.message,
                                            callback: function () {
                                                if (data.success) {
                                                    location.reload();
                                                }
                                            }
                                        });
                                    }
                                });
                            }
                        }
                    });
                }, 'json');

            }
        }

        var script = new script;
        script.initialize();
    </script>
</body>

</html>