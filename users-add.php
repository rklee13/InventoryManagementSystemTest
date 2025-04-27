<?php
// Start the session
session_start();

if (!isset($_SESSION['user']))
    header("location:login.php");

$_SESSION['table'] = 'UserLoginInformation';
$user = $_SESSION['user'];
$_SESSION['redirect_to'] = 'users-add.php';
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Users - Inventory Management System</title>
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
                            <h1><i class="fa-solid fa-user-plus"></i> Create User</h1>
                            <div id="userAddFormContainer">
                                <form action="database/add.php" method="POST" class="appForm">
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
                                        <input type="email" id="emailAdd" name="email" class="appFormInput" />
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