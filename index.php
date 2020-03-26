<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
session_start();

require_once("vendor/autoload.php");

use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\DB\Sql;
use \Hcode\Model\User;
use \Hcode\Model\Category;

$app = new Slim();

$app->config('debug', true);

$app->get('/', function() {

	$page = new Page();

	$page->setTpl("index");

	// $sql = new Hcode\DB\Sql();
	// $results = $sql->select("SELECT * FROM tb_users");
	// echo json_encode($results);

});
$app->get('/admin', function() {
	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("index");

	 // $sql = new Hcode\DB\Sql();
	 // $results = $sql->select("SELECT * FROM tb_users");
	 // echo json_encode($results);

});
$app->get('/cena', function() {
	 User::verifyLogin();
	 $sql = new Sql();
	 $results = $sql->select("SELECT * FROM tb_users");
	 echo json_encode($results);

});
$app->get('/admin/login', function() {

	$page = new PageAdmin([
		"header" => false,
		"footer" => false
	]);
	$page->setTpl("login");
});

$app->post('/admin/login', function() {
	User::login($_POST['login'], $_POST['password']);
	header("Location: /admin");
	exit;
});
$app->get('/admin/logout', function(){
	User::logout();
	header("Location: /admin/login");
	exit;
});

$app->get("/admin/users", function(){
	User::verifyLogin();
	$users = User::listAll();
	$page = new PageAdmin();
	$page->setTpl("users", array(
		"users" => $users
	));
});

$app->get("/admin/users/create", function(){
	User::verifyLogin();
	$page = new PageAdmin();
	$page->setTpl("users-create");
});

$app->get("/admin/users/:iduser/delete", function($iduser){
	User::verifyLogin();
	$user = new User();
	$user->get((int)$iduser);
	$user->delete();
	header("Location: /admin/users");
	exit;

});


$app->get("/admin/users/:iduser", function($iduser){
	User::verifyLogin();
	$user = new User();
	$user->get((int)$iduser);
	$page = new PageAdmin();
	$page->setTpl("users-update", array(
		"user" => $user->getValues()
	));
});





$app->post("/admin/users/create", function(){
	User::verifyLogin();
	$user = new User();
	$_POST["desperson"] = utf8_encode($_POST["desperson"]);
	$_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;
 	$_POST['despassword'] = password_hash($_POST["despassword"], PASSWORD_DEFAULT, [
 		"cost"=>12
 	]);
	$user->setData($_POST);
	$user->save();
	header("Location: /admin/users");
	exit;

});


$app->post("/admin/users/:iduser", function($iduser){
	User::verifyLogin();
	$user = new User();
	$_POST["desperson"] = utf8_encode($_POST["desperson"]);
	$_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

	$user->get((int)$iduser);

	$user->setData($_POST);

	$user->update();
	header("Location: /admin/users");
	exit;

});


$app->get("/forgot", function() {

	$page = new Page([
		"header" => false,
		"footer" => false
	]);

	$page->setTpl("forgot");

});

$app->post("/forgot", function(){

	$user = User::getForgot($_POST["email"], false);

	header("Location: /forgot/sent");
	exit;

});

$app->get("/forgot/sent", function(){

	$page = new Page([
		"header" => false,
		"footer" => false
	]);

	$page->setTpl("forgot-sent");

});

$app->get("/forgot/reset", function(){

	$user = User::validForgotDecrypt($_GET["code"]);

	$page = new Page([
		"header" => false,
		"footer" => false
	]);

	$page->setTpl("forgot-reset", array(
		"name"=>$user["desperson"],
		"code"=>$_GET["code"]
	));

});

$app->post("/forgot/reset", function(){

	$forgot = User::validForgotDecrypt($_POST["code"]);

	User::setFogotUsed($forgot["idrecovery"]);

	$user = new User();

	$user->get((int)$forgot["iduser"]);

	$password = User::getPasswordHash($_POST["password"]);

	$user->setPassword($password);

	$page = new Page([
		"header" => false,
		"footer" => false
	]);

	$page->setTpl("forgot-reset-success");

});
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

$app->run();




 ?>
