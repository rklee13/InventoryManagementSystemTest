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
    <title>View Suppliers - Inventory Management System</title>
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
                            <h1><i class="fa-solid fa-users"></i> List of Current Suppliers</h1>
                            <div class="userListContent">
                                <div class="users">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Location</th>
                                                <th>Contact Details</th>
                                                <th>Products</th>
                                                <th>Created By</th>
                                                <th>Created At</th>
                                                <th>Updated At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($suppliers as $index => $supplier): ?>
                                                <tr>
                                                    <td><?= $index + 1 ?></td>
                                                    <td id="supplierName"><?= $supplier['supplier_name'] ?></td>
                                                    <td id="supplierLocation"><?= $supplier['supplier_location'] ?></td>
                                                    <td id="supplierEmail"><?= $supplier['email'] ?></td>
                                                    <td id="productsList">
                                                        <?php
                                                            $supplierId = $supplier['id'];
                                                            $stmt = $connection->prepare("SELECT product_name FROM products,productSupplier WHERE productSupplier.supplier=$supplierId AND productSupplier.product=products.id");
                                                            $stmt->execute();
                                                            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                                            $product_array = array_column($row, 'product_name');
                                                            $product_list = $product_array && count($product_array) > 0 ?
                                                                "<ul><li>" . implode("</li><li>", $product_array) . "</li></ul>" : "Not Set";
                                                            echo $product_list;
                                                            ?>
                                                        </td>
                                                    <td id="createdBy"">
                                                        <?php
                                                        $userId = $supplier['created_by'];
                                                        $stmt = $connection->prepare("SELECT * FROM UserLoginInformation WHERE id=$userId");
                                                        $stmt->execute();
                                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);

                                                        $created_by = $row['first_name'] . ' ' . $row['last_name'];
                                                        echo $created_by;
                                                        ?>
                                                    </td>
                                                    <td><?= date('M d, Y h:i:s A e', strtotime($supplier['created_at'])) ?></td>
                                                    <td><?= date('M d, Y h:i:s A e', strtotime($supplier['updated_at'])) ?></td>
                                                    <td>
                                                        <a href="" id="editSupplierButton"
                                                        data-supplierid="<?= $supplier['id'] ?>" class="editButton"><i
                                                            class="fa-solid fa-pencil"></i>
                                                        Edit</a> |
                                                        <a href="" id="deleteSupplierButton"
                                                            data-supplierid="<?= $supplier['id'] ?>"
                                                            data-suppliername="<?= $supplier['supplier_name'] ?>"
                                                            class="deleteButton"><i class="fa-solid fa-trash"></i>
                                                            Delete</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                    <p class="totalUserCount">Total Suppliers: <?= count($suppliers) ?></p>
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

                    if (targetElememtId === 'deleteSupplierButton') {
                        e.preventDefault(); // This prevents the automatic page refresh from the <a> element

                        const supplierId = targetElement.dataset.supplierid;
                        const supplierName = targetElement.dataset.suppliername;
                        
                        BootstrapDialog.confirm({
                            type: BootstrapDialog.TYPE_DANGER,
                            title: 'Delete Supplier',
                            message: 'Are you sure you want to delete <strong>' + supplierName + '</strong>?',
                            callback: function (isDelete) {
                                if (isDelete) {
                                    $.ajax({
                                        method: "POST",
                                        data: {
                                            id: supplierId,
                                            name: supplierName,
                                            table: 'suppliers',
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
                    } else if (targetElememtId === 'editSupplierButton') {
                        e.preventDefault(); // This prevents the automatic page refresh from the <a> element and loading

                        // Get the data
                        const supplierId = targetElement.dataset.supplierid;
                        vm.showEditDialog(supplierId);
                    }
                });
            }

            this.showEditDialog = function (id) {
                $.get('database/getProduct.php', { id: id }, function (supplierDetails) {
                    let currentSuppliers = productDetails['suppliers'];
                    let suppliersOption = '';

                    for (const [supplierId, supplierName] of Object.entries(suppliersList)) {
                        let selected = currentSuppliers.includes(parseInt(supplierId)) ? 'selected' : '';
                        suppliersOption +="<option "+ selected +" value='"+supplierId+"'>"+supplierName+"</option>";
                    }

                    BootstrapDialog.confirm({
                        title: 'Update <strong>' + supplierDetails.supplier_name + '</strong>',
                        message: '<form id="editDialogForm" method="POST" class="appForm" enctype="multipart/form-data">\
                                <input type="hidden" name="id" value="'+ id + '"/>\
                                <div class="appFormInputContainer">\
                                    <label for="supplier_name">Product Name:</label>\
                                    <input type="text" id="supplier_name" name="supplier_name" class="appFormInput" placeholder="Enter supplier name" value="'+ supplierDetails.supplier_name + '"/>\
                                </div>\
                                <div class="appFormInputContainer">\
                                    <label for="supplier_location">Product Name:</label>\
                                    <input type="text" id="supplier_location" name="supplier_location" class="appFormInput" placeholder="Enter supplier location" value="'+ supplierDetails.supplier_location + '"/>\
                                </div>\
                                <div class="appFormInputContainer">\
                                    <label for="supplier_email">Description:</label>\
                                    <input type="email" id="supplier_email" name="email" class="appFormInput" placeholder="Enter supplier email" value="'+ supplierDetails.email + '"/>\
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