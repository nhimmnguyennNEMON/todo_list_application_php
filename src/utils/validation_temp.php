<?php
include_once '../businessObject/validation_error.php';

global $errors;
global $arrayError;
$errors = new ValidationError();
$arrayError = array();

class ValidationTemp {

    public function validateUsername($username): array
    {
        global $errors;
        global $arrayError;
        $regexUserName = '/^[a-zA-Z0-9_.]{6,25}$/';
        if (empty($username)) {
            $errors->setArrayErrors(array(
                'field' => 'username',
                'message' => 'Username cannot be empty.'));
            $arrayError[] = $errors;
        } elseif (strpos($username, ' ') === true) {
            $errors->setArrayErrors(array(
                'field' => 'username',
                'message' => 'Username must not contain spaces.'));
            $arrayError[] = $errors;
        } elseif (!preg_match($regexUserName, $username)) {
            $errors->setArrayErrors(array(
                'field' => 'username',
                'message' => 'Username allows only letters, numbers and underscores, minimum 6 characters, maximum 20 characters.'));
            $arrayError[] = $errors;
        }

        return $arrayError;
    }

    public function validatePassword($password): ValidationError
    {
        global $errors;
        global $arrayError;
        $regexPassword= "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,25}$/";

        if (empty($password)) {
            $errors->setArrayErrors(array(
                'field' => 'password',
                'message' => 'Password cannot be empty.'));
            $arrayError[] = $errors;
        } elseif (!preg_match($regexPassword, $password)) {
            $errors->setArrayErrors(array(
                'field' => 'password',
                'message' => 'Password requires at least 8 characters, including letters, numbers and at least one special character.'));
            $arrayError[] = $errors;
        }

        return $errors;

    }

    public function validateUPC($username, $password, $confirmPassword): array
    {
        global $errors;
        global $arrayError;
        $regexUserName = '/^[a-zA-Z0-9_.]{6,25}$/';
        $regexPassword= "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,25}$/";

        if (empty($username)) {
            $errors->setArrayErrors(array(
                'field' => 'username',
                'message' => 'Username cannot be empty.'));
            $arrayError[] = $errors;
        } elseif (strpos($username, ' ') === true) {
            $errors->setArrayErrors(array(
                'field' => 'username',
                'message' => 'Username must not contain spaces.'));
            $arrayError[] = $errors;
        } elseif (!preg_match($regexUserName, $username)) {
            $errors->setArrayErrors(array(
                'field' => 'username',
                'message' => 'Username allows only letters, numbers, dots and underscores, minimum 6 characters, maximum 25 characters.'));
            $arrayError[] = $errors;
        }

        if (empty($password)) {
            $errors->setArrayErrors(array(
                'field' => 'password',
                'message' => 'Password cannot be empty.'));
            $arrayError[] = $errors;
        } elseif (!preg_match($regexPassword, $password)) {
            $errors->setArrayErrors(array(
                'field' => 'password',
                'message' => 'Password requires at least 8 characters, including letters, numbers and at least one special character.'));
            $arrayError[] = $errors;
        }

        if (empty($confirmPassword)) {
            $errors->setArrayErrors(array(
                'field' => 'cfPassword',
                'message' => 'Confirm Password cannot be empty.'));
        } elseif (!preg_match($regexPassword, $password)) {
            $errors->setArrayErrors(array(
                'field' => 'cfPassword',
                'message' => 'Confirm Password requires at least 8 characters, including letters, numbers and at least one special character.'));
        }

        return $arrayError;;
    }
}