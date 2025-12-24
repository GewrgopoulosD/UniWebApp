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

    const dashboardDiv = createElement("div", "", "dashboardDiv");

    let btnCreateCourses = createElement("button", "Courses", "coursesBtn"); // create a button to open courses

    let btnAssignments = createElement(
      "button",
      "Assignments",
      "assignmentsBtn"
    ); // create a button to open assignments

    let btnStudentSubmissions = createElement(
      "button",
      "Student Submissions",
      "studentSubmissionsBtn"
    ); // create a button to open student submissions

    let btnGrades = createElement("button", "Grades", "gradesBtn"); // create a button to open grades

    btnCreateCourses.addEventListener("click", async () => {
      await showCourses(
        dashboardDiv,
        [btnAssignments, btnStudentSubmissions, btnGrades],
        btnCreateCourses
      );
    });

    divForActions.append(
      btnCreateCourses,
      btnAssignments,
      btnStudentSubmissions,
      btnGrades
    );

    fragment.append(divForActions, dashboardDiv);

    // let btnLogOut = createElement("button", "Log out", "", () => {
    //   window.location.href = "dashboard.php?action=logout";
    // }); // create a button to log out

    // fragment.append(btnLogOut);

    container.append(fragment);
    console.log("ok"); //TODO: remove it
  }
}
