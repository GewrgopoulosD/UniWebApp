<?php

require_once "../models/Course.php";

class CourseController
{
    private $courseModel;

    public function __construct()
    {
        $this->courseModel = new Course();
    }

    public function fetchAllCourses()
    {
        $courses = $this->courseModel->getAllCourses();

        header('Content-Type: application/json');//inform the browser that we send json data
        echo json_encode($courses);// Convert PHP array to JSON to take it from js
        exit;
    }

    public function createCourse($title)
    {
        session_start();
        $professorId = $_SESSION['user_id'];
        return $this->courseModel->addCourse($title, $professorId);
    }

    public function deleteCourse($courseId)
    {
        return $this->courseModel->deleteCourse($courseId);
    }

    public function updateCourse($courseId, $newTitle)
    {
        return $this->courseModel->updateCourse($courseId, $newTitle);
    }
}