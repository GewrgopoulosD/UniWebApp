import { BurgerMenu } from "./global.js";
BurgerMenu();

document.addEventListener("DOMContentLoaded", () => {
  //add modal
  const addModal = document.getElementById("addAssignmentsModal");
  const addForm = document.getElementById("AddAssignmentsForm");
  const addTitleInput = document.getElementById("add_assignments_title");

  document.getElementById("addAssignmentBtn")?.addEventListener("click", () => {
    addModal.classList.add("active");
    addTitleInput.focus();
  });

  document
    .getElementById("closeAddModal")
    ?.addEventListener("click", closeAddModal);

  document
    .getElementById("cancelAddBtn")
    ?.addEventListener("click", closeAddModal);

  function closeAddModal() {
    addModal.classList.remove("active");
    addForm.reset();
  }

  addForm?.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(addForm);

    try {
      const response = await fetch(
        "./Api/ApiAssignments.php?action=addAssignment",
        {
          method: "POST",
          body: formData,
        }
      );

      const result = await response.json();

      if (result.success) {
        alert("Assignment added successfully!");
        location.reload();
      } else {
        alert(result.message || "Failed to add assignment");
      }
    } catch (error) {
      console.error("Error adding assignment:", error);
      alert("An error occurred");
    }
  });

  //edit modal
  const editModal = document.getElementById("editAssignmentsModal");
  const editForm = document.getElementById("editAssignmentsForm");
  const editIdInput = document.getElementById("edit_assignment_id");
  const editTitleInput = document.getElementById("edit_assignment_title");
  const editDescriptionInput = document.getElementById(
    "edit_assignment_description"
  );
  const editDeadlineInput = document.getElementById("edit_assignment_deadline");

  const editBtns = document.querySelectorAll(".btn-edit");

  window.openEditModal = function (id, title, description, deadline) {
    editIdInput.value = id;
    editTitleInput.value = title;
    editDescriptionInput.value = description;
    editDeadlineInput.value = deadline;

    editModal.classList.add("active");
    editTitleInput.focus();
  };

  editBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      window.openEditModal(
        btn.dataset.id,
        btn.dataset.title,
        btn.dataset.description,
        btn.dataset.deadline
      );
    });
  });

  document
    .getElementById("closeEditModal")
    ?.addEventListener("click", closeEditModal);

  document
    .getElementById("cancelEditBtn")
    ?.addEventListener("click", closeEditModal);

  function closeEditModal() {
    editModal.classList.remove("active");
    editForm.reset();
  }

  editForm?.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(editForm);

    try {
      const response = await fetch(
        "./Api/ApiAssignments.php?action=updateAssignment",
        {
          method: "POST",
          body: formData,
        }
      );

      const result = await response.json();

      if (result.success) {
        alert("Assignment updated successfully!");
        location.reload();
      } else {
        alert(result.message || "Failed to update assignment");
      }
    } catch (error) {
      console.error("Error updating assignment:", error);
      alert("An error occurred");
    }
  });

  //delete modal
  const deleteModal = document.getElementById("deleteAssignmentsModal");
  const deleteForm = document.getElementById("deleteAssignmentsForm");
  const deleteIdInput = document.getElementById("delete_assignment_id");
  const deleteBtns = document.querySelectorAll(".btn-delete");

  window.openDeleteModal = function (assignmentId) {
    deleteIdInput.value = assignmentId;
    deleteModal.classList.add("active");
  };

  deleteBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      window.openDeleteModal(btn.dataset.id);
    });
  });

  document
    .getElementById("closeDeleteModal")
    ?.addEventListener("click", closeDeleteModal);

  document
    .getElementById("cancelDeleteBtn")
    ?.addEventListener("click", closeDeleteModal);

  function closeDeleteModal() {
    deleteModal.classList.remove("active");
    deleteForm.reset();
  }

  deleteForm?.addEventListener("submit", async (e) => {
    e.preventDefault();

    if (!confirm("Really delete this assignment? This cannot be undone!"))
      return;

    const formData = new FormData(deleteForm);

    try {
      const response = await fetch(
        "./Api/ApiAssignments.php?action=deleteAssignment",
        {
          method: "POST",
          body: formData,
        }
      );

      const result = await response.json();

      if (result.success) {
        alert("Assignment deleted successfully!");
        location.reload();
      } else {
        alert(result.message || "Failed to delete assignment");
      }
    } catch (error) {
      console.error("Error deleting assignment:", error);
      alert("An error occurred");
    }
  });

  //preview modal
  const previewModal = document.getElementById("previewAssignmentsModal");
  const previewTitle = document.getElementById("preview_title");
  const previewDescription = document.getElementById("preview_description");
  const previewDeadline = document.getElementById("preview_deadline");
  const closePreviewModalBtn = document.getElementById("closePreviewModal");
  const closePreviewBtn = document.getElementById("closePreviewBtn");

  //make the cards clickable -> open preview modal
  const assignments = document.querySelectorAll(".assignmentsCard");

  assignments.forEach((card) => {
    card.style.cursor = "pointer"; //make assignments looks like href

    card.addEventListener("click", () => {
      const title = card.querySelector(".AssignmentTitle").innerText;
      const description = card.dataset.description;
      const deadline = card.dataset.deadline;

      previewTitle.innerText = title;
      previewDescription.innerText = description;
      previewDeadline.innerText = "Deadline: " + deadline;

      previewModal.classList.add("active");
    });

    //except these keys(dont open preview modal)
    card.querySelectorAll(".btn-edit, .btn-delete").forEach((btn) => {
      btn.addEventListener("click", (e) => e.stopPropagation());
    });
  });

  const closePreviewModal = () => {
    previewModal.classList.remove("active");
  };

  closePreviewModalBtn.addEventListener("click", closePreviewModal);
  closePreviewBtn.addEventListener("click", closePreviewModal);

  //click outside of modal -> close modal
  window.addEventListener("click", (e) => {
    if (e.target === addModal) closeAddModal();
    if (e.target === editModal) closeEditModal();
    if (e.target === deleteModal) closeDeleteModal();
    if (e.target === previewModal) closePreviewModal();
  });
});
