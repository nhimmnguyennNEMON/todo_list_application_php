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
    $confirmPassword = $_POST['confirmPassword'];
    $captchaInput = $_POST['captcha'];

    if (isset($_SESSION['captcha']) && !empty($captchaInput)) {
        $captcha = $_SESSION['captcha'];
        if ($captchaInput === $captcha) {
            unset($_SESSION['captcha']);
            $result_validate = $validate->validateUPC($username, $password, $confirmPassword);

            if ($userModel->getUserByUsername($username) !== NULL ) {
                $result[] = new ValidationError('username', 'Username already exists in the system!');
                echo pareDataToJson($result);
            } elseif (count($result_validate) > 0) {
                echo pareDataToJson($result_validate);
            } else {
                $user_register = $userModel->registerUser($username, $password);
                $user_register ? $result[] = new ValidationError('success', 'Register successfully, redirect to sign in page after 3s!')
                    : $result[] = new ValidationError('fail', 'Register fail!');
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
