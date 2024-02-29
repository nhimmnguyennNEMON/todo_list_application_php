<?php
include_once '../utils/connect_database.php';
include_once '../utils/validation.php';
include_once '../models/user_model.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    global $conn;
    $userModel = new user_model($conn);
    $validate = new Validation();

    $username = $_POST['username'];
    $password = $_POST['password'];
    $captchaInput = $_POST['captcha'];

    if (isset($_SESSION['captcha']) && !empty($captchaInput)) {
        $captcha = $_SESSION['captcha'];
        if ($captchaInput === $captcha) {
            unset($_SESSION['captcha']);
            $result_validate_username = $validate->validateUsername($username);
            $result_validate_password = $validate->validatePassword($password);
            $user = $userModel->getUserByUsername($username);

            if (count($result_validate_username) > 0) {
                echo pareDataToJson($result_validate_username);
            } else if (count($result_validate_password) > 0) {
                echo pareDataToJson($result_validate_password);
            } elseif ($user && password_verify($password, $user['password'])) {
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $user['user_id'];
                $result[] = new ValidationError('success', 'Login successfully, redirect to do list page after 3s!');
                echo pareDataToJson($result);
            } else {
                $result[] = new ValidationError('fail', 'Username and Password incorrect!');
                echo pareDataToJson($result);
            }

        } else {
            $result[] = new ValidationError('captcha', 'Captcha is invalid, please try again!');
            echo pareDataToJson($result);
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


