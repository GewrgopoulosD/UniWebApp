<?php

require_once __DIR__ . "/../controler/GradesController.php";

session_start();

$controller = new GradesController();

$action = $_GET['action'] ?? '';

switch ($action) {

    case 'bringStudentGrades':
        $studentId = $_SESSION['user_id'] ?? null;

        $grades = $controller->fetchGradesForStudent();

        echo json_encode([
            'success' => true,
            'grades' => $grades
        ]);
        break;

    case 'bringAllStudents'://test 

        $students = $controller->fetchGradesForTeachers();
        echo json_encode([
            'success' => true,
            'students' => $students
        ]);
        break;

    case 'bringGradesForStudent':

        $studentId = $_GET['user_id'] ?? null;
        if ($studentId) {
            $grades = $controller->fetchGradesForStudentById($studentId);
            echo json_encode([
                'success' => true,
                'grades' => $grades
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'No user_id provided'
            ]);
        }
        break;

    case 'SearchingFor':

        $studentName = $_GET['username'] ?? null;

        if ($studentName !== null) {
            $grades = $controller->fetchStudentByName($studentName);
            echo json_encode([
                'success' => true,
                'grades' => $grades
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'There is not user with this username'
            ]);
        }
        break;

    default:
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Invalid action'
        ]);
        break;



}