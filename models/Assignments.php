<?php

require_once __DIR__ . "/../config/config.php";

class Assignments
{
    private $connection;

    public function __construct()
    {
        $this->connection = $GLOBALS['pdo'];
    }

    public function getAssignments()
    {

        $statement = $this->connection->prepare("select * from assignments order by created_at");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addAssignment($courseId, $assignmentTitle, $assignmentDescription, $deadline)
    {
        $statement = $this->connection->prepare(
            "INSERT INTO assignments (course_id,assignment_title,assignment_description,deadline) VALUES (:course_id, :assignment_title, :assignment_description, :deadline)"
        );

        return $statement->execute([':course_id' => $courseId, ':assignment_title' => $assignmentTitle, ':assignment_description' => $assignmentDescription, ':deadline' => $deadline]);
    }

    public function deleteAssignment($assignmentId)
    {
        $statement = $this->connection->prepare(
            "DELETE FROM assignments WHERE assignment_id = :assignment_id"
        );

        return $statement->execute([':assignment_id' => $assignmentId]);
    }

    public function updateAssignment($assignmentId, $assignmentTitle, $assignmentDescription, $deadline) //make a function to update a course in the db
    {
        $statement = $this->connection->prepare(
            "UPDATE assignments SET assignment_title = :assignment_title, 
                                           assignment_description = :assignment_description,
                                           deadline = :deadline
                                       WHERE assignment_id  = :assignment_id"
        );

        return $statement->execute([
            ':assignment_title' => $assignmentTitle,
            ':assignment_description' => $assignmentDescription,
            ':deadline' => $deadline,
            ':assignment_id' => $assignmentId
        ]);
    }

    public function getAssignmentsByCourse($courseId)
    {
        $statement = $this->connection->prepare(
            "SELECT * FROM assignments WHERE course_id = :course_id"
        );
        $statement->execute([':course_id' => $courseId]);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAssignmentsWithCourse()
    {
        $statement = $this->connection->prepare(
            "SELECT a.assignment_id,
                a.assignment_title,
                a.assignment_description,
                a.deadline,
                c.title_course
         FROM assignments a
         JOIN courses c ON a.course_id = c.course_id
         ORDER BY a.created_at"
        );
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}