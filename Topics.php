<?php
session_start();

require_once "controler/UserControler.php";
require_once "controler/TopicController.php";
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

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $userControler->logout();
}

$courseId = $_GET['courseId'] ?? null;
if ($courseId === null || !is_numeric($courseId)) {
    header("Location: index.php");
    exit();
}


$courseController = new CourseController();
$courseName = $courseController->findTitleCourses($courseId);

$topicController = new TopicControllers();
$topics = $topicController->fetchTopicsById($courseId);

$pageTitle = "Education University | Topic " . $courseId;
$jsFile = "topics.js";
$pageCssFiles = "topics.css";

ob_start();
?>

<h2>Welcome
    <?php echo $userType === 'teacher' ? 'Mr. ' : 'Student '; ?>
    <?php echo htmlspecialchars($username); ?>
</h2>

<?php if ($userType === 'teacher'): ?>
    <div class="topicsActions">
        <button id="addTopicsBtn" class="btn-primary">
            <i class="material-icons">add</i>
            Add New Topic
        </button>
    </div>
<?php endif; ?>

<div class="topicContainer">
    <h3><?php echo htmlspecialchars($courseName['title_course']); ?> topics</h3>

    <?php if ($topics && count($topics) > 0): ?>
        <div class="topicsList">
            <?php foreach ($topics as $topic): ?>
                <div class="topicCard" data-topic-id="<?php echo $topic['topic_id']; ?>">
                    <h4>
                        <a href="<?php echo $topic['resource_url']; ?>" target="_blank" rel="noopener noreferrer"
                            class="topicTitleLink">
                            <?php echo htmlspecialchars($topic['topic_title']); ?>
                        </a>
                    </h4>
                    <div class="topicCardActions">
                        <?php if ($userType === 'teacher'): ?>
                            <button class="btn-edit" data-id="<?= $topic['topic_id'] ?>"
                                data-title="<?= htmlspecialchars($topic['topic_title'], ENT_QUOTES) ?>"
                                data-url="<?= htmlspecialchars($topic['resource_url'], ENT_QUOTES) ?>">
                                <i class="material-icons">edit</i>
                            </button>
                            <button class="btn-delete" data-id="<?php echo $topic['topic_id']; ?>">
                                <i class="material-icons">delete</i>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No topics found for this course.</p>
    <?php endif; ?>
</div>

<!-- add topic Modal -->
<div id="addTopicsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Add Topic</h2>
            <span class="close" id="closeAddModal">×</span>
        </div>
        <div class="modal-body">
            <form id="AddTopicForm">
                <label for="add_topic_title">Topic Title:</label>
                <input type="text" id="add_topic_title" name="title" required autocomplete="off">
                <label for="add_topic_url">Topic url:</label>
                <input type="text" id="add_topic_url" name="resource" required autocomplete="off">
                <input type="hidden" name="courseId" value="<?php echo $courseId; ?>">
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-secondary" id="cancelAddBtn">Cancel</button>
            <button type="submit" form="AddTopicForm" class="btn-primary">Add topic</button>
        </div>
    </div>
</div>

<!-- edit Course Modal -->
<div id="editTopicsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Course</h2>
            <span class="close" id="closeEditModal">×</span>
        </div>
        <div class="modal-body">
            <form id="editTopicForm">
                <input type="hidden" name="topicId" id="edit_topic_id">
                <label for="edit_title_topic">Topic Title:</label>
                <input type="text" id="edit_title_topic" name="newTitle" required autocomplete="off">
                <label for="edit_url_topic">Topic Title:</label>
                <input type="text" id="edit_url_topic" name="newResource" required autocomplete="off">
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-secondary" id="cancelEditBtn">Cancel</button>
            <button type="submit" form="editTopicForm" class="btn-primary">Save Changes</button>
        </div>
    </div>
</div>

<!-- Delete Course Modal  -->
<div id="deleteTopicModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Confirm Deletion</h2>
            <span class="close" id="closeDeleteModal">×</span>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete this topic?</p>
            <p class="delete-warning">This action cannot be undone!</p>

            <form id="deleteTopicForm">
                <input type="hidden" name="topicId" id="delete_topic_id">
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-secondary" id="cancelDeleteBtn">Cancel</button>
            <button type="submit" form="deleteTopicForm" class="btn-confirm-delete">Yes, Delete Topic</button>
        </div>
    </div>
</div>


<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>