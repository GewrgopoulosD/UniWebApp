<?php

require_once __DIR__ . "/../models/grades.php";

class GradesController
{

    private $gradesModel;

    public function __construct()
    {
        $this->gradesModel = new Grades();
    }

    private function isTeacher()
    {
        if (
            !isset($_SESSION['user_id']) ||
            !isset($_SESSION['role_id']) ||
            $_SESSION['role_id'] !== 0
        ) {
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'error' => 'FORBIDDEN'
            ]);
            exit;
        }
    }

    public function fetchGradesForStudent()
    {
        $userId = $_SESSION['user_id'];
        return $this->gradesModel->getGradesForId($userId);
    }

    public function fetchGradesForTeachers()
    {
        $this->isTeacher();
        return $this->gradesModel->getAllStudents();
    }

    public function fetchGradesForStudentById($userId)
    {
        $this->isTeacher();
        return $this->gradesModel->getGradesForId($userId);
    }
}