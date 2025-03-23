<?php
// Start the session
session_start();

if (!isset($_SESSION['user'])) header("location:login.php");

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
    <div class="dashboardSidebar" id="dashboardSidebar">
      <h3 class="dashboardLogo" id="dashboardLogo">IMS</h3>
      <div class="dashboardSidebarUser">
        <img id="userImage" src="./Images/userProfileHolder.jpg" alt="User image." />
        <span><?= $user['first_name'] . ' ' . $user['last_name']  ?></span>
      </div>
      <div class="dashboardSidebarMenu">
        <ul class="dashboardMenuLists">
          <li class="activeMenu">
            <a href=""><i class="fa-solid fa-gauge"></i><span class="menuText">Dashboard</span></a>
          </li>
          <li>
            <a href=""><i class="fa-solid fa-bullhorn"></i><span class="menuText">Campaign</span></a>
          </li>
          <li>
            <a href=""><i class="fa-solid fa-dollar-sign"></i><span class="menuText">Revenue Management</span></a>
          </li>
          <li>
            <a href=""><i class="fa-solid fa-book"></i><span class="menuText">Accounts Receivable</span></a>
          </li>
          <li>
            <a href=""><i class="fa-solid fa-gears"></i><span class="menuText">Configuration</span></a>
          </li>
          <li>
            <a href=""><i class="fa-solid fa-chart-line"></i><span class="menuText">Stats</span></a>
          </li>
        </ul>
      </div>
    </div>
    <div class="dashboardContentContainer" id="dashboardContentContainer">
      <div class="dashboardContentTopNav">
        <a href="" id="sidebarToggleButton"><i class="fa-solid fa-bars"></i></a>
        <a href="database/logout.php" id="logoutButton"><i class="fa-solid fa-power-off"></i> Log out</a>
      </div>
      <div class="dashboardContent">
        <div class="dashboardContentMain"></div>
      </div>
    </div>
  </div>

  <script>
    var sidebarIsOpen = true;

    sidebarToggleButton.addEventListener("click", (event) => {
      event.preventDefault();

      if (sidebarIsOpen) {
        dashboardSidebar.style.width = "10%";
        dashboardSidebar.style.transition = "0.3s all";
        dashboardContentContainer.style.width = "90%";
        dashboardLogo.style.fontSize = "60px";
        userImage.style.width = "60px";

        menuIconTexts = document.getElementsByClassName("menuText");
        for (var i = 0; i < menuIconTexts.length; i++) {
          menuIconTexts[i].style.display = "none";
        }
        document.getElementsByClassName(
          "dashboardMenuLists"
        )[0].style.textAlign = "center";

        sidebarIsOpen = false;
      } else {
        dashboardSidebar.style.width = "20%";
        dashboardSidebar.style.transition = "0.3s all";
        dashboardContentContainer.style.width = "80%";
        dashboardLogo.style.fontSize = "80px";
        userImage.style.width = "20%";

        menuIconTexts = document.getElementsByClassName("menuText");
        for (var i = 0; i < menuIconTexts.length; i++) {
          menuIconTexts[i].style.display = "inline-block";
        }
        document.getElementsByClassName(
          "dashboardMenuLists"
        )[0].style.textAlign = "left";

        sidebarIsOpen = true;
      }
    });
  </script>
</body>

</html>