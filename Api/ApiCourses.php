<?php
require_once __DIR__ . "/../controler/CourseController.php";

$controller = new CourseController();// create the controller to can use its methods from outside

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'bringCourses':
        $controller->fetchAllCourses();// call the method to fetch all courses
        break;

    case 'addCourse':
        $title = $_POST['title'] ?? null;

        $success = $title && $_SERVER['REQUEST_METHOD'] === 'POST'
            ? $controller->createCourse($title)
            : false;

        echo json_encode([
            'success' => (bool) $success,
            'message' => $success ? 'Course added!' : ($title ? 'Invalid request method' : 'No title provided')
        ]);

        break;

    default:
        http_response_code(400);
        echo json_encode(["error" => "Invalid action"]);
}

