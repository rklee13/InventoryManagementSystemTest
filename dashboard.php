<?php
// Start the session
session_start();

if (!isset($_SESSION['user']))
  header("location:login.php");

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
      <?php include'partials/app-topnav.php' ?>
      <div class="dashboardContent">
        <div class="dashboardContentMain"></div>
      </div>
    </div>
  </div>

  <script src="scripts/script.js"></script>
</body>
</html>