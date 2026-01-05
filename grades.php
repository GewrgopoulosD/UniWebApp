<?php
session_start();

require_once "controler/UserControler.php";
require_once "models/Users.php";
require_once "models/Students.php";
require_once "models/Teachers.php";
require_once "controler/GradesController.php";

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

$gradesController = new GradesController();
$students = [];

if ($userType === 'teacher') {
    $students = $gradesController->fetchGradesForTeachers();
}

$pageTitle = "Education University | Grades";
$jsFile = "grades.js";
$pageCssFiles = "gradesCss.css";


ob_start();
?>

<h2>Welcome
    <?php echo $userType === 'teacher' ? 'Mr. ' : 'Student '; ?>
    <?php echo htmlspecialchars($username); ?>
</h2>
<?php if ($userType === 'student'): ?>
    <div class="gradesContainer">
        <h3>My Grades</h3>
    </div>

<?php else: ?>

    <div class="gradesContainer">
        <h3>Available students</h3>

        <?php if ($students && count($students) > 0): ?>
            <div class="gradesList">
                <?php foreach ($students as $student): ?>
                    <div class="studentCard" data-student-id="<?php echo $student['user_id']; ?>"
                        data-student-username="<?php echo htmlspecialchars($student['username']); ?>">
                        <h4 class="studentUsernameLink">
                            <a href="javascript:void(0)">
                                <?php echo htmlspecialchars($student['username']); ?> ---
                                <?php echo htmlspecialchars($student['email']); ?>
                            </a>
                        </h4>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="no-student">No student available.</p>
        <?php endif; ?>
    </div>

<?php endif; ?>

<!--preview grades modal-->
<div id="previewGradesModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="addNameHeader"></h2>
            <span class="close" id="closePreviewModal">×</span>
        </div>
        <div class="modal-body">
            <h3 id="preview_title">Grades</h3>
            <p id="preview_description"></p>
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