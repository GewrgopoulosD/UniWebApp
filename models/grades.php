<?php

require_once __DIR__ . "/../config/config.php";

class Grades
{
    private $connection;

    public function __construct()
    {
        $this->connection = $GLOBALS['pdo'];
    }
    //student grades 
    public function getGradesForId($userId)
    {
        $sql = "SELECT a.assignment_title, c.title_course, a.deadline,COALESCE(s.grade, 0) AS grade
                FROM assignments a
                JOIN courses c ON a.course_id = c.course_id
                LEFT JOIN submissions s ON a.assignment_id = s.assignment_id
                AND s.user_id = :user_id
                order by a.deadline";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllStudents()
    {
        $sql = "
        SELECT user_id,username,email
        FROM users 
        Where role_id = 1
        ORDER BY username;
    ";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
