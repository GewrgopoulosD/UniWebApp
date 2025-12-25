import { BurgerMenu, updateMenuForLoggedIn, createElement } from "./global.js";
import { showCourses } from "./modules/coursesModule.js";

BurgerMenu();

let container = document.querySelector("#dashCont");

if (!userRole || (userRole !== "student" && userRole !== "teacher")) {
  // check for scam injection
  let fail = document.createElement("p");
  fail.textContent = "Forbidden Action";
  fail.style.fontSize = "x-large";
  container.append(fail);
} else {
  loadDash(userRole);
}

function loadDash(userRole) {
  updateMenuForLoggedIn();

  const fragment = document.createDocumentFragment(); // we make a temporary container

  let header = createElement(
    "h2",
    userRole === "teacher"
      ? `Welcome Mr. ${username} `
      : `Welcome Student ${username} `
  ); //we make an element h2

  fragment.append(header); // we put the h2 in the div

  let divForActions = createElement("div", "", "actionsContainer"); // create a div to contain action buttons

  const dashboardDiv = createElement("div", "", "dashboardDiv");

  let btnCourses = createElement("button", "Courses", "coursesBtn"); // create a button to open courses

  let btnAssignments = createElement("button", "Assignments", "assignmentsBtn"); // create a button to open assignments

  let btnStudentSubmissions = createElement(
    "button",
    "Student Submissions",
    "studentSubmissionsBtn"
  ); // create a button to open student submissions

  let btnGrades = createElement("button", "Grades", "gradesBtn"); // create a button to open grades

  divForActions.append(
    btnCourses,
    btnAssignments,
    btnStudentSubmissions,
    btnGrades
  );

  fragment.append(divForActions, dashboardDiv);

  let btnLogOut = createElement("button", "Log out", "LogOutBtn", () => {
    window.location.href = "dashboard.php?action=logout";
  }); // create a button to log out

  fragment.append(btnLogOut);

  container.append(fragment);

  //--------------------------------//
  //START MAKING METHODS            //
  //-------------------------------//

  //-------------------------------//
  //for courses//
  //------------------------------//
  btnCourses.addEventListener("click", async () => {
    await showCourses(
      dashboardDiv,
      [btnAssignments, btnStudentSubmissions, btnGrades],
      btnCourses,
      userRole
    );
  });
  console.log("ok"); //TODO: remove it
}
