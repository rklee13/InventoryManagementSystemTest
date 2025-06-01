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
            <?php include 'partials/app-topnav.php' ?>
            <div class="dashboardContent">
                <div class="reportsContainer">
                    <div class="reportType">
                        <p>Export Products</p>
                        <div class="alignRight">
                            <a class="reportExportButton" href="database/report_csv.php?report=products">CSV</a>
                            <a class="reportExportButton" href="database/report_pdf.php?report=products" target="_blank">PDF</a>
                        </div>
                    </div>
                    <div class="reportType">
                        <p>Export Suppliers</p>
                        <div class="alignRight">
                            <a class="reportExportButton" href="database/report_csv.php?report=suppliers">CSV</a>
                            <a class="reportExportButton" href="database/report_pdf.php?report=suppliers" target="_blank">PDF</a>
                        </div>
                    </div>
                </div>
                <div class="reportsContainer">
                    <div class="reportType">
                        <p>Export Deliveries</p>
                        <div class="alignRight">
                            <a class="reportExportButton" href="database/report_csv.php?report=deliveries">CSV</a>
                            <a class="reportExportButton" href="database/report_pdf.php?report=deliveries" target="_blank">PDF</a>
                        </div>
                    </div>
                    <div class="reportType">
                        <p>Export Purchase Orders</p>
                        <div class="alignRight">
                            <a class="reportExportButton" href="database/report_csv.php?report=orders">CSV</a>
                            <a class="reportExportButton" href="database/report_pdf.php?report=orders" target="_blank">PDF</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="scripts/script.js"></script>
    <script>
    </script>
</body>

</html>