<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Task.php";
    require_once __DIR__."/../src/Category.php";

    $app = new Silex\Application();

    $server = 'mysql:host=localhost;dbname=to_do';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);



    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));

    $app->get("/", function() use ($app) {
        return $app['twig']->render('index.html.twig', array('categories' => Category::getAll()));
    });

    $app->get("/tasks", function() use ($app) {
        return $app['twig']->render('tasks.html.twig', array('tasks' => Task::getAll()));
    });

    $app->get("/categories", function() use ($app) {
        return $app['twig']->render('categories.html.twig', array('categories' => Category::getAll()));
    });

    $app->get("/categories/{id}", function($id) use ($app) {
        $category = Category::findById($id);
        return $app['twig']->render('categories.html.twig', array('category' => $category, 'tasks' => $category->getTasks()));
    });

    $app->post("/tasks", function() use ($app) {
        $description = $_POST['description'];
        $category_id = $_POST['category_id'];
        $due_date = $_POST['due_date'];
        $task = new Task($description, $due_date, $id = null, $category_id);
        $task->save();
        $category = Category::findById($category_id);
        return $app['twig']->render('categories.html.twig', array('category' => $category, 'tasks' => $category->getTasks()));
    });

    $app->post("/delete_tasks/{id}", function($id) use ($app) {
        $category_id = Category::findById($id);
        Task::deleteFromCategory($category_id->getId());

        return $app['twig']->render('categories.html.twig', array('category' => $category_id));
    });

    $app->post("/categories", function() use ($app) {
        $category = new Category($_POST['name']);
        $category->save();
        return $app['twig']->render('index.html.twig', array('categories' => Category::getAll()));
    });

    $app->post("/delete_categories", function() use ($app) {
        Category::deleteCategories();
        return $app['twig']->render('index.html.twig');
    });

    // $app->post("/date_search", function() use ($app) {
    //     // $category_id = Category::findById($id);
    //     $search_date = $_POST['search_date'];
    //     Task::findByDate($search_date);
    //
    //     return $app['twig']->render('categories.html.twig', array('category' => $category, 'tasks' => $));
    // });

    return $app;
?>
