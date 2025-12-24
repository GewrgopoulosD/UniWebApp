<?php

require_once "../config/config.php";

class Course //make a class Course to handle all course related db operations
{
    private $connection;

    public function __construct() //in constructor connect to the database
    {
        $this->connection = $GLOBALS['pdo'];
    }

    public function getAllCourses()//make a function to fetch the courses from the fb
    {
        $statement = $this->connection->prepare("select course_id, title_course from Courses");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC); //returns an array indexed by column name as returned in your result set
    }

    public function addCourse($title, $professorId)//make a function to add a course to the db
    {
        $statement = $this->connection->prepare(
            "INSERT INTO courses (title_course, professor_id) VALUES (:title, :professorId)"
        );

        return $statement->execute([':title' => $title, ':professorId' => $professorId]);
    }

    public function deleteCourse($courseId) //make a function to delete a course from the db
    {
        $statement = $this->connection->prepare(
            "DELETE FROM courses WHERE course_id = :courseId"
        );

        return $statement->execute([':courseId' => $courseId]);
    }

    public function updateCourse($courseId, $newTitle) //make a function to update a course in the db
    {
        $statement = $this->connection->prepare(
            "UPDATE courses SET title_course = :newTitle WHERE course_id = :courseId"
        );

        return $statement->execute([':newTitle' => $newTitle, ':courseId' => $courseId]);
    }
}
