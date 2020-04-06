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


require_once("site.php");
require_once("functions.php");
require_once("admin.php");
require_once("admin-user.php");
require_once("admin-forget.php");
require_once("admin-category.php");
require_once("admin-product.php");







$app->run();




 ?>
