<?php
include_once '../utils/connect_database.php';
include_once '../utils/validation.php';
include_once '../models/user_model.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    global $conn;
    session_start();
    $userModel = new user_model($conn);
    $validate = new Validation();
    $result = array();

    $username = $_SESSION['username'] ?? null;
    $password = $_POST['password'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];
    $captchaInput = $_POST['captcha'];
    $userid = $_SESSION['user_id'] ?? "";

    if (isset($_SESSION['captcha']) && !empty($captchaInput)) {
        $captcha = $_SESSION['captcha'];
        if ($captchaInput === $captcha) {
            unset($_SESSION['captcha']);
            $result_validate_password = $validate->validatePassword($password);
            $result_validate_new_password = $validate->validatePassword($newPassword, 'newPassword');
            $result_validate_cf_password = $validate->validatePassword($confirmPassword, 'confirmPassword');
            $user = $userModel->getUserByUsername($username);

            $_SESSION['old_password'] = $password;
            $_SESSION['new_Password'] = $newPassword;
            $_SESSION['confirm_Password'] = $confirmPassword;

            if (count($result_validate_password) > 0) {
                echo pareDataToJson($result_validate_password);
            } else if (count($result_validate_new_password) > 0) {
                echo pareDataToJson($result_validate_new_password);
            } else if (count($result_validate_cf_password) > 0) {
                echo pareDataToJson($result_validate_cf_password);
            } else if ($newPassword == $password) {
                $result[] = new ValidationError('newPassword', 'The new password must not be the same as the old password!');
                echo pareDataToJson($result);
            } else if ($confirmPassword !== $newPassword) {
                $result[] = new ValidationError('confirmPassword', 'Confirm password does not match new password!');
                echo pareDataToJson($result);
            } elseif ($user && password_verify($password, $user['password'])) {
                $update_password = $userModel->updatePasswordByUserId($newPassword, $userid);
                $result[] = new ValidationError('success', 'Change password successfully, redirect to do list page after 3s!');
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
    $conn->close();
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