<?php
// Start the session
session_start();

if (!isset($_SESSION['user']))
    header("location:login.php");

$_SESSION['table'] = 'UserLoginInformation';
$show_table = 'UserLoginInformation';
$user = $_SESSION['user'];
$user_permissions=$user['permissions'];

$users = include 'database/showAll.php';
?>

<!DOCTYPE html>
<html>

<head>
    <title>View Users - Inventory Management System</title>
    <?php include('partials/app-headers-script.php'); ?>
</head>

<body>
    <div id="dashboardContainer">
        <!-- Sidebar -->
        <?php include 'partials/app-sidebar.php' ?>
        <div class="dashboardContentContainer" id="dashboardContentContainer">
            <!-- Top Navigator bars -->
            <?php include 'partials/app-topnav.php' ?>
            <?php if (in_array('users_view', $user['permissions'])) { ?>
                <!-- Main content section -->
                <div class="dashboardContent">
                    <div class="dashboardContentMain">
                        <div class="rowInfo">
                            <div class="column column-12">
                                <h1><i class="fa-solid fa-users"></i> List of Current Users</h1>
                                <div class="userListContent">
                                    <div class="users">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>First Name</th>
                                                    <th>Last Name</th>
                                                    <th>Email</th>
                                                    <th>Created At</th>
                                                    <th>Updated At</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($users as $index => $user): ?>
                                                    <tr>
                                                        <td><?= $index + 1 ?></td>
                                                        <td id="firstName"><?= $user['first_name'] ?></td>
                                                        <td id="lastName"><?= $user['last_name'] ?></td>
                                                        <td id="email"><?= $user['email'] ?></td>
                                                        <td><?= date('M d, Y h:i:s A e', strtotime($user['created_at'])) ?></td>
                                                        <td><?= date('M d, Y h:i:s A e', strtotime($user['updated_at'])) ?></td>
                                                        <td>
                                                            <a href="" id="editUserButton" data-userid="<?= $user['id'] ?>"
                                                                class="<?= in_array("users_edit", $user_permissions) ? 'editUserButton' : 'accessDeniedError' ?>">
                                                                <i class="fa-solid fa-pencil"></i>
                                                                Edit</a>
                                                            <a href="" id="deleteUserButton" data-userid="<?= $user['id'] ?>"
                                                                class="<?= in_array("users_delete", $user_permissions) ? 'deleteUserButton' : 'accessDeniedError' ?>"
                                                                data-fname="<?= $user['first_name'] ?>"
                                                                data-lname="<?= $user['last_name'] ?>">
                                                                <i class="fa-solid fa-trash"></i>
                                                                Delete</a>
                                                            <input type="hidden" id="cur_permissions_<?= $user['id'] ?>"
                                                                value="<?= $user['permissions'] ?>">
                                                        </td>
                                                    </tr>
                                                <?php endforeach ?>
                                            </tbody>
                                        </table>
                                        <p class="totalUserCount">Total Users: <?= count($users) ?></p>
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

    <?php include('partials/app-scripts.php') ?>
    <script>
        function script() {
            this.allowedPermissionList = [];
            this.permissionElement = '\
                <div id="permissions">\
                    <h4>Permissions</h4>\
                    <hr>\
                    <div class="permissionsContainer">\
                        <div class="permission">\
                            <div class="row">\
                                <div class="col-md-3">\
                                    <p class="moduleName">Dashboard</p>\
                                </div>\
                                <div class="col-md-2">\
                                    <p class="moduleFunction" data-value="dashboard_view">View</p>\
                                </div>\
                            </div>\
                        </div>\
                        <div class="permission">\
                            <div class="row">\
                                <div class="col-md-3">\
                                    <p class="moduleName">Reports</p>\
                                </div>\
                                <div class="col-md-2">\
                                    <p class="moduleFunction" data-value="report_view">View</p>\
                                </div>\
                            </div>\
                        </div>\
                        <div class="permission">\
                            <div class="row">\
                                <div class="col-md-3">\
                                    <p class="moduleName">Purchase Order</p>\
                                </div>\
                                <div class="col-md-2">\
                                    <p class="moduleFunction" data-value="purchaseOrder_view">View</p>\
                                </div>\
                                <div class="col-md-2">\
                                    <p class="moduleFunction" data-value="purchaseOrder_create">Create</p>\
                                </div>\
                                <div class="col-md-2">\
                                    <p class="moduleFunction" data-value="purchaseOrder_edit">Edit</p>\
                                </div>\
                            </div>\
                        </div>\
                        <div class="permission">\
                            <div class="row">\
                                <div class="col-md-3">\
                                    <p class="moduleName">Product</p>\
                                </div>\
                                <div class="col-md-2">\
                                    <p class="moduleFunction" data-value="product_view">View</p>\
                                </div>\
                                <div class="col-md-2">\
                                    <p class="moduleFunction" data-value="product_create">Create</p>\
                                </div>\
                                <div class="col-md-2">\
                                    <p class="moduleFunction" data-value="product_edit">Edit</p>\
                                </div>\
                                <div class="col-md-2">\
                                    <p class="moduleFunction" data-value="product_delete">Delete</p>\
                                </div>\
                            </div>\
                        </div>\
                        <div class="permission">\
                            <div class="row">\
                                <div class="col-md-3">\
                                    <p class="moduleName">Supplier</p>\
                                </div>\
                                <div class="col-md-2">\
                                    <p class="moduleFunction" data-value="supplier_view">View</p>\
                                </div>\
                                <div class="col-md-2">\
                                    <p class="moduleFunction" data-value="supplier_create">Create</p>\
                                </div>\
                                <div class="col-md-2">\
                                    <p class="moduleFunction" data-value="supplier_edit">Edit</p>\
                                </div>\
                                <div class="col-md-2">\
                                    <p class="moduleFunction" data-value="supplier_delete">Delete</p>\
                                </div>\
                            </div>\
                        </div>\
                        <div class="permission">\
                            <div class="row">\
                                <div class="col-md-3">\
                                    <p class="moduleName">Users</p>\
                                </div>\
                                <div class="col-md-2">\
                                    <p class="moduleFunction" data-value="users_view">View</p>\
                                </div>\
                                <div class="col-md-2">\
                                    <p class="moduleFunction" data-value="users_create">Create</p>\
                                </div>\
                                <div class="col-md-2">\
                                    <p class="moduleFunction" data-value="users_edit">Edit</p>\
                                </div>\
                                <div class="col-md-2">\
                                    <p class="moduleFunction" data-value="users_delete">Delete</p>\
                                </div>\
                            </div>\
                        </div>\
                        <div class="permission">\
                            <div class="row">\
                                <div class="col-md-3">\
                                    <p class="moduleName">Point of Sale</p>\
                                </div>\
                                <div class="col-md-2">\
                                    <p class="moduleFunction" data-value="pointOfSale_access">Has Access</p>\
                                </div>\
                            </div>\
                        </div>\
                    </div>\
                </div>';

            this.initialize = function () {
                this.registerEvents();
            },

                this.registerEvents = function () {
                    document.addEventListener('click', function (e) {
                        const targetElement = e.target;
                        const targetElememtId = targetElement.id;
                        const classList = targetElement.classList;

                        if (!classList.contains('accessDeniedError')) {
                            if (targetElememtId === 'deleteUserButton') {
                                e.preventDefault(); // This prevents the automatic page refresh from the <a> element

                                const userId = targetElement.dataset.userid;
                                const fname = targetElement.dataset.fname;
                                const lname = targetElement.dataset.lname;
                                const fullName = fname + ' ' + lname;

                                BootstrapDialog.confirm({
                                    type: BootstrapDialog.TYPE_DANGER,
                                    title: 'Delete User',
                                    message: 'Are you sure you want to delete <strong>' + fullName + '</strong>?',
                                    callback: function (isDelete) {
                                        if (isDelete) {
                                            $.ajax({
                                                method: "POST",
                                                data: {
                                                    id: userId,
                                                    name: fullName,
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
                            } else if (targetElememtId === 'editUserButton') {
                                e.preventDefault(); // This prevents the automatic page refresh from the <a> element and loading

                                // Get the data
                                const userId = targetElement.dataset.userid;
                                const firstName = targetElement.closest('tr').querySelector("#firstName").textContent;
                                const lastName = targetElement.closest('tr').querySelector("#lastName").textContent;
                                const email = targetElement.closest('tr').querySelector("#email").textContent;
                                const fullName = firstName + ' ' + lastName;

                                const permissions = document.getElementById('cur_permissions_' + userId).value;

                                BootstrapDialog.confirm({
                                    title: 'Update ' + fullName,
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
                                </div>' + script.permissionElement + '\
                                <input type="hidden" name="permissions" id="permissionsInput" value="'+ permissions + '">\
                                </form>',
                                    callback: function (isUpdate) {
                                        if (isUpdate) { // if user clicked "Ok" button
                                            $.ajax({
                                                method: "POST",
                                                data: {
                                                    id: userId,
                                                    name: fullName,
                                                    first_name: document.getElementById("firstNameUpdate").value,
                                                    last_name: document.getElementById("lastNameUpdate").value,
                                                    email: document.getElementById("emailUpdate").value,
                                                    permissions: document.getElementById("permissionsInput").value,
                                                },
                                                url: './database/update.php',
                                                dataType: 'JSON',
                                                success: function (data) {
                                                    BootstrapDialog.alert({
                                                        type: data.success ? BootstrapDialog.TYPE_SUCCESS : BootstrapDialog.TYPE_DANGER,
                                                        message: data.message,
                                                        callback: function () {
                                                            location.reload();
                                                        }
                                                    });
                                                }
                                            });
                                        }
                                    },
                                    onshown: function () {
                                        // Get each permissions
                                        const currentPermissions = permissions.split(',');
                                        script.allowedPermissionList = [];  // Clear it

                                        // Loop through each permission and apply the active styling - i.e. selection
                                        currentPermissions?.forEach(permission => {
                                            if (permission !== '') {
                                                let permissionTargetElement = document.querySelector("[data-value='" + permission + "'");
                                                if (permissionTargetElement != null) {
                                                    permissionTargetElement.classList.add('permissionActive');
                                                    script.allowedPermissionList.push(permission);
                                                }
                                            }
                                        });
                                    }
                                });
                            } else if (targetElement.classList.contains('moduleFunction')) {
                                // Get value
                                const permissionName = targetElement.dataset.value;

                                // Set the active class & store/remove permissions
                                if (targetElement.classList.contains('permissionActive')) {
                                    targetElement.classList.remove('permissionActive');

                                    // Remove this from the permission array
                                    script.allowedPermissionList = script.allowedPermissionList.filter((name) => {
                                        return name !== permissionName;
                                    });

                                } else {
                                    targetElement.classList.add('permissionActive');
                                    script.allowedPermissionList.push(permissionName);
                                }

                                // Update the hidden element
                                document.getElementById('permissionsInput').value = script.allowedPermissionList.join(',');
                            }
                        }
                    });
                }
        }

        var script = new script;
        script.initialize();
    </script>
</body>

</html>