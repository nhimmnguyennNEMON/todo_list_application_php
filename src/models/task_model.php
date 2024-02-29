<?php
class task_model {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getListTaskForUser($user_id): array
    {
        $query = "SELECT * FROM todo_list.Tasks WHERE user_id = '$user_id'";
        $result = mysqli_query($this->conn, $query);
        $tasks = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $task = new Task($row['id'], $row['title'], $row['completed'], $row['user_id']);
                $tasks[] = $task;
            }
        }
        return $tasks;
    }

    public function getListTaskForUserAndStatus($user_id, $status): array
    {
        $query = "SELECT * FROM todo_list.Tasks WHERE user_id = '$user_id' and completed = '$status'";
        $result = mysqli_query($this->conn, $query);
        $tasks = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $task = new Task($row['id'], $row['title'], $row['completed'], $row['user_id']);
                $tasks[] = $task;
            }
        }
        return $tasks;
    }

    public function addNewTask($user_id, $content) {
        $userid = (int)$user_id;
        $query = "INSERT INTO todo_list.Tasks (title, completed, user_id) VALUES ('$content', '2', '$userid')";
        return mysqli_query($this->conn, $query);
    }

    public function deletedTaskByUserId($user_id) {
        $userId = (int)$user_id;
        $query = "DELETE FROM todo_list.Tasks WHERE user_id = '$userId'";
        return mysqli_query($this->conn, $query);
    }

    public function deletedTaskById($task_id) {
        $taskId = (int)$task_id;
        $query = "DELETE FROM todo_list.Tasks WHERE id = '$taskId'";
        return mysqli_query($this->conn, $query);
    }

    public function updateStatusTaskById($task_id, $status_task) {
        $status = (int)$status_task;
        $taskId = (int)$task_id;
        $query = "UPDATE todo_list.Tasks SET completed ='$status' WHERE id = '$taskId'";
        return mysqli_query($this->conn, $query);
    }
}

