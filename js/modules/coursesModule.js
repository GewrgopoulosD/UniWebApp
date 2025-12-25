import { createElement, fetchData } from "../global.js";

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
async function refreshCourses(userRole) {
  //method to refresh the course list
  const coursesData = await fetchData(
    "./Api/ApiCourses.php?action=bringCourses"
  );
  renderCourseList(
    document.querySelector(".dashboardContent"),
    coursesData,
    userRole
  );
}

//-------------------------------//
//Render Course List
//-------------------------------//
function renderCourseList(containerDiv, rows, userRole) {
  //method to render the course list

  const oldUL = containerDiv.querySelector(".dashboardUlContentInside");
  if (oldUL) oldUL.remove(); // Remove previous UL if exists

  const ulContentInside = createElement("ul", "", "dashboardUlContentInside"); //make a ul

  for (let i = 0; i < rows.length; i++) {
    //for each row we make a li
    let li = document.createElement("li");
    li.className = "dashboardListItem";

    let rowData = Array.isArray(rows[i]) // check if the row is an array or an object
      ? rows[i]
      : Object.values(rows[i]);

    let courseId = rowData[0]; // keep the course id
    li.dataset.courseId = courseId; // and set it to dataset to edit it if we want, also with that we will fetch topics of each courseId

    for (let j = 1; j < rowData.length; j++) {
      //for each column make a span beside of li
      // start from 1 to skip the id column
      let span = document.createElement("span");
      span.textContent = rowData[j];
      li.appendChild(span);
    }

    if (userRole !== "student") {
      //if not student
      let editBtn = createElement("button", "Edit", "edit-btn", async () => {
        //make an edit button
        let newTitle = prompt("Edit course title:", li.children[0].textContent); //on click we open a prompt and  take the text content
        if (newTitle && newTitle.trim()) {
          //on click (in prompt) if there is sth
          await updateCourse(courseId, newTitle.trim()); //we call the method update course
          refreshCourses(); // Reload list
        }
      });

      let deleteBtn = createElement(
        "button",
        "Delete",
        "delete-btn",
        async () => {
          if (confirm(`Delete course "${li.children[0].textContent}"?`)) {
            // the same as the upper but without input
            await deleteCourse(courseId); // and delete it with the method
            refreshCourses(); // Reload list
          }
        }
      );

      li.append(editBtn, deleteBtn); //put it in the li
    }
    ulContentInside.appendChild(li); //put the li in ul
  }

  containerDiv.appendChild(ulContentInside); // put ul in parent div
}

//-------------------------------//
//Update Dashboard Content
//-------------------------------//
function updateDashboardContent(
  divWithContentsOfChoice,
  headerText,
  btnText,
  userRole
) {
  let fragmentDivContent = document.createDocumentFragment(); // make a fragment to hold temporary the contents
  divWithContentsOfChoice.innerHTML = "";

  let divContent = createElement("div", "", "dashboardContent"); // create a div to hold the content
  fragmentDivContent.append(divContent);

  let headerRowContent = createElement("div", "", "dashboardHeaderRow"); // create a header row of content
  let headerContent = createElement("h3", headerText); // create an h3 for the content title
  headerContent.style.textDecoration = "underline";
  headerRowContent.appendChild(headerContent);

  if (userRole !== "student") {
    // check if user is teacher or student
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

          refreshCourses(userRole);
        }
      );

      headerRowContent.appendChild(inputNewContent);
      headerRowContent.appendChild(btnSubmitForNewContent);
    });

    headerRowContent.appendChild(btnAddContent);
  }

  fragmentDivContent.appendChild(headerRowContent);
  fragmentDivContent.appendChild(divContent);

  divWithContentsOfChoice.appendChild(fragmentDivContent);
  refreshCourses(userRole);
}

//-------------------------------//
//Show Courses
//-------------------------------//
export async function showCourses(
  div,
  buttonsToHide = [],
  btnCreateCourses,
  userRole
) {
  try {
    const coursesData = await fetchData(
      "./Api/ApiCourses.php?action=bringCourses"
    ); //fetch the courses from the api

    const isCurrentlyHidden =
      div.style.display === "none" || !div.style.display;

    if (isCurrentlyHidden) {
      //load and show
      updateDashboardContent(div, "Courses", "Add New", userRole);
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
