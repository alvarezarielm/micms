<?php

class PageModel extends Model {
	
	var $id = null;
	var $title;
	var $menuindex;
	var $public_access;
	var $alias;
	var $parent;
	var $template;
	var $created;
	
	static function model($className = __CLASS__){
		return parent::loadModel($className);
	}
	
// 	var $dependentTables = array(
// 			'Content'
// 	);
	
	
	
// 	function createPage($data){
// 		$connection = DB::connect();
// 		$stmt = $connection->prepare("INSERT INTO `pages` (`title`,`menuindex`, `public_access`,`alias`,`parent`,`template`,`created`) VALUES (:title,:menuindex,:public_access,:alias,:parent,:template,:created )");
// 		$stmt->bindParam(':title', $data['title']);
// 		$stmt->bindParam(':menuindex', $data['menuindex']);
// 		$stmt->bindParam(':public_access', $data['public_access']);
// 		$stmt->bindParam(':alias', $data['alias']);
// 		$stmt->bindParam(':parent', $data['parent']);
// 		$stmt->bindParam(':template', $data['template']);
// 		$stmt->bindParam(':created', date('d-m-Y - h:i'));
// 		$stmt->execute();
		
// 		$lastPageId = $this->db->lastInsertId();
		
// 		$stmt = $connection->prepare("INSERT INTO `content` (`valor_contenido`, `page_id`) VALUES (:content, :page_id)");
// 		$stmt->bindParam(':content', $data['content']);
// 		$stmt->bindParam(':page_id', $lastPageId);
// 		$stmt->execute();
// 		return $lastPageId;		
// 	}	
	
// 	function update($id, $data){
// 		$connection = DB::connect();
// 		$stmt = $connection->prepare("UPDATE `pages` SET `title` = :title, `menuindex` = :menuindex, `public_access` = :public_access, `alias`=:alias,`parent`=:parent, `template` = :template, `modified` = :modified  WHERE `id`=:id");
		
// 		$stmt->bindParam(':title', $data['title']);
// 		$stmt->bindParam(':menuindex', $data['menuindex']);
// 		$stmt->bindParam(':public_access', $data['public_access']);
// 		$stmt->bindParam(':alias', $data['alias']);
// 		$stmt->bindParam(':parent', $data['parent']);
// 		$stmt->bindParam(':template', $data['template']);
// 		$stmt->bindParam(':id', $id);
// 		$stmt->bindParam(':modified', date('d-m-Y - h:i'));
// 		$stmt->execute();

// 		$stmt = $connection->prepare("UPDATE `content` SET `valor_contenido` = :contenido WHERE `id`=:id_contenido");
		
// 		for ($i = 0; $i < count($data);$i++){
// 			if($data[0]['id_contenido_'.$i] != null || $data['contenido_'.$i] != null){
// 				$id_contenido[$i] = $data['id_contenido_'.$i];
// 				$contenido[$i] = $data['contenido'.$i];
	
// 				$stmt->bindParam(':contenido', $contenido[$i]);
// 				$stmt->bindParam(':id_contenido', $id_contenido[$i]);
// 				$stmt->execute();
// 			}
			
// 		}
		
// 		return true;
// 	}
		
// 	function delete($id){
// 		$connection = DB::connect();
// 		$stmt = $connection->query("DELETE FROM pages WHERE id = '".$id."'");
// 		$stmt2 = $connection->query("DELETE FROM content WHERE page_id = '".$id."'");
		
// 		return true;
// 	}
	
	function getPages(){
		$connection = DB::connect();
		$stmt = $connection->query('SELECT * FROM pages ORDER BY menuindex ASC');
		$data = $stmt->fetchAll();
		
		return $data;
	}
	
	function getPage($id){
		$connection = DB::connect();
		if(is_numeric($id)){
			$stmt = $connection->prepare("SELECT * FROM `pages` WHERE `id`= :id");
			$stmt->bindParam(':id', $id);
			$stmt->execute();
			$data = $stmt->fetch();
		}else{
			$stmt = $connection->prepare("SELECT * FROM `pages` WHERE `alias`= :alias");
			$stmt->bindParam(':alias', $id);
			$stmt->execute();
			$data = $stmt->fetch();
		}
		return $data;
	}
	
	function getPageContent($id){
		$connection = DB::connect();
		if(is_numeric($id)){
			$stmt = $connection->prepare("SELECT * FROM `content` WHERE `page_id`= :id");
			$stmt->bindParam(':id', $id);
			$stmt->execute();
			$data = $stmt->fetchAll();
		}else{
			//Consulto el id de la pagina buscando por alias
			$stmt = $connection->prepare("SELECT id FROM `pages` WHERE `alias`= :alias");
			$stmt->bindParam(':alias', $id);
			$stmt->execute();
			$page_id = $stmt->fetchAll();

			$stmt = $connection->prepare("SELECT * FROM `content` WHERE `page_id`= :id");
			$stmt->bindParam(':id', $page_id[0]['id']);
			$stmt->execute();
			$data = $stmt->fetchAll();
		}
		
		
		return $data;
	}
	function getTemplates(){
		$connection = DB::connect();
		$stmt = $connection->prepare("SELECT * FROM `templates`");
		$stmt->execute();
		$data = $stmt->fetchAll();
		return $data;
	}
	
	function getTemplate($id){
		
	}
	
	function loadChild($id){
		$connection = DB::connect();
		$stmt = $connection->prepare("SELECT * FROM `pages` WHERE `parent` = :id");
		$stmt->bindParam(':id', $id);
		$stmt->execute();
		$data = $stmt->fetchAll();
		return $data;
	}
	
	
}