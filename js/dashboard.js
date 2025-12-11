import { BurgerMenu, updateMenuForLoggedIn } from "./global.js";
BurgerMenu();

let container = document.querySelector("#dashCont");

if (!userRole || (userRole !== "student" && userRole !== "teacher")) {
  // check for scam injection
  let fail = document.createElement("p");
  fail.textContent = "Invalid mode";
  fail.style.fontSize = "x-large";
  container.append(fail);
} else {
  loadDash(userRole);
}

function loadDash(userRole) {
  updateMenuForLoggedIn();
  const fragment = document.createDocumentFragment(); // we make a temporary container

  let header = document.createElement("h2"); //we make an element h2
  header.textContent =
    userRole === "student"
      ? `Welcome student ${username} `
      : `Welcome Mr. ${username} `;
  // header.style.textAlign = "center";

  fragment.append(header); // we put the h2 in the div

  let dashComing = document.createElement("h3"); //we make an element h2
  dashComing.textContent = "Dashboard is coming soon!";
  // dashComing.style.textAlign = "center";

  fragment.append(dashComing);

  let btnLogOut = document.createElement("button");
  btnLogOut.type = "button";
  btnLogOut.textContent = "Log out";
  btnLogOut.addEventListener("click", () => {
    window.location.href = "dashboard.php?action=logout";
  });

  fragment.append(btnLogOut);

  container.append(fragment);
  console.log("ok"); //TODO: remove it
}
