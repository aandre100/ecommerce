<?php
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Category;
use \Hcode\Model\Product;

$app->get("/admin/categorias", function(){
	User::verifyLogin();
	$search = (isset($_GET['search']) ?  $_GET['search'] : '');
	$page = (isset($_GET['page']) ? (int)$_GET['page'] : 1);
	if($search != '') {
		$pagination = Category::getPageSearch($search, $page, 2);
	} else {
		$pagination = Category::getPage($page, 2);
	}

	$pages = [];

	for($x=0; $x < $pagination['pages']; $x++){
		array_push($pages, [
			'href' => '/admin/categorias?'.http_build_query([
				'page' => $x + 1,
				'search'=>$search
			]),
			'text' => $x + 1
		]);
	}



	$page = new PageAdmin();

	$page->setTpl("categories", [
		"categories"  => $pagination['data'],
		'search' => $search,
		'pages'  => $pages
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


$app->get("/admin/categorias/:idcategory/products", function($idcategory){

	User::verifyLogin();

	$category = new Category();
	$category->get((int)$idcategory);

	$page = new PageAdmin();

	$page->setTpl("categories-products", [
		"category" => $category->getValues(),
		"productsRelated" => $category->getProducts(),
		"productsNotRelated" => $category->getProducts(false)

	]);
});
$app->get("/admin/categorias/:idcategory/products/:idproduct/add", function($idcategory, $idproduct){

	User::verifyLogin();

	$category = new Category();
	$category->get((int)$idcategory);
	$product = new Product();
	$product->get((int)$idproduct);
	$category->addProduct($product);
	header("Location: /admin/categorias/". $idcategory ."/products");
	exit;
});
$app->get("/admin/categorias/:idcategory/products/:idproduct/remove", function($idcategory, $idproduct){

	User::verifyLogin();

	$category = new Category();
	$category->get((int)$idcategory);
	$product = new Product();
	$product->get((int)$idproduct);
	$category->removeProduct($product);
	header("Location: /admin/categorias/". $idcategory ."/products");
	exit;


});

?>
