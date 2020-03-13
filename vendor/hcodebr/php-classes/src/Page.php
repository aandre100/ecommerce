<?php
namespace Hcode;

use Rain\Tpl;

class Page {

	public function __construct(){
		$config = array(
			"tpl_dir"   => $_SERVER["DOCUMENT_ROOT"]. "/views/",
			"cache_dir" => $_SERVER["DOCUMENT_ROOT"] . "/views-cache/",
			"debug"     => false //set to be false to improve speed
			);
		Tpl::configure( $config );
		$tpl = new Tpl();
	}
	public function __destruct(){

	}

}


 ?>
