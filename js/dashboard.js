import {
  BurgerMenu,
  updateMenuForLoggedIn,
  createElement,
  showCourses,
} from "./global.js";
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

  if (userRole === "teacher") {
    const fragment = document.createDocumentFragment(); // we make a temporary container

    let header = createElement("h2", `Welcome Mr. ${username} `); //we make an element h2

    fragment.append(header); // we put the h2 in the div

    let divForActions = createElement("div", "", "actionsContainer"); // create a div to contain action buttons

    let btnCreateCourses = createElement(
      "button",
      "Courses",
      "coursesBtn",
      async () => {
        await showCourses(
          dashboardTable,
          [btnAssignments, btnStudentSubmissions, btnGrades],
          btnCreateCourses
        );
      }
    );

    // create a button to open the table for courses
    divForActions.append(btnCreateCourses);

    let btnAssignments = createElement(
      "button",
      "Assignments",
      "assignmentsBtn"
    ); // create a button to open the table for courses
    divForActions.append(btnAssignments);

    let btnStudentSubmissions = createElement(
      "button",
      "Student Submissions",
      "studentSubmissionsBtn"
    ); // create a button to open the table for courses
    divForActions.append(btnStudentSubmissions);

    let btnGrades = createElement("button", "Grades", "gradesBtn"); // create a button to open the table for courses
    divForActions.append(btnGrades);

    fragment.append(divForActions);

    let dashboardTable = createElement("table", "", "dashboardTable"); //we make a table to show the data
    dashboardTable.style.display = "none"; // initially make it hidden

    let thead = createElement("thead"); // create thead and tbody so every button that user clicks we will update them dynamically
    let tbody = createElement("tbody");
    dashboardTable.append(thead, tbody);

    fragment.append(dashboardTable);

    // let btnLogOut = createElement("button", "Log out", "", () => {
    //   window.location.href = "dashboard.php?action=logout";
    // }); // create a button to log out

    // fragment.append(btnLogOut);

    container.append(fragment);
    console.log("ok"); //TODO: remove it
  }
}
