<h3>Nueva pagina <a class="cancel-btn" href="admin/siteSettings">Cancel</a></h3> 
<form id="createPageForm" action="admin/createPage" method="POST">
	<p><label>Titulo</label></p>
	<p><input class="required" type="text" name="title"></p>
	<p><label>Menu index</label></p>
	<p><input type="text" id="menuIndex" name="menuindex" value=""></p>
	<p><label>Alias</label></p>
	<p><input type="text" id="alias" name="alias" value=""></p>
	<p><label>Template</label></p>
	<select name="template">
		<?php echo $this->template;?>
	</select>
	<p><label>Parent</label></p>
	<select name="parent">
		<?php echo $this->parent;?>
	</select>
	<p>Public access <input type="checkbox" name="public_access" /></p>
	<p><label>Contenido</label></p>
	<textarea name="content"></textarea>
	<p><button>Save</button></p>
	
</form>
<script type="text/javascript">
	 tinyMCE.init({
		    mode : "textareas",
		    theme : "advanced",
		    theme_advanced_toolbar_location: "top",
		    theme_advanced_toolbar_align: "left"
		});	
	 
$('#createPageForm').submit(
		function(e){
			e.preventDefault();
			if(validate($(this))){
				ajaxRequest($(this).attr('action'), $(this).serialize(), 'create');
			}
		}
	);
$('.cancel-btn').click(
	function(e){
		e.preventDefault();
		loadSettingsView();
	}
);


</script>