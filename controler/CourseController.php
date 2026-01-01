<?php

require_once __DIR__ . '/../models/Course.php';


class CourseController // we make a courseController class to handle users requests related with courses (from here will call course model functions)
{
    private $courseModel;

    public function __construct()
    {
        $this->courseModel = new Course();
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

    public function fetchAllCourses()
    {
        return $this->courseModel->getAllCourses();
    }

    public function createCourse($title)
    {
        $this->isTeacher();
        $professorId = $_SESSION['user_id'];
        return $this->courseModel->addCourse($title, $professorId);
    }

    public function deleteCourse($courseId)
    {
        $this->isTeacher();
        return $this->courseModel->deleteCourse($courseId);
    }

    public function updateCourse($courseId, $newTitle)
    {
        $this->isTeacher();
        return $this->courseModel->updateCourse($courseId, $newTitle);
    }

    public function findTitleCourses($courseId)
    {
        return $this->courseModel->findCourseTitle($courseId);
    }
}