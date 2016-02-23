<?php
    class Category
    {
        private $name;
        private $id;

        function __construct($category_name, $category_id = null)
        {
            $this->name = $category_name;
            $this->id = $category_id;
        }

        function setName($new_name)
        {
            $this->name = (string) $new_name;
        }

        function getName()
        {
            return $this->name;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO categories (name) VALUES ('{$this->getName()}')");
            $this->id= $GLOBALS['DB']->lastInsertId();
        }

        static function getAll()
        {
            $returned_categories = $GLOBALS['DB']->query("SELECT * FROM categories;");
            $categories = array();
            foreach($returned_categories as $category) {
                $name = $category['name'];
                $id = $category['id'];
                $new_category = new Category($name, $id);
                array_push($categories, $new_category);
            }
            return $categories;
        }

        function getTasks()
        {
            $tasks = Array();
            $returned_tasks = $GLOBALS['DB']->query("SELECT * FROM tasks WHERE category_id = {$this->getId()} ORDER BY due_date;");
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

        static function deleteCategories()
        {
          $GLOBALS['DB']->exec("DELETE FROM categories;");
        }

        static function findById($search_id)
        {
            $found_category = null;
            $categories = Category::getAll();
            foreach($categories as $category) {
                $category_id = $category->getId();
                if ($category_id == $search_id) {
                  $found_category = $category;
                }
            }
            return $found_category;
        }
    }
?>
