<?php

class View {
	
	function __construct() {
		Session::init();
	}
	
	function render($name, $noInclude = false){
		if ($noInclude == true) {
			require BASE_PATH.'/app/views/' . $name . '.php';	
		}else{
			require BASE_PATH.'/app/views/elements/head.php';
			require BASE_PATH.'/app/views/'.$name.'.php';
			require BASE_PATH.'/app/views/elements/footer.php';
		}
		
	}
	
}