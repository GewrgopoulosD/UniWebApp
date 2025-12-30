<?php
session_start();

require_once "controler/UserControler.php";
require_once "models/Users.php";
require_once "models/Students.php";
require_once "models/Teachers.php";


if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}
$pageCssFiles = ['dashCss.css'];  
$userControler = new UserControler();
$userData = $userControler->getCurrentUser();

$userType = $userData ? $userData['userType'] : null;
$username = $userData ? $userData['username'] : null;


if (!$userType || ($userType !== 'student' && $userType !== 'teacher')) {
  header("Location: index.php");
  exit();
}


if (isset($_GET['action']) && $_GET['action'] === 'logout') {
  $userControler->logout();
}


$pageTitle = "Education University | Dashboard";
$mainDivId = "dashCont";
$jsFile = "dashboard.js";


ob_start();
?>

<h2>Welcome <?php echo $userType === 'teacher' ? 'Mr. ' : 'Student '; ?><?php echo htmlspecialchars($username); ?></h2>

<div class="buttonContainer">
  <button type="button" class="coursesBtn" onclick="location.href='Courses.php'">Courses</button>
  <button type="button" class="assignmentsBtn" onclick="location.href='Assignments.php'">Assignments</button>
  <button type="button" class="studentSubmissionsBtn" onclick="location.href='Submissions.php'">Student Submissions</button>
  <button type="button" class="gradesBtn" onclick="location.href='Grades.php'">Grades</button>
</div>


<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>