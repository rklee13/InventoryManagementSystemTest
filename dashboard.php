<?php
// Start the session
session_start();

if (!isset($_SESSION['user']))
  header("location:login.php");

$user = $_SESSION['user'];

// Get the graph data - purchase order by status
include('database/po_status_pie_graph.php');
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
        <div class="dashboardContentMain">
          <figure class="highcharts-figure">
            <div id="pieChartContainer"></div>
            <p class="highcharts-description" style="text-align: center">
              Breakdown of all purchased orders by status.
            </p>
          </figure>
        </div>
      </div>
    </div>
  </div>

  <script src="scripts/script.js"></script>
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/modules/exporting.js"></script>
  <script src="https://code.highcharts.com/modules/accessibility.js"></script>
  <script src="https://code.highcharts.com/modules/export-data.js"></script>
  <script>
    Highcharts.chart('pieChartContainer', {
      chart: {
        type: 'pie'
      },
      title: {
        text: 'Purchase Orders By Status'
      },
      tooltip: {
        valueSuffix: ' orders'
      },
      subtitle: {
        text:
          'Source:<a href="https://www.mdpi.com/2072-6643/11/3/684/htm" target="_default">MDPI</a>'
      },
      plotOptions: {
        pie: {
          allowPointSelect: true,
          cursor: 'pointer',
          dataLabels: [{
            enabled: true,
            distance: 20
          }, {
            enabled: true,
            distance: -40,
            format: '{point.percentage:.1f}%',
            style: {
              fontSize: '1.2em',
              textOutline: 'none',
              opacity: 0.7
            },
            filter: {
              operator: '>',
              property: 'percentage',
              value: 10
            }
          }]
        }
      },
      series: [
        {
          name: 'Status',
          colorByPoint: true,
          data: <?= json_encode($results) ?>
        }
      ]
    });
  </script>
</body>

</html>