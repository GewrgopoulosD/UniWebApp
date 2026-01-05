import { BurgerMenu } from "./global.js";

BurgerMenu();

document.addEventListener("DOMContentLoaded", () => {
  const viewSubmissionsModal = document.getElementById("viewSubmissionsModal"); //modal view total
  const submissionsTableBody = document.getElementById("submissionsTableBody"); // tBody (delegation)
  const submissionsModalTitle = document.getElementById(
    "submissionsModalTitle"
  ); //title modal
  const closeSubmissionsModalBtn = document.getElementById(
    "closeSubmissionsModal"
  ); //btn X
  const closeSubmissionsBtn = document.getElementById("closeSubmissionsBtn"); //button close (footer)

  //view submission btn teacher
  document.querySelectorAll(".btn-view-submissions").forEach((btn) => {
    btn.addEventListener("click", async (e) => {
      e.stopPropagation();

      const assignmentId = btn.dataset.assignmentId;
      const title = btn.dataset.title;
      const course = btn.dataset.course;

      submissionsModalTitle.innerText = `${title} (${course})`;
      submissionsTableBody.innerHTML =
        "<tr><td colspan='4'>Loading...</td></tr>";
      viewSubmissionsModal.classList.add("active");

      try {
        const response = await fetch(
          `./Api/ApiSubmission.php?action=bringSubmissionsByAssignment&assignmentId=${assignmentId}`
        );
        const result = await response.json();

        if (!result.success) {
          submissionsTableBody.innerHTML =
            "<tr><td colspan='4'>Failed to load submissions</td></tr>";
          return;
        }

        renderSubmissions(result.data);
      } catch (error) {
        console.error(error);
        submissionsTableBody.innerHTML =
          "<tr><td colspan='4'>Error loading submissions</td></tr>";
      }
    });
  });

  //rendder sub table
  function renderSubmissions(submissions) {
    submissionsTableBody.innerHTML = "";

    if (!submissions || submissions.length === 0) {
      submissionsTableBody.innerHTML =
        "<tr><td colspan='4'>No students found</td></tr>";
      return;
    }

    submissions.forEach((sub) => {
      const tr = document.createElement("tr");

      tr.innerHTML = `
        <td>${sub.student_name}</td>
        <td class="${
          sub.status === "Submitted" ? "status-ok" : "status-pending"
        }">
          ${sub.status}
        </td>
        <td>${sub.submission_date ?? "-"}</td>
        <td>
          <input 
            type="number"
            id="grade-${sub.submission_id}"
            name="grade-${sub.submission_id}"
            min="0"
            max="10"
            step="0.1"
            value="${sub.grade ?? ""}"
            data-submission-id="${sub.submission_id ?? ""}"
            class="grade-input"
                     ${sub.status === "Submitted" ? "" : "disabled"}
        />
        <button class="btn-save-grade" ${
          sub.status === "Submitted" ? "" : "disabled"
        }>
          ${sub.grade !== null ? "Update" : "Save"}
        </button>

        </td>
      `;

      submissionsTableBody.appendChild(tr);
    });
  }

  //delegation save btn
  submissionsTableBody.addEventListener("click", async (e) => {
    if (!e.target.classList.contains("btn-save-grade")) return;

    const btn = e.target;
    const row = btn.closest("tr");
    const input = row.querySelector(".grade-input");
    const submissionId = input.dataset.submissionId;
    const grade = input.value;

    if (!submissionId || grade === "") {
      alert("Please enter a grade");
      return;
    }

    try {
      const formData = new FormData();
      formData.append("submissionId", submissionId);
      formData.append("grade", grade);

      const response = await fetch(
        "./Api/ApiSubmission.php?action=gradeSubmission",
        { method: "POST", body: formData }
      );

      const result = await response.json();

      if (result.success) {
        alert("Grade saved!");
        btn.textContent = "Update";
      } else {
        alert(result.message || "Failed to save grade");
      }
    } catch (error) {
      console.error(error);
      alert("Server error");
    }
  });

  //close modal function
  function closeSubmissionsModal() {
    viewSubmissionsModal.classList.remove("active");
  }

  closeSubmissionsModalBtn?.addEventListener("click", closeSubmissionsModal);
  closeSubmissionsBtn?.addEventListener("click", closeSubmissionsModal);

  //student submission
  document.querySelectorAll(".btn-submit-assignment").forEach((btn) => {
    btn.addEventListener("click", async () => {
      const assignmentId = btn.dataset.assignmentId;
      if (!assignmentId) return;

      if (!confirm("Are you sure you want to submit this assignment?")) return;

      try {
        const formData = new FormData();
        formData.append("assignmentId", assignmentId);

        const response = await fetch(
          "./Api/ApiSubmission.php?action=submitAssignment",
          { method: "POST", body: formData }
        );

        const result = await response.json();

        if (result.success) {
          btn.textContent = "✔ Submitted";
          btn.disabled = true;
        } else {
          alert(result.message || "Failed to submit assignment");
        }
      } catch (error) {
        console.error(error);
        alert("Error submitting assignment");
      }
    });
  });

  window.addEventListener("click", (e) => {
    if (e.target === viewSubmissionsModal) closeSubmissionsModal();
  });
});
