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
            <?php if (in_array('purchaseOrder_create', $user['permissions'])) { ?>
                <!-- Main content section -->
                <div class="dashboardContent">
                    <div class="dashboardContentMain">
                        <div class="rowInfo">
                            <div class="column column-12">
                                <h1><i class="fa-solid fa-user-plus"></i> Order Product</h1>
                                <div id="userAddFormContainer">
                                    <form action="database/saveOrder.php" method="POST">
                                        <div class="alignRight">
                                            <!-- Only type submit should trigger the form action -->
                                            <button type="button" id="orderProductsButton"
                                                class="orderButton orderProductsButton">Add
                                                Another Product</button>
                                        </div>
                                        <div id="orderProductLists">
                                            <p id="noProductData" style="color: #9f9f9f;">No products selected.</p>
                                            <!-- <div class="orderProductRow">
                                                <div>
                                                    <label for="product_name">PRODUCT NAME: </label>
                                                    <select id="product_name" name="product_name"></select>
                                                </div>
                                            </div> -->
                                        </div>
                                        <div class="alignRight marginTop20">
                                            <button type="submit" id="submitOrderProductsButton"
                                                class="orderButton submitOrderProductsButton">Submit Order</button>
                                        </div>
                                    </form>
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
                </div>
            <?php } else { ?>
                <div id="accessDeniedErrorMessage">Access denied.</div>
            <?php } ?>
        </div>
    </div>
    <?php include('partials/app-scripts.php') ?>
    <script>
        var products = <?= $products ?>;
        var counter = 0;

        function script() {
            var vm = this;

            let productOptions = '\
                <div>\
                    <label for="product_name_select">PRODUCT NAME: </label>\
                    <select id="product_name_select" name="products[]">\
                        <option value="">Select Product</option>\
                        INSERTPRODUCTHERE\
                    </select>\
                    <button id="orderRemoveButton" class="button orderRemoveButton"> Remove</button>\
                </div>';

            this.initialize = function () {
                this.registerEvents();
                this.renderProductOptions();
            }

            this.getSupplierRowId = function (count) {
                return 'suppliersRow_' + count;
            }

            this.renderProductOptions = function () {
                let optionHtml = '';

                products.forEach((product) => {
                    optionHtml += '<option value="' + product.id + '">' + product.product_name + '</option>';
                    //selectId.options.add(new Option(product.product_name,product.id));
                });

                // Append to container
                productOptions = productOptions.replace('INSERTPRODUCTHERE', optionHtml);
            }

            this.renderSupplierRow = function (supplierDetails, counterId, productId) {
                let supplierRows = '';

                supplierDetails.forEach((supplier) => {
                    supplierRows += '\
                        <div class="suppliersRows">\
                            <div class="rowInfo">\
                                <div style="width: 50%;">\
                                    <p class="supplierRowName">'+ supplier.supplier_name + '</p>\
                                </div>\
                                <div style="width: 50%;">\
                                    <label for="quantityOrder">Quantity: </label>\
                                    <input type="number" id="quantityOrder" name="quantity['+ productId + '][' + supplier.id + ']" class="appFormInput orderProductQty" placeholder="Enter quantity" />\
                                </div>\
                            </div>\
                        </div>';
                });

                // Append to container
                let supplierRowContainer = document.getElementById(this.getSupplierRowId(counterId));
                supplierRowContainer.innerHTML = supplierRows;
            }

            this.registerEvents = function () {

                document.addEventListener('click', function (e) {
                    const targetElement = e.target;
                    const targetElementId = targetElement.id;

                    // Add a new product order event
                    if (targetElementId === 'orderProductsButton') {
                        //e.preventDefault(); // This prevents the automatic page refresh from the <a> element

                        let orderProductListsContainer = document.getElementById('orderProductLists');
                        if (orderProductListsContainer) {
                            document.getElementById('noProductData').style.display='none';

                            counter++;
                            const supplierRowId = vm.getSupplierRowId(counter);

                            // Create and append rows to not clear out values when we add a new row
                            // Create the outer div container
                            const divContainerElement = document.createElement('div')
                            divContainerElement.className = 'orderProductRow';
                            divContainerElement.innerHTML = productOptions;

                            // Create the inner div for the supplier row
                            const divSupplierRowElement = document.createElement('div');
                            divSupplierRowElement.id = vm.getSupplierRowId(counter);
                            divSupplierRowElement.dataset.counter = counter;
                            divSupplierRowElement.className = 'suppliersRows';
                            divContainerElement.appendChild(divSupplierRowElement);
                            orderProductListsContainer.appendChild(divContainerElement);

                            // const supplierRowHtml='\
                            //     <div class="orderProductRow">\
                            //         '+productOptions+'\
                            //         <div class="suppliersRows" id="'+supplierRowId+'" data-counter="'+counter+'"></div>\
                            //     </div>';
                            // orderProductListsContainter.innerHTML += '\
                            //     <div class="orderProductRow">\
                            //         '+productOptions+'\
                            //         <div class="suppliersRows" id="'+supplierRowId+'" data-counter="'+counter+'"></div>\
                            //     </div>';
                        }

                    } else if (targetElementId === 'orderRemoveButton') {
                        //e.preventDefault(); // This prevents the automatic page refresh from the <a> element and loading
                        let orderRow = targetElement.closest('div.orderProductRow');

                        // Remove the element
                        orderRow.remove();
                        
                        // Show the default string
                        let orderProductListsContainer = document.getElementById('orderProductLists');
                        if (orderProductListsContainer && orderProductListsContainer.getElementsByClassName('orderProductRow').length === 0) {
                            document.getElementById('noProductData').style.display='';
                            console.log(document.getElementById('noProductData').style.display);
                        }

                    } else if (targetElementId === 'submitOrderProductsButton') {
                        //e.preventDefault(); // This prevents the automatic page refresh from the <a> element and loading
                    }
                });

                document.addEventListener('change', function (e) {
                    const targetElement = e.target;
                    const targetElementId = targetElement.id;

                    // Add supplier rows on product option change
                    if (targetElementId === 'product_name_select') {
                        //e.preventDefault(); // This prevents the automatic page refresh from the <a> element

                        let productId = targetElement.value;
                        let counterId = targetElement.closest('div.orderProductRow').querySelector('.suppliersRows').dataset.counter;

                        if (productId.length > 0) {
                            $.get('database/getSupplierFromProduct.php', { id: productId }, function (supplierDetails) {
                                vm.renderSupplierRow(supplierDetails, counterId, productId);
                            }, 'json');
                        } else {
                            vm.renderSupplierRow([], counterId);
                        }
                    }
                });
            }
        }

        (new script()).initialize();
    </script>
</body>

</html>