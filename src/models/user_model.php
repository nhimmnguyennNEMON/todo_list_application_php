<?php
class user_model {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function registerUser($username, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO todo_list.Users (username, password) VALUES ('$username', '$hashedPassword')";
        return mysqli_query($this->conn, $query);
    }

    public function getUserByUsername($username) {
        $query = "SELECT * FROM todo_list.Users WHERE username = '$username'";
        $result = mysqli_query($this->conn, $query);
        return mysqli_fetch_assoc($result);
    }

    public function updatePasswordByUserId($password, $user_id) {
        $userId = (int)$user_id;
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "UPDATE todo_list.Users SET password ='$hashedPassword' WHERE user_id = '$userId'";
        return mysqli_query($this->conn, $query);
    }

    public function deletedUserByUserId($user_id) {
        $userId = (int)$user_id;
        $query = "DELETE FROM todo_list.Users WHERE user_id = '$userId'";
        return mysqli_query($this->conn, $query);
    }
}

