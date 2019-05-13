<?php

use \Peperoschach\Page;
use \Peperoschach\Model\Category;
use \Peperoschach\Model\Product;

$app->get('/', function () {

    $products = Product::listAll();

    $page =  new Page();

    $page->setTpl("index", [
        'products'=>Product::checkList($products)
    ]);
});

$app->get("/categories/:idcategory", function ($idcategory) {

    $pageCurrent = (isset($_GET['page'])) ? (int)$_GET["page"] : 1;

    $category = new Category();

    $category->get((int)$idcategory);

    $pagination = $category->getProductsPage($pageCurrent);

    $pages = [];

    for ($i=1; $i <= $pagination['pages']; $i++) {
        array_push($pages, [
            'link'=>'/ecommerce/categories/' . $category->getidcategory() . '?page=' . $i,
            'page'=>$i
        ]);
    }

    $page = new Page();

    $page->setTpl("category", [
        "category" =>$category->getValues(),
        "products" =>$pagination["data"],
        "pages"=>$pages
    ]);
});
