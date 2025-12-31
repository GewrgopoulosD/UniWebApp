<?php

require_once __DIR__ . "/../models/Topic.php";

class TopicControllers
{
    private $topicModel;

    public function __construct()
    {
        $this->topicModel = new Topic(); //when we will create an instance of controller we will have access to db with topic model 
    }

    function isTeacher()
    { //make a function which see if the user is teacher, if no, we give 403 forbidden access
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

    public function fetchTopicsById($courseId) //make a function to fetch topics using topic model
    {

        return $this->topicModel->getTopics($courseId);//if user is a teacher (else exit)

    }

    public function createTopic($title, $resource, $courseId)
    { //method to create a topic using topic model function

        $this->isTeacher();
        return $this->topicModel->addTopic($title, $resource, $courseId);
    }

    public function updateTopic($topicId, $newTitle, $newResource)
    {

        $this->isTeacher();
        return $this->topicModel->updateTopic($topicId, $newTitle, $newResource);
    }

    public function deleteTopic($topicId)
    {

        $this->isTeacher();
        return $this->topicModel->deleteTopic($topicId);
    }
}

