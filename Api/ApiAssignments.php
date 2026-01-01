<?php

require_once __DIR__ . "/../controler/AssignmentsController.php";

session_start();

$controller = new AssignmentsController();

$action = $_GET['action'] ?? '';

switch ($action) {

    case 'bringAssignments':
        $assignments = $controller->fetchAllAssignments();

        echo json_encode([
            'success' => true,
            'data' => $assignments ? $assignments : []
        ]);
        break;

    case 'bringAssignmentsByCourse':
        $courseId = $_GET['courseId'] ?? null;

        if (!$courseId) {
            echo json_encode([
                'success' => false,
                'message' => 'No course ID provided'
            ]);
            break;
        }

        $assignments = $controller->fetchAssignmentsByCourse($courseId);

        echo json_encode([
            'success' => true,
            'data' => $assignments ? $assignments : []
        ]);
        break;

    case 'addAssignment':
        $courseId = $_POST['courseId'] ?? null;
        $title = $_POST['title'] ?? null;
        $description = $_POST['description'] ?? null;
        $deadline = $_POST['deadline'] ?? null;

        $success = (
            $courseId && $title && $deadline &&
            $_SERVER['REQUEST_METHOD'] === 'POST'
        )
            ? $controller->createAssignment($courseId, $title, $description, $deadline)
            : false;

        echo json_encode([
            'success' => (bool) $success,
            'message' => $success
                ? 'Assignment added!'
                : 'Invalid request or missing data'
        ]);
        break;

    case 'updateAssignment':
        $assignmentId = $_POST['assignmentId'] ?? null;
        $title = $_POST['title'] ?? null;
        $description = $_POST['description'] ?? null;
        $deadline = $_POST['deadline'] ?? null;

        $success = (
            $assignmentId && $title && $deadline &&
            $_SERVER['REQUEST_METHOD'] === 'POST'
        )
            ? $controller->updateAssignment($assignmentId, $title, $description, $deadline)
            : false;

        echo json_encode([
            'success' => (bool) $success,
            'message' => $success
                ? 'Assignment updated!'
                : 'Invalid request or missing data'
        ]);
        break;

    case 'deleteAssignment':
        $assignmentId = $_POST['assignmentId'] ?? null;

        $success = (
            $assignmentId &&
            $_SERVER['REQUEST_METHOD'] === 'POST'
        )
            ? $controller->deleteAssignment($assignmentId)
            : false;

        echo json_encode([
            'success' => (bool) $success,
            'message' => $success
                ? 'Assignment deleted!'
                : 'Invalid request or missing assignment ID'
        ]);
        break;
    case 'bringAssignmentsWithCourse':
        $assignments = $controller->fetchAssignmentsWithCourse();

        echo json_encode([
            'success' => true,
            'data' => $assignments ? $assignments : []
        ]);
        break;

    default:
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Invalid action'
        ]);
}
