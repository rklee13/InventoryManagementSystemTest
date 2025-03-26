<?php
// Start the session
session_start();

if (!isset($_SESSION['user']))
    header("location:login.php");

$_SESSION['table'] = 'UserLoginInformation';
$user = $_SESSION['user'];
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
            <div class="dashboardContent">
                <div class="dashboardContentMain" id="userAddFormContainer">
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
                        <button type="Submit" class="addUserButton"><i class="fa-solid fa-plus"></i> Add User</button>
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

    <script src="scripts/script.js"></script>
</body>

</html>