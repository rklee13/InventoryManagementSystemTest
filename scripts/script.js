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
