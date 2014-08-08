<h3><?php echo ucfirst($this->page_title);?> content <span><?php if(empty($this->modified)){echo 'Creado: '.$this->created; }else{ echo 'Modificado: '.$this->modified;}?></span></h3>
<form id="editPageForm" action="<?php echo BASE_URL?>admin/savePage/<?php echo $this->page_id; ?>" method="POST">
	<p><label>Titulo</label></p>
	<input type="text" id="editTitle" name="Page[title]" value="<?php echo $this->page_title;?>">
	<p><label>Menu index</label></p>
	<input type="text" id="menuIndex" name="Page[menuindex]" value="<?php echo $this->menuindex;?>">
	<p><label>URL Alias</label></p>
	<input type="text" id="alias" name="Page[alias]" value="<?php echo $this->alias;?>">
	<p><label>Template</label></p>
	<select name="Page[template]">
		<?php echo $this->template;?>
	</select>
	<p><label>Parent</label></p>
	<select name="Page[parent]">
		<?php echo $this->parent;?>
	</select>
	<p>Public access <input type="checkbox" value="1" name="Page[public_access]" <?php if($this->public_access){echo 'checked=""';}?>/></p>
	<?php $i = 0;
		 foreach ($this->content as $content):?>
		 	<?php $i++?>
		 <h4>Contenido <?php echo $i?></h4>
		<textarea id="editor<?php echo $i?>" title="<?php echo $content['id']?>" name="contenido<?php echo $i?>"><?php echo $content['valor_contenido'];?></textarea>
		<input type="hidden" name="id_contenido_<?php echo $i ?>" value="<?php echo $content['id']?>"/>
	<?php endforeach;?>
	<p><button>Save</button></p>
</form>

<script type="text/javascript">

	tinyMCE.init({
	    mode : "textareas",
	    theme : "advanced",
	    theme_advanced_toolbar_location: "top",
	    theme_advanced_toolbar_align: "left"
	});

$('#editPageForm').submit(
	function(e){
		ajaxRequest($(this).attr('action'), $(this).serialize(), 'edit');
		return false;
	}
);
</script>