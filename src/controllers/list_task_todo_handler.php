<?php
include_once '../utils/connect_database.php';
include_once '../utils/validation.php';
include_once '../models/user_model.php';
include_once '../models/task_model.php';
include_once '../businessObject/Task.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    global $conn;
    session_start();
    $userModel = new user_model($conn);
    $taskModel = new task_model($conn);
    $userid = $_SESSION['user_id'] ?? "";

    if (isset($_POST['action']) && $_POST['action'] === 'addTask') {
        ($_POST['contentTask']) == '' ? $contentTask = "New task default" : $contentTask = $_POST['contentTask'];
        $result = $taskModel->addNewTask($userid, $contentTask);
        return $result;
    } else if (isset($_POST['action']) && $_POST['action'] === 'changeStatus') {
        $taskId = $_POST['taskId'];
        $statusTask = $_POST['statusTask'];

        $task_id = (int)$taskId;
        $status_id = (int)$statusTask;
        $taskModel->updateStatusTaskById($taskId, $status_id);
        echo 1;
    } else if (isset($_POST['action']) && $_POST['action'] === 'deleteTask') {
        $listTasks = $_POST['listTasks'] ?? [];
        foreach ($listTasks as $task) {
            $taskId = (int)$task;
            $taskModel->deletedTaskById($taskId);
        }
        $resultError[] = new ValidationError('success', 'Deleted task successfully, reload list page after 3s!');
        echo pareDataToJsonPLus($resultError);
    } else if (isset($_POST['action']) && $_POST['action'] === 'saveStatusTask') {
        $listTasks = $_POST['listTasks'] ?? [];
        foreach ($listTasks as $task) {
            $taskId = (int)$task;
            $taskModel->updateStatusTaskById($taskId, 1);
        }
        $resultError[] = new ValidationError('success', 'Update status task successfully, reload list page after 3s!');
        echo pareDataToJsonPLus($resultError);
    }

}

function getListTask(): array
{
    global $conn;
    $taskModel = new task_model($conn);
    $userid = $_SESSION['user_id'];

    try {
        $result = $taskModel->getListTaskForUser($userid);
    } catch (Exception $e) {
        echo $e;
    }

    return $result;
}

function getListTaskForStatus($status): array
{
    global $conn;
    $taskModel = new task_model($conn);
    $userid = $_SESSION['user_id'];

    try {
        $result = $taskModel->getListTaskForUserAndStatus($userid, $status);
    } catch (Exception $e) {
        echo $e;
    }

    return $result;
}

function pareDataToJsonPLus($arrayObjects) {
    $data = array();
    foreach ($arrayObjects as $item) {
        $newItem = array(
            "field" => $item->getField(),
            "message" => $item->getMessage()
        );
        $data[] = $newItem;
    }
    return json_encode($data);
}




