<?php
	
class PagesController {
	public $load;
	public $model;
	public $viewVars = array();
	
	function __construct(){
		
		$this->load = new Load();
		$this->model = new PagesModel();
		
		$this->viewVars = $this->load->viewVars;
		
		//default layout
		$this->index();
	
	}
	
	function index(){
		$currentPage = $this->getCurrentPageId();
		
		$this->load->set('data');
		$this->load->view('layout.php');
		
	}
	
	function getCurrentPageId(){
		if(!isset($_GET['page'])){
			$id = 1;
		}else{
			$id = htmlspecialchars($_GET['page']);
		}
		
		return $id;
		
	}
}