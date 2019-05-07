<?php
session_start();
require_once("vendor/autoload.php");

use \Slim\Slim;
use \Peperoschach\Page;
use \Peperoschach\PageAdmin;
use \Peperoschach\Model\User;

$app = new Slim();

$app->config('debug', true);

$app->get('/', function() {

    $page =  new Page();

    $page->setTpl("index");
});

$app->get('/admin', function() {

    User::verifyLogin();

    $page =  new PageAdmin();

    $page->setTpl("index");
});

$app->get('/admin/login', function() {

    $page = new PageAdmin([
        "header"=>false,
        "footer"=>false
    ]);

    $page->setTpl("login");
});

$app->post('/admin/login', function() {

    User::login($_POST["login"], $_POST["password"]);

    header("Location: /ecommerce/admin");
    exit;
});

$app->get('/admin/logout', function() {

    User::logout();

    header("Location: /ecommerce/admin/login");
    exit;
});

$app->run();

 ?>
