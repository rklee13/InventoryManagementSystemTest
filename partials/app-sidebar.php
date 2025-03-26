<div class="dashboardSidebar" id="dashboardSidebar">
    <h3 class="dashboardLogo" id="dashboardLogo">IMS</h3>
    <div class="dashboardSidebarUser">
        <img id="userImage" src="./Images/userProfileHolder.jpg" alt="User image." />
        <span><?= $user['first_name'] . ' ' . $user['last_name'] ?></span>
    </div>
    <div class="dashboardSidebarMenu">
        <ul class="dashboardMenuLists">
        <!-- class="activeMenu" -->
            <li>
                <a href="./dashboard.php"><i class="fa-solid fa-gauge"></i><span class="menuText">Dashboard</span></a>
            </li>
            <li>
                <a href="./users-add.php"><i class="fa-solid fa-user-plus"></i><span class="menuText">Add User</span></a>
            </li>
            <!-- <li>
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
            </li> -->
        </ul>
    </div>
</div>