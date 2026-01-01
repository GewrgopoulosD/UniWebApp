<?php

require_once __DIR__ . '/../models/Assignments.php';

class AssignmentsController
{
    private $assignmentsModel;

    public function __construct()
    {
        $this->assignmentsModel = new Assignments();
    }

    private function isTeacher() //make a function which see if the user is teacher, if no, we give 403 forbidden access
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
    // fetch all assignments
    public function fetchAllAssignments()
    {
        return $this->assignmentsModel->getAssignments();
    }

    // create new assignment
    public function createAssignment($courseId, $title, $description, $deadline)
    {
        $this->isTeacher();

        return $this->assignmentsModel->addAssignment(
            $courseId,
            $title,
            $description,
            $deadline
        );
    }

    // delete assignment
    public function deleteAssignment($assignmentId)
    {
        $this->isTeacher();
        return $this->assignmentsModel->deleteAssignment($assignmentId);
    }

    // update assignment
    public function updateAssignment($assignmentId, $title, $description, $deadline)
    {
        $this->isTeacher();

        return $this->assignmentsModel->updateAssignment(
            $assignmentId,
            $title,
            $description,
            $deadline
        );
    }

    public function fetchAssignmentsByCourse($courseId)
    {
        return $this->assignmentsModel->getAssignmentsByCourse($courseId);
    }

    public function fetchAssignmentsWithCourse()
    {
        return $this->assignmentsModel->getAssignmentsWithCourse();
    }

}