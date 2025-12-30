document.addEventListener('DOMContentLoaded', () => {
    // ============= EDIT MODAL =============
    const editModal = document.getElementById('editCourseModal');
    const editForm = document.getElementById('editCourseForm');
    const editTitleInput = document.getElementById('edit_title_course');
    const editIdInput = document.getElementById('edit_course_id');

    window.openEditModal = function(courseId, courseTitle) {
        editIdInput.value = courseId;
        editTitleInput.value = courseTitle;
        editModal.classList.add('active');
        editTitleInput.focus();
    };

    document.getElementById('closeEditModal')?.addEventListener('click', closeEditModal);
    document.getElementById('cancelEditBtn')?.addEventListener('click', closeEditModal);

    function closeEditModal() {
        editModal.classList.remove('active');
        editForm.reset();
    }

    editForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(editForm);
        
        try {
            const response = await fetch('../Api/ApiCourses.php?action=updateCourse', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                alert('Course updated successfully!');
                location.reload();
            } else {
                alert(result.message || 'Failed to update course');
            }
        } catch (error) {
            console.error('Error updating course:', error);
            alert('An error occurred');
        }
    });


    const deleteModal = document.getElementById('deleteCourseModal');
    const deleteForm = document.getElementById('deleteCourseForm');
    const deleteIdInput = document.getElementById('delete_course_id');

    window.openDeleteModal = function(courseId) {
        deleteIdInput.value = courseId;
        deleteModal.classList.add('active');
    };

    document.getElementById('closeDeleteModal')?.addEventListener('click', closeDeleteModal);
    document.getElementById('cancelDeleteBtn')?.addEventListener('click', closeDeleteModal);

    function closeDeleteModal() {
        deleteModal.classList.remove('active');
        deleteForm.reset();
    }

    deleteForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        if (!confirm('Really delete this course? This cannot be undone!')) return;

        const formData = new FormData(deleteForm);

        try {
            const response = await fetch('../Api/ApiCourses.php?action=updateCourse', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                alert('Course deleted successfully!');
                location.reload();
            } else {
                alert(result.message || 'Failed to delete course');
            }
        } catch (error) {
            console.error('Error deleting course:', error);
            alert('An error occurred');
        }
    });

    // Close modals on outside click
    window.addEventListener('click', function(e) {
        if (e.target === editModal) closeEditModal();
        if (e.target === deleteModal) closeDeleteModal();
    });
});