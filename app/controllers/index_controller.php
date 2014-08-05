<?php

class IndexController extends Controller {
	
	public function __construct(){
		parent::__construct();		
	}
	
	public function index($id = null){
		$view = new View();
		$settingsModel = $this->loadModel('settings');
		$friendly_urls = $settingsModel->getSetting('friendly_urls');
		$site_name = $settingsModel->getSetting('site_name');
		$pageModel = $this->loadModel('page');
		
		//si no se asigno un id de pagina, cargamos la pagina default
		if(is_null($id)){
			$id = $settingsModel->getSetting('default_page');
		}
		
		
		$current_page = $pageModel->getPage($id);
		if($current_page['public_access'] == 0){
			echo 'La pagina no existe o no tiene acceso a ella';
			return false;
		}
		$current_page_content = $pageModel->getPageContent($id);
		$page_content = array();
		for ($i = 0;$i < count($current_page_content); $i++){
			$page_content[$i] = $current_page_content[$i]['valor_contenido'];
		}
		$this->assign('page_content', $page_content);
		$this->assign('page_title', $current_page['title']);
		$this->assign('site_name', $site_name);
		$menu = '';
		foreach ($pageModel->getPages() as $page){
			if($page['public_access'] != 0){
				if($friendly_urls == 0){
					$menu .= '<li><a href="'.$page['id'].'">'.$page['title'].'</a></li>';
				}else{
					$menu .= '<li><a href="'.$page['alias'].'">'.$page['title'].'</a></li>';
				}
			}
		}
		$this->assign('menu', $menu);
		$templates = $pageModel->getTemplates();
		foreach($templates as $template){
			if($template['id'] == $current_page['template']){
				$this->view->render('layouts/'.$template['template']);
			}
		}
	}
	
	public function admin(){
		
	}
	
}