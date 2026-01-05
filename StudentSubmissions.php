<?php
session_start();

require_once "controler/UserControler.php";
require_once "models/Users.php";
require_once "models/Students.php";
require_once "models/Teachers.php";
require_once "controler/AssignmentsController.php";
require_once "controler/StudentSubmissionsController.php";

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
$studentSubmission = new StudentSubmissions();

if ($userType === 'student') {
    $assignments = $studentSubmission->getAssignmentsForStudent($_SESSION['user_id']);
} else {
    $assignments = $assignmentController->fetchAssignmentsWithCourse();
}

$pageTitle = "Education University | Student Submissions";
$jsFile = "studentSubmission.js";
$pageCssFiles = "studentSubmission.css";


ob_start();
?>


<h2>Welcome
    <?php echo $userType === 'teacher' ? 'Mr. ' : 'Student '; ?>
    <?php echo htmlspecialchars($username); ?>
</h2>

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
                            <button class="btn-view-submissions" data-assignment-id="<?= $assignment['assignment_id']; ?>"
                                data-title="<?= htmlspecialchars($assignment['assignment_title']); ?>"
                                data-course="<?= htmlspecialchars($assignment['title_course']); ?>">
                                View submissions
                            </button>
                        <?php endif; ?>

                        <?php if ($userType === 'student'): ?>
                            <button class="btn-submit-assignment" data-assignment-id="<?= $assignment['assignment_id'] ?>"
                                <?= $assignment['status'] === 'Submitted' ? 'disabled' : '' ?>> <!--hiddenn or not -->
                                <?= $assignment['status'] === 'Submitted' ? '✔ Submitted' : 'Submit' ?>
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

<!-- View Submissions Modal -->
<div id="viewSubmissionsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="submissionsModalTitle">Assignment Submissions</h2>
            <span class="close" id="closeSubmissionsModal">×</span>
        </div>

        <div class="modal-body">
            <h4 id="modalAssignmentTitle"></h4>
            <table class="submissionsTable">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Status</th>
                        <th>Submission Date</th>
                        <th>Grade</th>
                    </tr>
                </thead>
                <tbody id="submissionsTableBody">
                    <!-- TODO : remember fill it  -->
                </tbody>
            </table>
        </div>

        <div class="modal-footer">
            <button class="btn-secondary" id="closeSubmissionsBtn">Close</button>
        </div>
    </div>
</div>




<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>