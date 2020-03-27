<?php
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Category;

$app->get("/admin/categorias", function(){
	User::verifyLogin();
	$categories = Category::listAll();
	$page = new PageAdmin();

	$page->setTpl("categories", [
		"categories" => $categories
	]);
});


$app->get("/admin/categorias/create", function(){
	User::verifyLogin();
	$page = new PageAdmin();

	$page->setTpl("categories-create");
});
$app->post("/admin/categorias/create", function(){
	User::verifyLogin();
	$category = new Category();
	$category->setData($_POST);
	$category->save();
	header("Location: /admin/categorias");
	exit;
});

$app->get("/admin/categorias/:idcategory/delete", function($idcategory){
	User::verifyLogin();
	$category = new Category();
	$category->get((int)$idcategory);
	$category->delete();
	header("Location: /admin/categorias");
	exit;
});

$app->get("/admin/categorias/:idcategory", function($idcategory){
	User::verifyLogin();
	$page = new PageAdmin();
	$category = new Category();
	$category->get((int)$idcategory);
	$page->setTpl("categories-update", [
		"category" => $category->getValues()

	]);
});
$app->post("/admin/categorias/:idcategory", function($idcategory){
	User::verifyLogin();
	$category = new Category();
	$category->get((int)$idcategory);

	$category->setData($_POST);
	$category->save();
	header("Location: /admin/categorias");
	exit;
});

$app->get("/categorias/:idcategory", function($idcategory){

	$category = new Category();
	$category->get((int)$idcategory);

	$page = new Page();

	$page->setTpl("category", [
		"category" => $category->getValues(),
		"products" => []
	]);

});
?>
