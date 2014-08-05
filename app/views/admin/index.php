<script type="text/javascript" src="<?php echo BASE_URL?>app/template/js/tiny_mce/tiny_mce.js"></script>
<div id="topBar">
	<div class="bar-wrapper">
		<div class="bar-info">
			<h2>Administrador</h2>
			
			Logueado como <?php echo $this->username;?> <a href="<?php echo BASE_URL ?>admin/logout">Logout</a>
		</div>
	</div>
	
</div>
<div id="admin">		
		<div id="wrapper">
			
			
			
			<div id="leftPanel">
				<h3>Pages<a class="addpage-btn" href="#">+ Add Page</a></h3>
				
				<p>&nbsp;</p>
				<ul id="menu">
					
				</ul>
			</div>
			<div id="content">
			<div id="response"></div>
				<div id="ajaxContent">
					
				</div>
			</div>
		</div>
		<script type="text/javascript">
		$(document).ready(
				function(){
					$('#menu').empty().html('<img src="<?php echo BASE_URL?>app/template/images/loading.gif" />');
					init();
				});

		function ajaxRequest(url, data, action){
			$.post(url, data, function(id){
					returnPages();
					if(action == 'delete'){
						loadResponse(html);
						return false;
					}
					if(action == 'create'){
						html='Pagina creada';
						returnEdit(id);
					}
					loadResponse(html);
					returnEdit(id);
				}
			);
		}
		
		function returnPages(){
			$.ajax({
				url: '<?php echo BASE_URL?>admin/getMenuPages',
				success: function(html){
					$('#menu').html(html);
					$('#menu li a.page-item').each(
							function(i){
								$(this).click(
									function(e){
										id = $(this).attr('rel');
										if(e){
											returnEdit(id);
										}
										return false;
									}
								);
							}
						);
				}
			})
		}

		function loadSettingsView(){
			$.ajax({
				url: '<?php echo BASE_URL?>admin/siteSettings',
				success: function(html){
					$('#ajaxContent').empty().html('<img class="loading" src="<?php echo BASE_URL?>app/template/images/loading.gif" />');
					$('#ajaxContent').html(html);
				}
			});
		}
		
		function returnEdit(id){
			$.ajax({
				url: '<?php echo BASE_URL?>admin/editPage/'+id,
				success: function(html){
					$('#ajaxContent').empty().html('<img class="loading" src="<?php echo BASE_URL?>app/template/images/loading.gif" />');
					$('#ajaxContent').html(html);
				}
			});
		}

		function validate(form){
			errors = 0;
			form.find('.required').each(
				function(i){
					if($(this).val()==''){
						errors++;
						$(this).parent().append('<span class="error">Required field<span>');
						$(this).css('border','1px solid red');
					}
				}
			);
			if(errors>0){
				return false;
			}else{
				return true;
			}
			
		}
		
		function clickBinds(){
			$('.addpage-btn').click(
				function(e){
					$.ajax({
						url: '<?php echo BASE_URL?>admin/addPage',
						success: function(html){
							$('#ajaxContent').html(html);
						}
					})
					return false;
				}
			);
			$('.del').live('click', function(e) {
				e.preventDefault();
				var id = $(this).attr('title');
				ajaxRequest('<?php echo BASE_URL?>admin/deletePage/'+id, null, 'delete');
				loadSettingsView();
			});
		}
		function loadResponse(response){
			$('#response').html(response);
			$('#response').fadeIn();
			setTimeout("$('#response').fadeOut()", 2000);
		}
		function init(){
			returnPages();
			clickBinds();
			loadSettingsView();
		}
		</script>
</div>