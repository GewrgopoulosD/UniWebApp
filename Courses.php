<?php
session_start();

require_once "controler/UserControler.php";
require_once "controler/CourseController.php";
require_once "models/Users.php";
require_once "models/Students.php";
require_once "models/Teachers.php";

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}


$userControler = new UserControler();
$userData = $userControler->getCurrentUser();
$userType = $userData['userType'] ?? null;
$username = $userData['username'] ?? null;

$courseController = new CourseController();
$courses = $courseController->fetchAllCourses();

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
  $userControler->logout();
}

if (!$userType || ($userType !== 'student' && $userType !== 'teacher')) {
  header("Location: index.php");
  exit();
}

$pageTitle = "Education University | Courses";
$jsFile = "courses.js";
$pageCssFiles = "courses.css";

ob_start();
?>

<h2>Welcome <?php echo $userType === 'teacher' ? 'Mr. ' : 'Student '; ?><?php echo htmlspecialchars($username); ?></h2>

<?php if ($userType === 'teacher'): ?>
  <div class="courseActions">
    <button id="addCourseBtn" class="btn-primary">
      <i class="material-icons">add</i>
      Add New Course
    </button>
  </div>
<?php endif; ?>

<div class="coursesContainer">
  <h3>Available Courses</h3>

  <?php if ($courses && count($courses) > 0): ?>
    <div class="coursesList">
      <?php foreach ($courses as $course): ?>
        <div class="courseCard" data-course-id="<?php echo $course['course_id']; ?>">
          <h4>
            <a href="Topics.php?courseId=<?php echo $course['course_id']; ?>" class="courseTitleLink">
              <?php echo htmlspecialchars($course['title_course']); ?>
            </a>
          </h4>

          <div class="courseCardActions">
            <?php if ($userType === 'teacher'): ?>
              <button class="btn-edit"
                onclick="openEditModal(<?php echo $course['course_id']; ?>, '<?php echo htmlspecialchars($course['title_course'], ENT_QUOTES); ?>')">
                <i class="material-icons">edit</i>
              </button>
              <button class="btn-delete" onclick="openDeleteModal(<?php echo $course['course_id']; ?>)">
                <i class="material-icons">delete</i>
              </button>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p class="no-courses">No courses available yet.</p>
  <?php endif; ?>
</div>

<!-- add Course Modal -->
<div id="addCourseModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h2>Add Course</h2>
      <span class="close" id="closeAddModal">×</span>
    </div>
    <div class="modal-body">
      <form id="AddCourseForm">
        <label for="add_course_title">Course Title:</label>
        <input type="text" id="add_course_title" name="title" required autocomplete="off">
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn-secondary" id="cancelAddBtn">Cancel</button>
      <button type="submit" form="AddCourseForm" class="btn-primary">Add course</button>
    </div>
  </div>
</div>

<!-- edit Course Modal -->
<div id="editCourseModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h2>Edit Course</h2>
      <span class="close" id="closeEditModal">×</span>
    </div>
    <div class="modal-body">
      <form id="editCourseForm">
        <input type="hidden" name="courseId" id="edit_course_id"> <!-- Fixed name -->
        <label for="edit_title_course">Course Title:</label>
        <input type="text" id="edit_title_course" name="newTitle" required autocomplete="off"> <!-- Fixed name -->
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn-secondary" id="cancelEditBtn">Cancel</button>
      <button type="submit" form="editCourseForm" class="btn-primary">Save Changes</button>
    </div>
  </div>
</div>

<!-- Delete Course Modal -->
<div id="deleteCourseModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h2>Confirm Deletion</h2>
      <span class="close" id="closeDeleteModal">×</span>
    </div>
    <div class="modal-body">
      <p>Are you sure you want to delete this course?</p>
      <p class="delete-warning">This action cannot be undone!</p>

      <form id="deleteCourseForm">
        <input type="hidden" name="courseId" id="delete_course_id">
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn-secondary" id="cancelDeleteBtn">Cancel</button>
      <button type="submit" form="deleteCourseForm" class="btn-delete">Yes, Delete Course</button>
    </div>
  </div>
</div>


<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>