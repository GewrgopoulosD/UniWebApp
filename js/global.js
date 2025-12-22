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

export function createElement(type, text, className, onclick) {
  // to not repeat the same code for creating elements , make this function which creates any type of elements
  let element = document.createElement(type);
  element.textContent = text;
  if (className) {
    element.className = className;
  }
  if (onclick) {
    element.addEventListener("click", onclick);
  }
  return element;
}

async function fetchData(url) {
  // make a method to fecth data from apis and not repeat code
  const response = await fetch(url);
  return await response.json();
}

function toggleTable(table, mainBtn, otherButtons) {
  const isVisible = table.style.display === "table";

  table.style.display = isVisible ? "none" : "table";
  mainBtn.textContent = isVisible ? "Courses" : "Back";

  otherButtons.forEach((btn) => {
    if (btn) btn.style.display = isVisible ? "inline-block" : "none";
  });

  return !isVisible;
}

function updateTable(table, headerText, btnText, rows) {
  table.innerHTML = "";

  let headerRow = document.createElement("tr");
  let thMain = document.createElement("th");
  thMain.textContent = headerText;
  thMain.style.textDecoration = "underline";
  thMain.colSpan = 4; // unite the columns
  headerRow.appendChild(thMain);

  let btnAddCourse = createElement("Button", btnText, "", () => {
    if (!document.getElementById("newCourseInput")) {
      btnAddCourse.style.display = "none";
      let inputNew = createElement("input");
      inputNew.id = "newCourseInput";
      inputNew.placeholder = btnText;

      let btnSubmit = createElement("button", "Submit", "", async () => {
        let newCourseTitle = inputNew.value.trim();
        if (!newCourseTitle) {
          alert("Please enter a course title.");
          return;
        }

        let response = await fetch("./Api/ApiCourses.php?action=addCourse", {
          method: "POST",
          body: new URLSearchParams({ title: newCourseTitle }),
        });
        let result = await response.json();

        if (result.success) {
          alert(result.message); // show the json message from the api
          showCourses(table); // reload the table
          inputNew.remove();
          btnSubmit.remove();
          btnAddCourse.style.display = "inline-block";
        } else {
          alert(result.error || "Something went wrong");
        }
      });
      btnAddCourse.after(inputNew, btnSubmit);
    }
  });
  headerRow.appendChild(btnAddCourse);
  table.appendChild(headerRow);

  for (let i = 0; i < rows.length; i++) {
    let tr = document.createElement("tr");

    let rowData = Array.isArray(rows[i]) ? rows[i] : Object.values(rows[i]);

    for (let j = 0; j < rowData.length; j++) {
      let td = document.createElement("td");
      td.textContent = rowData[j];
      tr.appendChild(td);
    }

    table.appendChild(tr);
  }
}

export async function showCourses(table, buttonsToHide = [], btnCreateCourses) {
  try {
    const shouldShow = toggleTable(table, btnCreateCourses, buttonsToHide);
    if (!shouldShow) return;

    const coursesData = await fetchData(
      "./Api/ApiCourses.php?action=bringCourses"
    );

    updateTable(table, "Courses", "Add New", coursesData);
  } catch (err) {
    console.error("Error loading courses:", err);
  }
}
