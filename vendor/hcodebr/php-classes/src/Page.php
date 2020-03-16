<?php
namespace Hcode;

use Rain\Tpl;

class Page {
	private $tpl;
	private $options = [];
	private $defaults = [
			"data" =>[]
	];

	public function __construct($opts = array(), $tpl_dir = "/views/"){
		$this->options = array_merge($this->defaults, $opts);
		$config = array(
			"base_url"  => null,
			"tpl_dir"   => $_SERVER["DOCUMENT_ROOT"].$tpl_dir,
			"cache_dir" => $_SERVER["DOCUMENT_ROOT"]."/views-cache/",
			"debug"     => false //set to be false to improve speed
			);
		Tpl::configure( $config );
		$this->tpl = new Tpl;
		$this->setData($this->options["data"]);
		$this->tpl->draw("header");
	}
	private function setData($data = array()) {
		foreach($data as $key => $value) {
			$this->tpl->assign($key, $value);
		}
	}
	public function __destruct(){
		$this->tpl->draw("footer");
	}
	public function setTpl($tplname, $data = array(), $returnHTML = false){
		$this->setData($data);
		return $this->tpl->draw($tplname, $returnHTML);
	}


}


 ?>
