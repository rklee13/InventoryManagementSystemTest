<?php
// Start the session
session_start();

if (!isset($_SESSION['user']))
    header("location:login.php");

$_SESSION['table'] = 'UserLoginInformation';
$user = $_SESSION['user'];

$users = include 'database/showusers.php';

?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard - Inventory Management System</title>
    <link rel="stylesheet" href="stylesheet/dashboard.css" />
    <script src="https://kit.fontawesome.com/3a3f98ed32.js" crossorigin="anonymous"></script>
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
                        <div class="column column-5">
                            <h1><i class="fa-solid fa-user-plus"></i> Create User</h1>
                            <div id="userAddFormContainer">
                                <form action="database/adduser.php" method="POST" class="appForm">
                                    <div class="appFormInputContainer">
                                        <label for="first_name">First Name:</label>
                                        <input type="text" id="first_name" name="first_name" class="appFormInput" />
                                    </div>
                                    <div class="appFormInputContainer">
                                        <label for="last_name">Last Name:</label>
                                        <input type="text" id="last_name" name="last_name" class="appFormInput" />
                                    </div>
                                    <div class="appFormInputContainer">
                                        <label for="email">Email:</label>
                                        <input type="email" id="email" name="email" class="appFormInput" />
                                    </div>
                                    <div class="appFormInputContainer">
                                        <label for="password">Password:</label>
                                        <input type="password" id="password" name="password" class="appFormInput" />
                                    </div>
                                    <button type="Submit" class="addUserButton"><i class="fa-solid fa-plus"></i> Add
                                        User</button>
                                </form>
                            </div>
                        </div>
                        <div class="column column-7">
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
                                                    <td><?= $user['first_name'] ?></td>
                                                    <td><?= $user['last_name'] ?></td>
                                                    <td><?= $user['email'] ?></td>
                                                    <td><?= date('M d, Y h:i:s A e', strtotime($user['created_at'])) ?></td>
                                                    <td><?= date('M d, Y h:i:s A e', strtotime($user['updated_at'])) ?></td>
                                                    <td>
                                                        <a href="" id="editUserButton" class="editUserButton"><i
                                                                class="fa-solid fa-pencil"></i> Edit</a>
                                                        <a href="" id="deleteUserButton" data-userid="<?= $user['id'] ?>"
                                                            data-fname="<?= $user['first_name'] ?>"
                                                            data-lname="<?= $user['last_name'] ?>"
                                                            class="deleteUserButton"><i class="fa-solid fa-trash"></i>
                                                            Delete</a>
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
        </div>
    </div>

    <script src="scripts/script.js"></script>
    <script src="scripts/jquery-3.7.1.min.js"></script>
    <script>
        function script() {
            this.initialize = function () {
                this.registerEvents();
            },

                this.registerEvents = function () {
                    document.addEventListener('click', function (e) {
                        const targetElement = e.target;
                        const targetElememtId = targetElement.id;

                        if (targetElememtId === 'deleteUserButton') {
                            e.preventDefault(); // This prevents the automatic page refresh from the <a> element

                            // TODO: NEED TO DETERMINE A BETTER WAY TO DO THIS
                            const userId = targetElement.dataset.userid;
                            const fname = targetElement.dataset.fname;
                            const lname = targetElement.dataset.lname;
                            const fullName = fname + ' ' + lname;

                            if (window.confirm('Are you sure you want to delete ' + fullName + '?')) {
                                $.ajax({
                                    method:"POST",
                                    data: {
                                        user_id: userId,
                                        first_name:fname,
                                        last_name:lname,
                                    },
                                    url:'./database/deleteuser.php',
                                    dataType: 'JSON',
                                    success: function(data) {
                                        if (data.success) {
                                            if(window.confirm(data.message)) {
                                                location.reload();
                                            }
                                        } else {
                                            window.alert(data.message);
                                        }
                                    }
                                })
                            }
                        } else if (targetElememtId === 'editUserButton') {
                            console.log("edit");
                        }
                    });
                }
        }

        var script = new script;
        script.initialize();
    </script>
</body>

</html>