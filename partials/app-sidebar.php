<?php
$user = $_SESSION['user'];
?>
<div class="dashboardSidebar" id="dashboardSidebar">
    <h3 class="dashboardLogo" id="dashboardLogo">IMS</h3>
    <div class="dashboardSidebarUser">
        <img id="userImage" src="./Images/userProfileHolder.jpg" alt="User image." />
        <span><?= $user['first_name'] . ' ' . $user['last_name'] ?></span>
    </div>
    <div class="dashboardSidebarMenu">
        <ul class="dashboardMenuLists">
            <!-- class="activeMenu" -->
            <li class="listMainMenuItem">
                <a href="./dashboard.php"><i class="fa-solid fa-gauge"></i><span class="menuText">Dashboard</span></a>
            </li>
            <li class="listMainMenuItem">
                <a href="javascript:void(0)" class="showHideSubMenu">
                    <i class="fa-solid fa-tag"></i>
                    <span class="menuText showHideSubMenu">Product Management</span>
                    <i class="fa-solid fa-chevron-left mainMenuChevron showHideSubMenu"></i>
                </a>
                <ul id="productSubMenus" class="subMenus">
                    <li>
                        <a class="subMenuLink" href="./product-view.php"><i class="fa-regular fa-circle"></i> View Product</a>
                    </li>
                    <li>
                        <a class="subMenuLink" href="./product-add.php"><i class="fa-regular fa-circle"></i> Add Product</a>
                    </li>
                    <li>
                        <a class="subMenuLink" href="./product-order.php"><i class="fa-regular fa-circle"></i> Order Product</a>
                    </li>
                </ul>
            </li>
            <li class="listMainMenuItem">
                <a href="javascript:void(0)" class="showHideSubMenu">
                    <i class="fa-solid fa-truck"></i>
                    <span class="menuText showHideSubMenu">Supplier Management</span>
                    <i class="fa-solid fa-chevron-left mainMenuChevron showHideSubMenu"></i>
                </a>
                <ul id="supplierSubMenus" class="subMenus">
                    <li>
                        <a class="subMenuLink" href="./supplier-view.php"><i class="fa-regular fa-circle"></i> View Supplier</a>
                    </li>
                    <li>
                        <a class="subMenuLink" href="./supplier-add.php"><i class="fa-regular fa-circle"></i> Add Supplier</a>
                    </li>
                </ul>
            </li>
            <li class="listMainMenuItem showHideSubMenu">
                <a href="javascript:void(0)" id="listMainMenuItemUserManagement" class="showHideSubMenu">
                    <i class="fa-solid fa-user-plus showHideSubMenu"></i>
                    <span class="menuText showHideSubMenu">User Management</span>
                    <i class="fa-solid fa-chevron-left mainMenuChevron showHideSubMenu"></i>
                </a>
                <ul id="userAddSubMenus" class="subMenus">
                    <li>
                        <a class="subMenuLink" href="./users-view.php"><i class="fa-regular fa-circle"></i> View Users</a>
                    </li>
                    <li>
                        <a class="subMenuLink" href="./users-add.php"><i class="fa-regular fa-circle"></i> Add Users</a>
                    </li>
                </ul>
            </li>
            <!-- 
            <li>
                <a href=""><i class="fa-solid fa-gears"></i><span class="menuText">Configuration</span></a>
            </li>
            <li>
                <a href=""><i class="fa-solid fa-chart-line"></i><span class="menuText">Stats</span></a>
            </li> -->
        </ul>
    </div>
</div>