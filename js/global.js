export function BurgerMenu() {
  //export the function BurgerMenu
  const hamburger = document.querySelector(".hamburger"); // define the variable hamburger as an index in the html attribute with the class(.)hamburger
  const closeIcon = document.querySelector(".hamburgerC"); // define the variable closeIcon as an index in the html attribute with the class(.)hamburgerC
  const menu = document.querySelector(".menu"); // define the variable menu as an index in the html attribute with the class(.)menu

  hamburger.addEventListener("click", () => {
    //on click
    menu.classList.add("showMenu"); //add the class showMenu(which i have adjust in css) to menu to display the burger menu
    hamburger.style.display = "none"; //hide the icon of menu
    closeIcon.style.display = "block"; // and put the close icon in its position
  });

  closeIcon.addEventListener("click", () => {
    // on click
    menu.classList.remove("showMenu"); //remove the showMenu class to hide the menu
    closeIcon.style.display = "none"; //hide the X button
    hamburger.style.display = "block"; // and put the menu icon in its position
  });
}

export function updateMenuForLoggedIn() {
  if (!window.userRole) return; // If userRole is not defined, exit the function

  document.querySelectorAll(".login, .signup").forEach((btn) => {
    btn.style.display = "none"; //Hide login/signup buttons
  });

  let navMenu = document.querySelector("nav ul");
  if (!navMenu) return;

  let dashboardLi = document.createElement("li"); // Create the Dashboard link
  dashboardLi.innerHTML = `<a class="menuItem dashboard" href="dashboard.php">Dashboard</a>`; // set its inner HTML
  navMenu.appendChild(dashboardLi); // add it to the navigation menu

  let logoutLi = document.createElement("li"); // Create the Log out link
  logoutLi.innerHTML = `<a class="menuItem logout" href="dashboard.php?action=logout">Log out</a>`; // set its inner HTML
  navMenu.appendChild(logoutLi); // add it to the navigation menu
}
