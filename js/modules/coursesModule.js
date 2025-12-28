import {
  updateDashboardContent,
  fetchData,
  renderList,
  refreshList,
} from "../modules/dashboardEngine.js";

//-------------------------------//
// Course Methods
//-------------------------------//

//-------------------------------//
//Update Course
//-------------------------------//
async function updateCourse(courseId, newTitle) {
  //method to update a course
  let response = await fetch("./Api/ApiCourses.php?action=updateCourse", {
    method: "POST",
    body: new URLSearchParams({ courseId, newTitle }),
  });
  let result = await response.json();

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
  let response = await fetch("./Api/ApiCourses.php?action=deleteCourse", {
    method: "POST",
    body: new URLSearchParams({
      courseId, // Send as POST, not query param
    }),
  });
  let result = await response.json();

  if (!result.success) {
    alert("Error deleting course: " + result.error);
    throw new Error(result.error);
  }
}
//-------------------------------//
// Show Courses
//-------------------------------//
export async function showCourses(
  div,
  buttonsToHide = [],
  btnCreateCourses,
  userRole
) {
  try {
    let coursesData = await fetchData(
      "./Api/ApiCourses.php?action=bringCourses"
    ); //fetch data

    let isCurrentlyHidden = div.style.display === "none" || !div.style.display;

    if (!isCurrentlyHidden) {
      // Collapse
      div.style.display = "none";
      btnCreateCourses.textContent = "Courses";
      buttonsToHide.forEach((btn) => (btn.style.display = "inline-block"));
      return;
    }

    updateDashboardContent(
      div,
      "Courses",
      "Add New",
      userRole,
      "./Api/ApiCourses.php?action=bringCourses",
      {},
      {}
    );

    // edit delete config
    let courseListConfig =
      userRole === "teacher"
        ? {
            editHandler: async (courseId, li, row) => {
              let newTitle = prompt("Edit course title:", row.title);
              if (newTitle?.trim()) {
                await updateCourse(courseId, newTitle.trim());
                await refreshList(
                  "./Api/ApiCourses.php?action=bringCourses",
                  containerDiv,
                  courseListConfig
                );
              }
            },
            deleteHandler: async (courseId, li, row) => {
              if (confirm(`Delete "${row.title}"?`)) {
                await deleteCourse(courseId);
                await refreshList(
                  "./Api/ApiCourses.php?action=bringCourses",
                  containerDiv,
                  courseListConfig
                );
              }
            },
          }
        : {};

    //add course config
    let addCourseConfig =
      userRole === "teacher"
        ? {
            submitHandler: async (title) => {
              await fetch("./Api/ApiCourses.php?action=addCourse", {
                method: "POST",
                headers: {
                  "Content-Type": "application/x-www-form-urlencoded",
                },
                body: new URLSearchParams({ title }),
              });
              await refreshList(
                "./Api/ApiCourses.php?action=bringCourses",
                containerDiv,
                courseListConfig
              );
            },
          }
        : {};

    updateDashboardContent(
      div,
      "Courses",
      "Add New",
      userRole,
      "./Api/ApiCourses.php?action=bringCourses",
      addCourseConfig,
      courseListConfig
    );

    let containerDiv = div.querySelector(".dashboardContent");

    renderList(containerDiv, coursesData.data, courseListConfig);

    div.style.display = "flex"; // Show Dashboard
    btnCreateCourses.textContent = "Back";
    buttonsToHide.forEach((btn) => (btn.style.display = "none"));
  } catch (err) {
    console.error("Error loading courses:", err);
  }
}
