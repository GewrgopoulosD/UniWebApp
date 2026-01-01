<?php
session_start();

require_once "controler/UserControler.php";
require_once "models/Users.php";
require_once "models/Students.php";
require_once "models/Teachers.php";
require_once "controler/AssignmentsController.php";
require_once "controler/CourseController.php";

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}


$userControler = new UserControler();
$userData = $userControler->getCurrentUser();
$userType = $userData['userType'] ?? null;
$username = $userData['username'] ?? null;


if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $userControler->logout();
}

if (!$userType || ($userType !== 'student' && $userType !== 'teacher')) {
    header("Location: index.php");
    exit();
}


$assignmentController = new assignmentsController();
$assignments = $assignmentController->fetchAssignmentsWithCourse();

$courseController = new courseController();
$courses = $courseController->fetchAllCourses();

$pageTitle = "Education University | Assignments";
$jsFile = "assignments.js";
$pageCssFiles = "assignments.css";


ob_start();
?>


<h2>Welcome
    <?php echo $userType === 'teacher' ? 'Mr. ' : 'Student '; ?>
    <?php echo htmlspecialchars($username); ?>
</h2>

<?php if ($userType === 'teacher'): ?>
    <div class="AssignmentsActions">
        <button id="addAssignmentBtn" class="btn-primary">
            <i class="material-icons">add</i>
            Add New Assignments
        </button>
    </div>
<?php endif; ?>

<div class="assignmentsContainer">
    <h3>Assignments</h3>
    <?php if ($assignments && count($assignments) > 0): ?>
        <div class="assignmentsList">
            <?php foreach ($assignments as $assignment): ?>
                <div class="assignmentsCard" data-assignment-id="<?= $assignment['assignment_id']; ?>"
                    data-title="<?= htmlspecialchars($assignment['assignment_title'], ENT_QUOTES); ?>"
                    data-description="<?= htmlspecialchars($assignment['assignment_description'], ENT_QUOTES); ?>"
                    data-deadline="<?= $assignment['deadline']; ?>">
                    <h4 class="AssignmentTitle">
                        <?= htmlspecialchars($assignment['assignment_title']); ?>&nbsp;(<?= htmlspecialchars($assignment['title_course']); ?>)
                    </h4>
                    <div class="assignmentsCardActions">
                        <?php if ($userType === 'teacher'): ?>
                            <button class="btn-edit" data-id="<?= $assignment['assignment_id']; ?>"
                                data-title="<?= htmlspecialchars($assignment['assignment_title'], ENT_QUOTES); ?>"
                                data-description="<?= htmlspecialchars($assignment['assignment_description'], ENT_QUOTES); ?>"
                                data-deadline="<?= $assignment['deadline']; ?>">
                                <i class="material-icons">edit</i>
                            </button>
                            <button class="btn-delete" data-id="<?= $assignment['assignment_id']; ?>">
                                <i class="material-icons">delete</i>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No assignments found.</p>
    <?php endif; ?>
</div>

<!-- add assignments Modal -->
<div id="addAssignmentsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Add assignment</h2>
            <span class="close" id="closeAddModal">×</span>
        </div>
        <div class="modal-body">
            <form id="AddAssignmentsForm">
                <label for="add_assignments_courseId">For course:</label>
                <select id="add_assignments_courseId" name="courseId" required>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?= $course['course_id']; ?>">
                            <?= htmlspecialchars($course['title_course'], ENT_QUOTES); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <label for="add_assignments_title">Assignment Title:</label>
                <input type="text" id="add_assignments_title" name="title" required autocomplete="off">
                <label for="add_description">description:</label>
                <textarea id="add_description" name="description" required></textarea>
                <label for="add_deadline">deadline:</label>
                <input type="date" id="add_deadline" name="deadline" required>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-secondary" id="cancelAddBtn">Cancel</button>
            <button type="submit" form="AddAssignmentsForm" class="btn-primary">Add assignments</button>
        </div>
    </div>
</div>

<!-- edit assignments Modal -->
<div id="editAssignmentsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Assignments</h2>
            <span class="close" id="closeEditModal">×</span>
        </div>
        <div class="modal-body">
            <form id="editAssignmentsForm">
                <input type="hidden" name="assignmentId" id="edit_assignment_id">
                <label for="edit_assignment_title">Assignment Title:</label>
                <input type="text" id="edit_assignment_title" name="title" autocomplete="off">
                <label for="edit_assignment_description">description:</label>
                <textarea id="edit_assignment_description" name="description"></textarea>
                <label for="edit_assignment_deadline">deadline:</label>
                <input type="date" id="edit_assignment_deadline" name="deadline">
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-secondary" id="cancelEditBtn">Cancel</button>
            <button type="submit" form="editAssignmentsForm" class="btn-primary">Save Changes</button>
        </div>
    </div>
</div>

<!-- Delete assignments Modal  -->
<div id="deleteAssignmentsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Confirm Deletion</h2>
            <span class="close" id="closeDeleteModal">×</span>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete this assignments?</p>
            <p class="delete-warning">This action cannot be undone!</p>

            <form id="deleteAssignmentsForm">
                <input type="hidden" name="assignmentId" id="delete_assignment_id">
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-secondary" id="cancelDeleteBtn">Cancel</button>
            <button type="submit" form="deleteAssignmentsForm" class="btn-confirm-delete">Yes, Delete
                assignments</button>
        </div>
    </div>
</div>

<!--preview assignment modal-->
<div id="previewAssignmentsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Preview Assignment</h2>
            <span class="close" id="closePreviewModal">×</span>
        </div>
        <div class="modal-body">
            <h3 id="preview_title">Assignment Title</h3>
            <p id="preview_description">Assignment Description</p>
            <p id="preview_deadline">Deadline: </p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-secondary" id="closePreviewBtn">Close</button>
        </div>
    </div>
</div>




<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>