<?php

require_once "../config/config.php";

class Course
{
    private $connection;

    public function __construct()
    {
        $this->connection = $GLOBALS['pdo'];
    }

    public function getAllCourses()
    {
        $statement = $this->connection->prepare("select title_course from Courses");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC); //returns an array indexed by column name as returned in your result set
    }

    public function addCourse($title, $professorId)
    {
        $statement = $this->connection->prepare(
            "INSERT INTO courses (title_course, professor_id) VALUES (:title, :professorId)"
        );

        return $statement->execute([':title' => $title, ':professorId' => $professorId]);
    }
}
