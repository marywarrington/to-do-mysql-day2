<?php
class Task
{
    private $description;
    private $id;

    function __construct($task_description, $task_id = null)
    {
        $this->description = $task_description;
        $this->id = $task_id;
    }

    function setDescription($new_description)
    {
        $this->description = (string) $new_description;
    }

    function getDescription()
    {
        return $this->description;
    }

    function save()
    {
        $GLOBALS['DB']->exec("INSERT INTO tasks (description) VALUES ('{$this->getDescription()}');");
        $this->id = $GLOBALS['DB']->lastInsertId();
    }

    static function getAll()
    {
        $returned_tasks = $GLOBALS['DB']->query("SELECT * FROM tasks;");
        $tasks = array();

        foreach($returned_tasks as $task) {
            $description = $task['description'];
            $id = $task['id'];
            $new_task = new Task($description, $id);
            array_push($tasks, $new_task);
    }
        return $tasks;
    }

    static function deleteAll()
    {
        $GLOBALS['DB']->exec("DELETE FROM tasks;");
    }

    function getId()
    {
        return $this->id;
    }

    static function find($search_id)
    {
        $found_task = null;
        $tasks = Task::getAll();
        foreach($tasks as $task) {
            $task_id = $task->getId();
            if ($task_id == $search_id) {
                $found_task = $task;
            }
        }
        return $found_task;
    }


}
?>
