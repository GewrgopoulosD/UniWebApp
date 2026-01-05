import { BurgerMenu } from "./global.js";

BurgerMenu();

document.addEventListener("DOMContentLoaded", () => {
  const gradesContainer = document.querySelector(".gradesContainer"); //student grades

  if (gradesContainer && !gradesContainer.querySelector(".gradesList")) {
    async function loadStudentGrades() {
      try {
        const res = await fetch(
          "./Api/ApiGrades.php?action=bringStudentGrades"
        );
        const data = await res.json();

        if (data.success && data.grades.length > 0) {
          let rows = data.grades
            .map(
              (g) => `<tr>
                        <td>${g.assignment_title}</td>
                        <td>${g.title_course}</td>
                        <td>${g.grade}</td>
                      </tr>`
            )
            .join("");

          gradesContainer.innerHTML = `
            <h3>My Grades</h3>
            <table class="gradesTable">
              <thead>
                <tr>
                  <th>Assignment</th>
                  <th>Course</th>
                  <th>Grade</th>
                </tr>
              </thead>
              <tbody>${rows}</tbody>
            </table>
          `;
        } else {
          gradesContainer.innerHTML = `<h3>My Grades</h3><p>No grades available.</p>`;
        }
      } catch (err) {
        console.error("Error loading student grades:", err);
        gradesContainer.innerHTML = `<p>Error loading grades.</p>`;
      }
    }

    loadStudentGrades();
  }

  //teacher grade
  const gradesModal = document.getElementById("previewGradesModal");
  const modalStudent = document.querySelector(".addNameHeader");
  const gradesBody = gradesModal.querySelector(".modal-body");
  const closeModalSpan = document.getElementById("closePreviewModal");
  const closeModalBtn = document.getElementById("closePreviewBtn");

  const studentCards = document.querySelectorAll(".studentCard");

  function openGradesModal(userId, username) {
    modalStudent.textContent = `Grades for: ${username}`;
    gradesModal.classList.add("active");
    gradesBody.innerHTML = "<p>Loading grades...</p>";

    fetch(`./Api/ApiGrades.php?action=bringGradesForStudent&user_id=${userId}`)
      .then((res) => res.json())
      .then((data) => {
        if (data.success && data.grades.length > 0) {
          const rows = data.grades
            .map(
              (g) => `<tr>
                        <td>${g.assignment_title}</td>
                        <td>${g.title_course}</td>
                        <td>${g.grade}</td>
                      </tr>`
            )
            .join("");

          gradesBody.innerHTML = `
            <table class="gradesTable">
              <thead>
                <tr>
                  <th>Assignment</th>
                  <th>Course</th>
                  <th>Grade</th>
                </tr>
              </thead>
              <tbody>${rows}</tbody>
            </table>`;
        } else {
          gradesBody.innerHTML = "<p>No grades found for this student.</p>";
        }
      })
      .catch((err) => {
        console.error("Error loading grades:", err);
        gradesBody.innerHTML = "<p>Error loading grades.</p>";
      });
  }

  studentCards.forEach((card) => {
    card.addEventListener("click", () => {
      const userId = card.dataset.studentId;
      const username = card.dataset.studentUsername;
      if (userId) openGradesModal(userId, username);
    });
  });

  //close modal
  function closeModal() {
    gradesModal.classList.remove("active");
    gradesBody.innerHTML = "";
  }

  closeModalSpan?.addEventListener("click", closeModal);
  closeModalBtn?.addEventListener("click", closeModal);

  window.addEventListener("click", (e) => {
    if (e.target === gradesModal) closeModal();
  });
});
