<?php

require_once __DIR__ . "/../controler/StudentSubmissionsController.php";

session_start();

$controller = new StudentSubmissionsController();

$action = $_GET['action'] ?? '';

switch ($action) {

    case 'bringStudentAssignments':
        $assignments = $controller->fetchAssignmentsForStudent();

        echo json_encode([
            'success' => true,
            'data' => $assignments ? $assignments : []
        ]);
        break;

    case 'submitAssignment':
        $assignmentId = $_POST['assignmentId'] ?? null;
        $filePath = $_POST['filePath'] ?? null;
        $studentId = $_SESSION['user_id'] ?? null;

        if (
            !$assignmentId ||
            !$studentId ||
            $_SERVER['REQUEST_METHOD'] !== 'POST'
        ) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid request'
            ]);
            break;
        }

        $success = $controller->submitAssignment(
            $assignmentId,
            $studentId,
            $filePath
        );

        echo json_encode([
            'success' => (bool) $success,
            'message' => $success
                ? 'Assignment submitted successfully!'
                : 'You have already submitted this assignment'
        ]);
        break;

    case 'bringSubmissionsByAssignment':
        $assignmentId = $_GET['assignmentId'] ?? null;

        if (!$assignmentId) {
            echo json_encode([
                'success' => false,
                'message' => 'No assignment ID provided'
            ]);
            break;
        }

        $submissions = $controller->fetchSubmissionsByAssignment($assignmentId);

        echo json_encode([
            'success' => true,
            'data' => $submissions ? $submissions : []
        ]);
        break;

    case 'gradeSubmission':
        $submissionId = $_POST['submissionId'] ?? null;
        $grade = $_POST['grade'] ?? null;

        $success = (
            $submissionId !== null &&
            $grade !== null &&
            $_SERVER['REQUEST_METHOD'] === 'POST'
        )
            ? $controller->gradeSubmission($submissionId, $grade)
            : false;

        echo json_encode([
            'success' => (bool) $success,
            'message' => $success
                ? 'Grade saved successfully!'
                : 'Invalid request or missing data'
        ]);
        break;

    default:
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Invalid action'
        ]);
}