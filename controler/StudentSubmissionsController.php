<?php

require_once __DIR__ . '/../models/StudentSubmissions.php';

class StudentSubmissionsController
{
    private $submissionsModel;

    public function __construct()
    {
        $this->submissionsModel = new StudentSubmissions();
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

    private function isStudent()
    {
        if (
            !isset($_SESSION['user_id']) ||
            !isset($_SESSION['role_id']) ||
            $_SESSION['role_id'] !== 1
        ) {
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'error' => 'FORBIDDEN'
            ]);
            exit;
        }
    }

    //student can see the assignments
    public function fetchAssignmentsForStudent()
    {
        $this->isStudent();
        $studentId = $_SESSION['user_id'];
        return $this->submissionsModel->getAssignmentsForStudent($studentId);
    }

    // student submission
    public function submitAssignment($assignmentId, $filePath = null)
    {
        $this->isStudent();
        $studentId = $_SESSION['user_id'];
        return $this->submissionsModel->submitAssignment($assignmentId, $studentId, $filePath);
    }

    public function fetchSubmissionsByAssignment($assignmentId)
    {
        $this->isTeacher();
        return $this->submissionsModel->getSubmissionsByAssignment($assignmentId);
    }

    //grading
    public function gradeSubmission($submissionId, $grade)
    {
        $this->isTeacher();
        return $this->submissionsModel->gradeSubmission($submissionId, $grade);
    }
}