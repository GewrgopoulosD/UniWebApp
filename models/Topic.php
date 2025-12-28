<?php

require_once "../config/config.php";

class Topic
{ // make a class Topic to handle the topic's-database operations

    private $connection;

    public function __construct()
    { // when we create an instance, we establish the connection with db
        $this->connection = $GLOBALS['pdo'];
    }

    public function getTopics($courseId) // we make a function with a query to find all the topics which is connected with the courseId 
    {
        $statement = $this->connection->prepare("SELECT topic_title
                                                        FROM topics 
                                                        WHERE course_id = :courseId");
        $statement->execute([':courseId' => $courseId]);
        return $statement->fetchAll(PDO::FETCH_ASSOC); //returns an array indexed by column name as returned in your result set
    }

    public function addTopic($title, $resource, $courseId)//make a function to add new topics to the db
    {
        $statement = $this->connection->prepare(
            "INSERT INTO topics (topic_title, resource_url, course_id )
                    VALUES (:topic_title, :resource_url, :course_id)"
        );

        return $statement->execute([':topic_title' => $title, ':resource_url' => $resource, ':course_id' => $courseId]);
    }

    public function updateTopic($topicId, $newTitle, $newResource)
    { //make a function to update an existing topic in db
        $statement = $this->connection->prepare(
            "UPDATE topics
         SET topic_title = :topic_title, resource_url = :resource_url
         WHERE topic_Id = :topicId"
        );
        return $statement->execute([
            ':topic_title' => $newTitle,
            ':resource_url' => $newResource,
            ':topicId' => $topicId
        ]);

    }

    public function deleteTopic($topicId)
    { //function to delete a topic by id
        $statement = $this->connection->prepare("DELETE FROM topics WHERE topic_id = :topicId");
        return $statement->execute([':topicId' => $topicId]);
    }

}