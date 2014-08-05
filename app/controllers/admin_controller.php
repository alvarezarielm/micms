<?php 

class AdminController extends Controller {
	
	function __construct(){
		parent::__construct();
		Session::init();
		$this->view->page = 'admin';
		$this->view->page_title = 'Admin';
	}
	
	function login(){
		if($_POST){
			$u = $_POST['username'];
			$p = $_POST['password'];
			$userModel = $this->loadModel('user');
			if($userModel->login($u, $p)){
				Session::set('isLoggedIn', true);
				Session::set('user', $u);
				$this->redirect('admin');
			}else{
				$this->redirect('admin');
			}
		}else{
			if(!Session::get('user')){
				$this->view->render('admin/login');
			}
			
		}
		
	}
	
	function index(){
		
// 		$collection = Helper::getCollection('user');
		
// 		foreach ($collection as $user){
// 			var_dump($user->__get('id'));
// 		}
		
		$pageModel = $this->loadModel('page');
		
// 		var_dump($pageModel->instanceDependentTables());
		
		$settingsModel = $this->loadModel('settings');
		$site_name = $settingsModel->getSetting('site_name');
		$this->view->page_title = 'Admin';
		$this->view->site_name = $site_name['value'];

		$isLoggedIn = Session::get('isLoggedIn');
		if($isLoggedIn == false){
			Session::destroy();
			$this->view->render('admin/login');
			exit;
		}
		$this->view->username = Session::get('user');
		$this->view->render('admin/index');
	}
	
	function logout() {
		Session::destroy();
		$this->redirect('admin');
	}
	
	function siteSettings(){
		$settingsModel = $this->loadModel('settings');
		$settings = $settingsModel->getSettings();
		foreach ($settings as $setting => $key){
			$this->view->{$key['key']} = $key['value'];
		}
		
		$this->view->render('admin/sitesettings');
	}
	
	function saveSettings($data = array()){
		$data = $_POST;
		if(!isset($data['friendly_urls'])){
			$data['friendly_urls'] = 0;
		}else{
			$data['friendly_urls'] = 1;
		}
		$settingsModel = $this->loadModel('settings');
		$settingsModel->saveSettings($data);
		$this->view->render('admin/index');
	}
	
	function getMenuPages(){
		$pageModel = $this->loadModel('page');
		$menu = $pageModel->getPages();
		foreach ($menu as $item){
			if($item['parent']!=0){
				echo '';
			}else{
				echo '<li><a class="page-item" title="'.$item['title'].'" rel="'.$item['id'].'" href="#">'.$item['title'].'</a><a class="del" title="'.$item['id'].'" href="#">X</a>';
				$childs = $pageModel->loadChild($item['id']); 
				if (count($childs) > 0){
					echo '<ul>';
					foreach ($childs as $child){
						echo '<li><a class="page-item" href="#" rel="'.$child['id'].'" title="'.$child['title'].'">'.$child['title'].'</a></li>';
					}
					echo '</ul>';
				}
			}
				
		}
	}
	
	function deletePage($id){
		if($id == 1){
			echo 'La home no puede ser eliminada';
			return false;
		}
		$pageModel = $this->loadModel('page');
		$pageModel->__set('id', '1');
		$pageModel->delete();
		echo 'Pagina eliminada';
	}
	
	function forgotPassword(){
		$this->view->render('admin/forgotpassword');
		if($_POST){
			$data = $_POST;
			$userModel = $this->loadModel('user');
			$user = $userModel->getByEmail($data['email']);
			if(!$user){
				echo 'El email no se encuentra en nuestra base de datos';
			}else{
				$message = 'Hola '.ucfirst($user['username']).'<br/>';
				$message .= 'Para cambiar tu password hace click en el siguiente link:';
				$message .= BASE_URL.'admin/changePassword/'.md5('newPass');
				mail($user['email'], 'Password Reset', $message);
			}
		}
	}
	
