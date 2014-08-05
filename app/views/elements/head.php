<!DOCTYPE html>
<html>
	<head>
		<meta name="description" content="">
		<meta name="keywords" content="">
		<script type="text/javascript" src="<?php echo BASE_URL ?>app/template/js/jquery-1.7.1.min.js"></script>
		<?php if ($this->page == 'admin'):?>
			<link rel="stylesheet" href="<?php echo BASE_URL ?>/app/template/css/admin.css" />
			
		<?php else:?>
			<link rel="stylesheet" href="<?php echo BASE_URL ?>app/template/css/style.css" />
			<script type="text/javascript" src="<?php echo BASE_URL ?>app/template/js/cufon-yui.js"></script>
			<script type="text/javascript" src="<?php echo BASE_URL ?>app/template/js/Myriad_Pro_400.font.js"></script>
			<script type="text/javascript" src="<?php echo BASE_URL ?>app/template/js/init.js"></script>
		<?php endif;?>
		
		<title><?php echo $this->site_name?> - <?php echo $this->page_title?></title>
	</head>
	<body>