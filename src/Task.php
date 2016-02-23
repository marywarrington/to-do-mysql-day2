<?php
class Task
{
    private $description;
    private $due_date;
    private $id;
    private $category_id;


    function __construct($task_description, $task_due_date, $task_id = null, $task_category_id)
    {
        $this->description = $task_description;
        $this->due_date = $task_due_date;
        $this->id = $task_id;
        $this->category_id = $task_category_id;
    }

    function setDescription($new_description)
    {
        $this->description = (string) $new_description;
    }

    function getDescription()
    {
        return $this->description;
    }

    function getDueDate()
    {
        return $this->due_date;
    }

    function getId()
    {
        return $this->id;
    }

    function getCategoryId()
    {
        return $this->category_id;
    }

    function save()
    {
        $GLOBALS['DB']->exec("INSERT INTO tasks (description, due_date, category_id) VALUES ('{$this->getDescription()}', '{$this->getDueDate()}', {$this->getCategoryId()});");
        $this->id = $GLOBALS['DB']->lastInsertId();
    }

    static function getAll()
    {
        $returned_tasks = $GLOBALS['DB']->query("SELECT * FROM tasks;");
        $tasks = array();

        foreach($returned_tasks as $task) {
            $description = $task['description'];
            $due_date = $task['due_date'];
            $id = $task['id'];
            $category_id = $task['category_id'];
            $new_task = new Task($description, $due_date, $id, $category_id);
            array_push($tasks, $new_task);
    }
        return $tasks;
    }

    static function deleteAll()
    {
        $GLOBALS['DB']->exec("DELETE FROM tasks;");
    }

    static function deleteFromCategory()
    {
        $GLOBALS['DB']->exec("DELETE FROM tasks; WHERE category_id = '{$this->getCategoryId()}'");
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
