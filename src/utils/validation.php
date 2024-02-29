<?php
include_once '../businessObject/validation_error.php';

class Validation {

    public function validateUsername($username): array
    {
        $errors = [];
        $regexUserName = '/^[a-zA-Z0-9_.]{6,25}$/';
        if (empty($username)) {
            $errors[] = new ValidationError('username', 'Username cannot be empty.');
        } elseif (strpos($username, ' ') === true) {
            $errors[] = new ValidationError('username', 'Username must not contain spaces.');
        } elseif (!preg_match($regexUserName, $username)) {
            $errors[] = new ValidationError('username', 'Username allows only letters, numbers, dots and underscores, minimum 6 characters, maximum 25 characters.');
        }

        return $errors;
    }

    public function validatePassword($password, $field = 'password'): array
    {
        $errors = [];
        $regexPassword= "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,25}$/";

        if (empty($password)) {
            $errors[] = new ValidationError($field, 'Password cannot be empty.');
        } elseif (!preg_match($regexPassword, $password)) {
            $errors[] = new ValidationError($field, 'Password requires at least 8 characters, including uppercase and lowercase letters, numbers and at least one special character.');
        }

        return $errors;

    }

    public function validateUPC($username, $password, $confirmPassword): array
    {
        $errors = [];
        $regexUserName = '/^[a-zA-Z0-9_.]{6,25}$/';
        $regexPassword= "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,25}$/";

        if (empty($username)) {
            $errors[] = new ValidationError('username', 'Username cannot be empty.');
        } elseif (strpos($username, ' ') === true) {
            $errors[] = new ValidationError('username', 'Username must not contain spaces.');
        } elseif (!preg_match($regexUserName, $username)) {
            $errors[] = new ValidationError('username', 'Username allows only letters, numbers, dots and underscores, minimum 6 characters, maximum 25 characters.');
        }

        if (empty($password)) {
            $errors[] = new ValidationError('password', 'Password cannot be empty.');
        } elseif (!preg_match($regexPassword, $password)) {
            $errors[] = new ValidationError('password', 'Password requires at least 8 characters, including uppercase and lowercase letters, numbers and at least one special character.');
        }

        if (empty($confirmPassword)) {
            $errors[] = new ValidationError('cfPassword', 'Password cannot be empty.');
        }  elseif ($confirmPassword !== $password) {
            $errors[] = new ValidationError('cfPassword', 'Confirm password does not match password.');
        } elseif (!preg_match($regexPassword, $confirmPassword)) {
            $errors[] = new ValidationError('cfPassword', 'Password requires at least 8 characters, including uppercase and lowercase letters , numbers and at least one special character.');
        }

        return $errors;
    }
}

