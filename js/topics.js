import { BurgerMenu } from "./global.js";

BurgerMenu();

document.addEventListener("DOMContentLoaded", () => {
  //when the dom is readty

  //add modal
  const addModal = document.getElementById("addTopicsModal"); //find modal
  const addForm = document.getElementById("AddTopicForm"); //find form
  const addTitleInput = document.getElementById("add_topic_title"); //find input
  const addUrlInput = document.getElementById("add_topic_url"); //find url topic

  document.getElementById("addTopicsBtn")?.addEventListener("click", () => {
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

  addForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(addForm);
    try {
      const response = await fetch("./Api/ApiTopics.php?action=addTopic", {
        method: "POST",
        body: formData,
      });
      const result = await response.json();

      if (result.success) {
        alert("Topic added successfully!");
        location.reload();
      } else {
        alert(result.message || "Failed to add topic");
      }
    } catch (error) {
      console.error("Error adding topic:", error);
      alert("An error occurred");
    }
  });

  //edit modal
  const editModal = document.getElementById("editTopicsModal"); // modal
  const editForm = document.getElementById("editTopicForm"); // form edit
  const editTitleInput = document.getElementById("edit_title_topic"); // new title
  const editIdInput = document.getElementById("edit_topic_id"); //id
  const editUrlInput = document.getElementById("edit_url_topic"); //url
  const editBtn = document.querySelectorAll(".btn-edit"); //edit btn

  window.openEditModal = function (topicId, topicTitle, topicUrl) {
    editIdInput.value = topicId;
    editTitleInput.value = topicTitle;
    editUrlInput.value = topicUrl;
    editModal.classList.add("active");
    editTitleInput.focus();
  };

  editBtn.forEach((btn) => {
    btn.addEventListener("click", () => {
      window.openEditModal(btn.dataset.id, btn.dataset.title, btn.dataset.url);
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

  editForm.addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = new FormData(editForm);

    try {
      const response = await fetch("./Api/ApiTopics.php?action=updateTopic", {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (result.success) {
        alert("Topic updated successfully!");
        location.reload();
      } else {
        alert(result.message || "Failed to update topic");
      }
    } catch (error) {
      console.error("Error updating topic:", error);
      alert("An error occurred");
    }
  });

  // delete modal
  const deleteModal = document.getElementById("deleteTopicModal");
  const deleteForm = document.getElementById("deleteTopicForm");
  const deleteIdInput = document.getElementById("delete_topic_id");
  const deletebtn = document.querySelectorAll(".btn-delete");

  window.openDeleteModal = async function (topicId) {
    deleteIdInput.value = topicId;
    deleteModal.classList.add("active");
  };

  deletebtn.forEach((btn) => {
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

  deleteForm.addEventListener("submit", async function (e) {
    e.preventDefault();

    if (!confirm("Really delete this topic? This cannot be undone!")) return;

    const formData = new FormData(deleteForm);

    try {
      const response = await fetch("./Api/ApiTopics.php?action=deleteTopic", {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (result.success) {
        alert("Topic deleted successfully!");
        location.reload();
      } else {
        alert(result.message || "Failed to delete topic");
      }
    } catch (error) {
      console.error("Error deleting topic:", error);
      alert("An error occurred");
    }
  });

  // Close modals on outside click
  window.addEventListener("click", function (e) {
    if (e.target === editModal) closeEditModal();
    if (e.target === deleteModal) closeDeleteModal();
    if (e.target === addModal) closeAddModal();
  });
});
