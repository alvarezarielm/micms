<?php

class IndexController extends Controller {
	
	public function __construct(){
		parent::__construct();		
	}
	
	public function index($id = null){
		$settingsModel = $this->loadModel('settings');
		$settingsModel->loadSettings();
		$pageModel = $this->loadModel('page');
		$pageModel->load($settingsModel->attributes['default_page']);
		$pageId = ($settingsModel->attributes['friendly_urls']) ? $pageModel->alias : $pageModel->id;
		$this->redirect('page/view/'.$pageId);
	}
	
	public function admin(){
		
	}
	
}