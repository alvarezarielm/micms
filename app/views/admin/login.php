<div id="loginForm">
	<h2>Admin</h2>
	
	<form action="<?php echo BASE_URL ?>admin/login" method="POST">
		<label>User</label>
		<p><input type="text" name="username" /></p>
		<label>Password</label>
		<p><input type="password" name="password" /></p>
		<button>Login</button>
		<a href="<?php echo BASE_URL?>admin/forgotPassword">Olvidaste tu contrase&ntilde;a?</a>
		<div class="clearfix"></div>
	</form>
</div>