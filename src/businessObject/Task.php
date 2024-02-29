<?php

class Task{
    private $id;
    private $title;
    private $completed;
    private $user_id;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getCompleted()
    {
        return $this->completed;
    }

    public function setCompleted($completed)
    {
        $this->completed = $completed;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    public function __construct($id, $title, $completed, $user_id)
    {
        $this->id = $id;
        $this->title = $title;
        $this->completed = $completed;
        $this->user_id = $user_id;
    }
}

?>

