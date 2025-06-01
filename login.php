<?php
// Start the session
session_start();

if (isset($_SESSION['user'])) header("location:dashboard.php");

$error_message='';

if ($_SERVER['REQUEST_METHOD']=='POST') {
    include "./database/connection.php";

    $email = $_POST['email'];
    $password = $_POST['password'];

    $query="SELECT * FROM `UserLoginInformation` WHERE email='$email' AND password='$password'";
    $stmt = $connection->prepare($query);
    $result=$stmt->execute();

    if ($result && $stmt->rowCount() > 0) {
        // Fetches all the associated information
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $usersInfo = $stmt->fetchAll();

        $user_exist = false;
        foreach($usersInfo as $user) {
            if ($password === $user['password']) {
                $user_exist = true;
                $user['permissions'] = explode(',', $user['permissions']);

                // This saves the session so once a user comes back, they don't have to login again
                $_SESSION['user']=$user;
                break;
            }
        }

        if ($user_exist) {
            header('location:dashboard.php');
        } else {
            $error_message="Invalid user information. Verify the login information is correct, and then please try again.";    
        }

    } else {
        $error_message="Invalid user information. Verify the login information is correct, and then please try again.";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>IMS Login - Inventory Management System</title>

    <link rel="stylesheet" href="stylesheet/login.css">
</head>

<body id="loginBody">
    <?php if ($error_message): ?>
        <div id="errorMessage">
            <strong>ERROR: </strong><p><?= $error_message?></p>
        </div>
    <?php endif; ?>
    <div class="container">
        <div class="loginHeader">
            <h1>IMS</h1>
            <p>Inventory Management System</p>
        </div>

        <div class="loginBodyContainer">
            <form action="login.php" method="post">
                <div class="loginInputContainer">
                    <label for="emailInput">Email:</label>
                    <input id="emailInput" type="email" placeholder="Enter your email" name="email"/>
                </div>

                <div class="loginInputContainer">
                    <label for="passwordInput">Password:</label>
                    <input id="passwordInput" type="password" placeholder="Enter your password" name="password"/>
                </div>

                <div class="loginButtonContainer">
                    <button class="loginButton">Login</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>