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
    document.getElementsByClassName("dashboardMenuLists")[0].style.textAlign =
      "center";

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
    document.getElementsByClassName("dashboardMenuLists")[0].style.textAlign =
      "left";

    sidebarIsOpen = true;
  }
});

// Show/Hide submenu
document.addEventListener("click", function (e) {
  // e.preventDefault();
  const clickedElement = e.target;

  if (clickedElement.classList.contains("showHideSubMenu")) {
    const targetSubMenu = clickedElement
      .closest("li")
      .querySelector(".subMenus");
    const chevronIcon = clickedElement
      .closest("li")
      .querySelector(".mainMenuChevron");

    // Close all open submenus
    const allSubMenus = document.querySelectorAll(".subMenus");
    const allChevronIcon = document.querySelectorAll(".mainMenuChevron");
    allSubMenus.forEach((subMenu) => {
      if (subMenu !== targetSubMenu) {
        subMenu.style.display = "none";
      }
    });
    allChevronIcon.forEach((icon) => {
      if (icon !== chevronIcon) {
        icon.classList.remove("fa-chevron-down");
        icon.classList.add("fa-chevron-left");
      }
    });

    // Hide/Show selected sub menu
    showHideSubMenu(targetSubMenu, chevronIcon);
  }
});

// Function to hide/show submenu
function showHideSubMenu(targetSubMenu, chevronIcon) {
  if (targetSubMenu) {
    const isShown = targetSubMenu.style.display === "block";
    if (!isShown) {
      targetSubMenu.style.display = "block";
      chevronIcon.classList.remove("fa-chevron-left");
      chevronIcon.classList.add("fa-chevron-down");
    } else {
      targetSubMenu.style.display = "none";
      chevronIcon.classList.remove("fa-chevron-down");
      chevronIcon.classList.add("fa-chevron-left");
    }
  }
}

// Add/hide active class to menu
// Get the current page
// Use selector to get current menu or submenu
// Add the actual class
const pathArray = window.location.pathname.split("/");
const curFile = pathArray[pathArray.length - 1];
const curNav = document.querySelector('a[href="./' + curFile + '"');

if (curNav) {
  curNav.classList.add("subMenuActive");
  const mainNav = curNav.closest("li.listMainMenuItem");

  if (mainNav) {
    mainNav.style.background = "#f685a1";
    const targetSubMenu = curNav.closest(".subMenus");
    const chevronIcon = mainNav.querySelector(".mainMenuChevron");
    // Hide/Show selected sub menu
    showHideSubMenu(targetSubMenu, chevronIcon);
  }
}
