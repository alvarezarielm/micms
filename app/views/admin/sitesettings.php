<h3>Site settings</h3>
<form id="siteSettings" action="admin/saveSettings" method="POST">
	<p><label>Site name</label></p>
	<input type="text" name="site_name" value="<?php echo $this->site_name;?>">
	<p>Friendly url's <input type="checkbox" name="friendly_urls" <?php if($this->friendly_urls){echo 'checked=""';}?>/></p>
	<button>Save Settings</button>
</form>