	function mailValidator($mail) {
	    if (ereg("^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@+([_a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]{2,200}\.[a-zA-Z]{2,6}$", $mail ) ) {
	       return true;
	    } else {
	       return false;
	    }
	}
	
	function editPage($id = null){
		$pageModel = $this->loadModel('page');
		$page = $pageModel->getPage($id);
		$content = $pageModel->getPageContent($id);
		$this->view->page_id = $page['id'];
		$this->view->page_title = $page['title'];
		$this->view->menuindex = $page['menuindex'];
		$this->view->public_access = $page['public_access'];
		$this->view->alias = $page['alias'];
		$this->view->modified = $page['modified'];
		$this->view->created = $page['created'];
		$this->view->content = $content;
		
		$menu = $pageModel->getPages();
		$this->view->parent = '<option value="0" selected="yes">Site</option>';
		foreach ($menu as $menu_page){
			if($page['parent'] == $menu_page['id']){
				$this->view->parent .= '<option value="'.$menu_page['id'].'" selected="yes">'.$menu_page['title'].'</option>';
				
			}else{
				$this->view->parent .= '<option value="'.$menu_page['id'].'">'.$menu_page['title'].'</option>';
			}
			
		}
		
		$templates = $pageModel->getTemplates();		
		$selected = $page['template'];
		$i = 0;
		foreach ($templates as $item){
			$i++;
			if($i == $selected){
				$this->view->template .= '<option value="'.$i.'" selected="yes">'.$item['template'].'</option>';
			}else{
				$this->view->template .= '<option value="'.$i.'">'.$item['template'].'</option>';
			}
			
		}
		
		$this->view->render('admin/edit',1);
	}
	
	function savePage($id = null){
		$data = $_POST;
		$pageModel = $this->loadModel('page');
		if(!isset($data['public_access'])){
			$data['public_access'] = 0;
		}else{
			$data['public_access'] = 1;
		}
		if($pageModel->update($id, $data)){
			echo 'Pagina guardada';
		}else{
			echo 'No se pudo guardar la pagina';
		}
	}
	
	function addPage(){
		$pageModel = $this->loadModel('page');
		$templates = $pageModel->getTemplates();
		$menu = $pageModel->getPages();
		$this->view->parent = '<option value="0" selected="yes">Site</option>';
		foreach ($menu as $page){
			$this->view->parent .= '<option value="'.$page['id'].'">'.$page['title'].'</option>';
		}
		$i = 0;
		foreach ($templates as $item){
			$i++;
			if($item['id'] == 1){
				$this->view->template .= '<option value="'.$i.'" default="default">'.$item['template'].'</option>';
			}else{
				$this->view->template .= '<option value="'.$i.'">'.$item['template'].'</option>';
			}
		}
		$this->view->render('admin/addpage',1);
	}
	
	function createPage($data = array()){
		$data = $_POST;
		$pageModel = $this->loadModel('page');
		if(!isset($data['public_access'])){
			$data['public_access'] = 0;
		}else{
			$data['public_access'] = 1;
		}
		foreach ($data as $item => $v){
			if($item == 'title'){
				$alias = $this->makeAlias($v); 
				if(empty($v)){
					echo ucfirst($item).' es requerido<br/>';
					return false;
				}
			}
			if($item == 'alias'){
				if(empty($v)){
					$v = $alias;
				}
			}
			$pageModel->__set($item, $v);
		}
		
		$pageModel->__set('created', date('d-m-Y - h:i'));
		
		$saved = $pageModel->save();
		
		if ($saved) {
			echo $saved;
		}
		
	}
	function makeAlias($str){
		$alias = strtolower($str);
		$alias = explode(' ',$alias);
		$nuevo_alias = '';
		for($i = 0; $i < count($alias); $i++){
			if($i == 0){
				$nuevo_alias .= $alias[$i];
			}else{
			 	$nuevo_alias .= '-'.$alias[$i];
			}
		}
		return $nuevo_alias;
	}
	
}