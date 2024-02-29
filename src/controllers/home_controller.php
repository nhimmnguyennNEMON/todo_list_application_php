<?php
include_once '../utils/connect_database.php';
include_once '../utils/validation.php';
include_once '../models/user_model.php';
include_once '../models/task_model.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    global $conn;
    $userModel = new user_model($conn);
    $taskModel = new task_model($conn);
    $result = array();
    $userid = $_SESSION['user_id'] ?? "";

    if (isset($_POST['action']) && $_POST['action'] === 'logout') {
        logout();
    } else if (isset($_POST['action']) && $_POST['action'] === 'deletedAccount') {
        if (isset($_SESSION['username'])) {
            $task = $taskModel->deletedTaskByUserId($userid);
            $user = $userModel->deletedUserByUserId($userid);

            if ($task && $user) {
                logout();
                $result[] = new ValidationError('success', 'Deleted successfully, redirect to do login page after 3s!');
            } else {
                $result[] = new ValidationError('fail', 'Deleted account fail!');
            }
            echo pareDataToJson($result);

        } else {
            header("Location: ../view/signin_view.php");
            exit;
        }
    }


}

function pareDataToJson($arrayObjects) {
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

function logout() {
    session_unset();
    session_destroy();
}