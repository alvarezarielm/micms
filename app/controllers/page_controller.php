<?php
	
class PageController extends Controller {
	

	public function __construct(){
		parent::__construct();
	}
	
	public function view($id = null){
		
		$view = new View();
		$settingsModel = $this->loadModel('settings');
		$pageModel = $this->loadModel('page');
		$settingsModel->loadSettings();
		//si no se asigno un id de pagina, cargamos la pagina default
		if(is_null($id)){
			$id = $settingsModel->attributes['default_page'];
		}
		
		$current_page = ($settingsModel->attributes['friendly_urls']) ? $pageModel->loadByAttributes(array('alias'=>$id)) : $pageModel->load($id);
		
		$menu = $this->getMenu($pageModel, $settingsModel);
		$view->assign('menu', $menu);

		if(!$current_page->public_access){
			$view->render('layouts/404');
		}
		
		$current_page_content = $pageModel->getPageContent($id);
		$page_content = array();
		for ($i = 0;$i < count($current_page_content); $i++){
			$page_content[$i] = $current_page_content[$i]['valor_contenido'];
		}
		
		$view->assign('page_content', $page_content);
		$view->assign('page_title', $current_page->title);
		$view->assign('site_name', $settingsModel->attributes['site_name']);

		$templates = $pageModel->getTemplates();
		foreach($templates as $template){
			if($template['id'] == $current_page->template){
				$view->render('layouts/'.$template['template']);
			}
		}
	}
	
	public function getMenu($pageModel, $settingsModel){
		$menu = '<ul>';
		foreach ($pageModel->getPages() as $page){
			if($page['public_access'] != 0){
				if(!$settingsModel->attributes['friendly_urls']){
					$menu .= '<li><a href="'.BASE_URL.'page/view/'.$page['id'].'">'.$page['title'].'</a></li>';
				}else{
					$menu .= '<li><a href="'.$page['alias'].'">'.$page['title'].'</a></li>';
				}
			}
		}
		$menu .= '</ul>';
		return $menu;
	}
	
	public function delete($id){
		$this->loadModel('page')->delete($id);
	}
}