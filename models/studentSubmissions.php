<?php

require_once __DIR__ . "/../config/config.php";

class StudentSubmissions
{

    private $connection;

    public function __construct()
    {
        $this->connection = $GLOBALS['pdo'];
    }

    public function getAssignmentsForStudent($studentId)
    {
        $sql = "
            SELECT 
                a.assignment_id,
                a.assignment_title,
                c.title_course,
                a.deadline,
                CASE 
                    WHEN s.submission_id IS NULL THEN 'Not Submitted'
                    ELSE 'Submitted'
                END AS status,
                s.submitted_at AS submission_date
            FROM assignments a
            JOIN courses c ON a.course_id = c.course_id
            LEFT JOIN submissions s 
                   ON s.assignment_id = a.assignment_id AND s.user_id = :student_id
            ORDER BY a.deadline
        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':student_id' => $studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // student sumbission 
    public function submitAssignment($assignmentId, $studentId, $filePath = null)
    {

        $checkSql = "
        SELECT submission_id 
        FROM submissions 
        WHERE assignment_id = :assignment_id 
          AND user_id = :student_id
    ";

        $stmt = $this->connection->prepare($checkSql);
        $stmt->execute([
            ':assignment_id' => $assignmentId,
            ':student_id' => $studentId
        ]);

        if ($stmt->fetch()) {//if there is a row 
            return false;
        }

        $sql = "
            INSERT INTO submissions (assignment_id, user_id, file_path, submitted_at)
            VALUES (:assignment_id, :student_id, :file_path, NOW())
        ";

        $stmt = $this->connection->prepare($sql);
        return $stmt->execute([
            ':assignment_id' => $assignmentId,
            ':student_id' => $studentId,
            ':file_path' => $filePath
        ]);
    }

    // getter sumbissions for teacher (for each assignment)
    public function getSubmissionsByAssignment($assignmentId)
    {
        $sql = "
            SELECT 
                u.username AS student_name,
                CASE 
                    WHEN s.submission_id IS NULL THEN 'Pending'
                    ELSE 'Submitted'
                END AS status,
                s.submission_id,
                s.submitted_at AS submission_date,
                s.grade
            FROM users u
            LEFT JOIN submissions s 
                   ON s.assignment_id = :assignment_id AND s.user_id = u.user_id
            WHERE u.role_id = 1
            ORDER BY u.username
        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':assignment_id' => $assignmentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // teacher grading
    public function gradeSubmission($submissionId, $grade)
    {
        $sql = "
            UPDATE submissions
            SET grade = :grade
            WHERE submission_id = :submission_id
        ";

        $stmt = $this->connection->prepare($sql);
        return $stmt->execute([
            ':grade' => $grade,
            ':submission_id' => $submissionId
        ]);
    }
}
