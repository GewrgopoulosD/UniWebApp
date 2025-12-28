<?php

require_once __DIR__ . "/../controler/TopicController.php";

session_start();//we continue the session we took
$controller = new TopicController(); // create an instance to use its methods

$action = $_GET['action'] ?? ''; //check if there is parameter action , if exists we take it, else take ''

switch ($action) {
    case 'bringTopics':

        $courseId = $_GET['courseId'] ?? null;

        $topics = $courseId && $_SERVER['REQUEST_METHOD'] === 'GET'
            ? $controller->fetchTopicsById($courseId)
            : false;

        echo json_encode([
            'success' => (bool) $topics,
            'message' => $topics ? '' : 'Didnt found topics for course id ' . $courseId,
            'data' => $topics ? $topics : []
        ]);
        exit;

    case 'addTopic':

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $title = $_POST['title'] ?? null;
            $resource = $_POST['resource'] ?? null;
            $courseId = $_POST['courseId'] ?? null;

            if ($title && $resource && $courseId) { // check if we have all the variables
                $result = $controller->createTopic($title, $resource, $courseId);

                echo json_encode([
                    'success' => (bool) $result,
                    'message' => $result ? 'Topic created' : 'Failed to create topic'
                ]);
            } else { //if not
                echo json_encode([
                    'success' => false,
                    'message' => 'Missing title, resource, or courseId'
                ]);
            }
        }
        exit;

    case 'updateTopic':

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $topicId = $_POST['topicId'] ?? null;
            $newTitle = $_POST['newTitle'] ?? null;
            $newResource = $_POST['newResource'] ?? null;

            if ($topicId && $newTitle && $newResource) {
                $result = $controller->updateTopic($topicId, $newTitle, $newResource);

                echo json_encode([
                    'success' => (bool) $result,
                    'message' => $result ? 'Topic updated' : 'Failed to update topic'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Missing new title or new Resource'
                ]);
            }
        }
        exit;

    case 'deleteTopic':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $topicId = $_POST['topicId'] ?? null;

            if ($topicId) {
                $result = $controller->deleteTopic($topicId);

                echo json_encode([
                    'success' => (bool) $result,
                    'message' => $result ? 'Topic deleted' : 'Failed to delete topic'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Didnt found topic id ' . $topicId
                ]);
            }
        }
        exit;

    default:
        http_response_code(400);
        echo json_encode(["error" => "Invalid action"]);
        exit;
}
