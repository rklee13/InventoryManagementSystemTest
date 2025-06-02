<?php
// Start the session
session_start();

if (!isset($_SESSION['user']))
  header("location:login.php");

$user = $_SESSION['user'];

// Get the graph data - purchase order by status
include('database/po_status_pie_graph.php');

// Get the graph data -supplier product count
include('database/supplier_product_bar_graph.php');

// Get the graph data -delivery history per day
include('database/delivery_history_line_graph.php');
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
      <?php if (in_array('dashboard_view', $user['permissions'])) { ?>
        <div class="dashboardContent">
          <div class="dashboardChartContainer dashboardContentMain">
            <div class="col50">
              <figure class="highcharts-figure">
                <div id="pieChartContainer"></div>
                <p class="highcharts-description" style="text-align: center">
                  Breakdown of all purchased orders by status.
                </p>
              </figure>
            </div>
            <div class="col50">
              <figure class="highcharts-figure">
                <div id="barChartContainer"></div>
                <p class="highcharts-description" style="text-align: center">
                  Total product count based on suppliers.
                </p>
              </figure>
            </div>
          </div>
          <div class="dashboardContentMain">
            <figure class="highcharts-figure">
              <div id="deliveryHistoryLineChartContainer"></div>
              <p class="highcharts-description">
                Basic line chart showing trends in a dataset. This chart includes the
                <code>series-label</code> module, which adds a label to each line for
                enhanced readability.
              </p>
            </figure>
          </div>
        </div>
      <?php } else { ?>
        <div id="accessDeniedErrorMessage">Access denied.</div>
      <?php }?>
    </div>
  </div>

  <script src="scripts/script.js"></script>
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/modules/exporting.js"></script>
  <script src="https://code.highcharts.com/modules/accessibility.js"></script>
  <script src="https://code.highcharts.com/modules/export-data.js"></script>
  <script>
    // Pie Chart - Purchase Orders by Status
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
          data: <?= json_encode($poStatusPieResults) ?>
        }
      ]
    });

    // Bar Chart
    Highcharts.chart('barChartContainer', {
      chart: {
        type: 'column'
      },
      title: {
        text: 'Product Count Assigned To Supplier'
      },
      subtitle: {
        text:
          'Source: <a target="_blank" ' +
          'href="https://www.indexmundi.com/agriculture/?commodity=corn">indexmundi</a>'
      },
      xAxis: {
        categories: <?= json_encode($supplierNames) ?>,
        crosshair: true,
        accessibility: {
          description: 'Suppliers'
        }
      },
      yAxis: {
        min: 0,
        title: {
          text: 'Product Set'
        }
      },
      tooltip: {
        valueSuffix: ' products'
        // headerFormat: '<span style="font-size:10px">{point.key}</span>'
        // pointFormatter: function() {
        //   var point = this,
        //   series= point.series;

        //   return '<b>${point.name}</b>: ${point.y}';
        // }
      },
      plotOptions: {
        column: {
          pointPadding: 0.2,
          borderWidth: 0
        }
      },
      series: [
        {
          name: 'Product Count',
          data: <?= json_encode($barChartData) ?>
        }
      ]
    });
  
    // Line Chart
    Highcharts.chart('deliveryHistoryLineChartContainer', {
      // chart: {
      //   type: 'spline'
      // },

    title: {
        text: 'Delivery History Per Day',
        align: 'left'
    },

    yAxis: {
        title: {
            text: 'Product Delivered'
        }
    },

    xAxis: {
      categories: <?= json_encode($line_categories) ?>
        // accessibility: {
        //     rangeDescription: 'Range: 2010 to 2022'
        // }
    },

    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },

    plotOptions: {
        series: {
            label: {
                connectorAllowed: false
            },
        }
    },

    series: [{
        name: 'Product Delivered',
        data: <?= json_encode($line_data) ?>
    }],

    responsive: {
        rules: [{
            condition: {
                maxWidth: 500
            },
            chartOptions: {
                legend: {
                    layout: 'horizontal',
                    align: 'center',
                    verticalAlign: 'bottom'
                }
            }
        }]
    }

});

  </script>
</body>

</html>