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

//-------------------------------//
//Update Menu for Logged In Users
//-------------------------------//
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
//-------------------------------//
//Create Element
//-------------------------------//
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

//-------------------------------//
// Fetch Data
//-------------------------------//
async function fetchData(url) {
  //method to fetch data and not repeat the same code
  const response = await fetch(url);
  return await response.json();
}

//-------------------------------//
// Course Methods
//-------------------------------//
//-------------------------------//
//Update Course
//-------------------------------//
async function updateCourse(courseId, newTitle) {
  //method to update a course
  const response = await fetch("./Api/ApiCourses.php?action=updateCourse", {
    method: "POST",
    body: new URLSearchParams({ courseId: courseId, newTitle: newTitle }),
  });
  const result = await response.json();

  if (!result.success) {
    alert("Error updating course: " + result.error);
    throw new Error(result.error);
  }
}

//-------------------------------//
//Delete Course
//-------------------------------//
async function deleteCourse(courseId) {
  //method to delete a course
  const response = await fetch(
    `./Api/ApiCourses.php?action=deleteCourse&id=${courseId}`,
    {
      method: "POST",
      body: new URLSearchParams({
        courseId: courseId, // ← Send as POST body, not query param
      }),
    }
  );
  const result = await response.json();

  if (!result.success) {
    alert("Error deleting course: " + result.error);
    throw new Error(result.error);
  }
}

//-------------------------------//
//Refresh Courses
//-------------------------------//
async function refreshCourses() {
  //method to refresh the course list
  const coursesData = await fetchData(
    "./Api/ApiCourses.php?action=bringCourses"
  );
  renderCourseList(document.querySelector(".dashboardContent"), coursesData);
}

//-------------------------------//
//Render Course List
//-------------------------------//
function renderCourseList(containerDiv, rows) {
  //method to render the course list

  const oldUL = containerDiv.querySelector(".dashboardUlContentInside");
  if (oldUL) oldUL.remove(); // Remove previous UL if exists

  const ulContentInside = createElement("ul", "", "dashboardUlContentInside");

  for (let i = 0; i < rows.length; i++) {
    let li = document.createElement("li");
    li.className = "dashboardListItem";

    let rowData = Array.isArray(rows[i]) // check if the row is an array or an object
      ? rows[i]
      : Object.values(rows[i]);

    let courseId = rowData[0]; // keep the course id
    li.dataset.courseId = courseId;

    for (let j = 1; j < rowData.length; j++) {
      // start from 1 to skip the id column
      let span = document.createElement("span");
      span.textContent = rowData[j];
      li.appendChild(span);
    }

    let editBtn = createElement("button", "Edit", "edit-btn", async () => {
      let newTitle = prompt("Edit course title:", li.children[0].textContent);
      if (newTitle && newTitle.trim()) {
        await updateCourse(courseId, newTitle.trim());
        refreshCourses(); // Reload list
      }
    });

    let deleteBtn = createElement(
      "button",
      "Delete",
      "delete-btn",
      async () => {
        if (confirm(`Delete course "${li.children[0].textContent}"?`)) {
          await deleteCourse(courseId);
          refreshCourses(); // Reload list
        }
      }
    );

    li.append(editBtn, deleteBtn);
    ulContentInside.appendChild(li);
  }

  containerDiv.appendChild(ulContentInside);
}

//-------------------------------//
//Update Dashboard Content
//-------------------------------//
function updateDashboardContent(divWithContentsOfChoice, headerText, btnText) {
  let fragmentDivContent = document.createDocumentFragment(); // make a fragment to hold temporary the contents
  divWithContentsOfChoice.innerHTML = "";

  let divContent = createElement("div", "", "dashboardContent"); // create a div to hold the content
  fragmentDivContent.append(divContent);

  let headerRowContent = createElement("div", "", "dashboardHeaderRow"); // create a header row of content
  let headerContent = createElement("h3", headerText); // create an h3 for the content title
  headerContent.style.textDecoration = "underline";
  headerRowContent.appendChild(headerContent);

  let btnAddContent = createElement("Button", btnText, "", async () => {
    //create a button to add new content f.e. new course, assignment etc.
    if (document.getElementById("newContentInput")) return; // prevent multiple input fields

    btnAddContent.style.display = "none";
    let inputNewContent = createElement("input");
    inputNewContent.id = "newContentInput";
    inputNewContent.placeholder = btnText;

    let btnSubmitForNewContent = createElement(
      "button",
      "Submit",
      "",
      async () => {
        // create a button to submit the new content
        let newCourseTitle = inputNewContent.value.trim();
        if (!newCourseTitle) {
          alert("Please enter a course title.");
          return;
        }

        let response = await fetch("./Api/ApiCourses.php?action=addCourse", {
          //hit the api to add the new xourse
          method: "POST",
          body: new URLSearchParams({ title: newCourseTitle }),
        });

        let result = await response.json();

        if (!result.success) {
          alert("Error adding new content: " + result.error);
          return;
        }
        inputNewContent.remove();
        btnSubmitForNewContent.remove();
        btnAddContent.style.display = "inline-block";

        refreshCourses();
      }
    );

    headerRowContent.appendChild(inputNewContent);
    headerRowContent.appendChild(btnSubmitForNewContent);
  });

  headerRowContent.appendChild(btnAddContent);

  fragmentDivContent.appendChild(headerRowContent);
  fragmentDivContent.appendChild(divContent);

  divWithContentsOfChoice.appendChild(fragmentDivContent);
  refreshCourses();
}

//-------------------------------//
//Show Courses
//-------------------------------//
export async function showCourses(div, buttonsToHide = [], btnCreateCourses) {
  try {
    const coursesData = await fetchData(
      "./Api/ApiCourses.php?action=bringCourses"
    ); //fetch the courses from the api

    const isCurrentlyHidden =
      div.style.display === "none" || !div.style.display;

    if (isCurrentlyHidden) {
      //load and show
      updateDashboardContent(div, "Courses", "Add New", coursesData);
      div.style.display = "flex";
      btnCreateCourses.textContent = "Back";
      buttonsToHide.forEach((btn) => (btn.style.display = "none"));
    } else {
      //collapse
      div.style.display = "none";
      btnCreateCourses.textContent = "Courses";
      buttonsToHide.forEach((btn) => (btn.style.display = "inline-block"));
    }
  } catch (err) {
    console.error("Error loading courses:", err);
  }
}
