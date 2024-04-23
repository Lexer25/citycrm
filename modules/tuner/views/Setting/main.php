<?php 
//echo Debug::vars('2', $group); exit;
if ($alert) { ?>
<div class="alert_success">
	<p>
		<img class="mid_align" alt="success" src="images/icon_accept.png" />
		<?php echo $alert; ?>
	</p>
</div>
<?php } ?>
<div class="onecolumn">
	<div class="header">
		<div id="search"<?php if (isset($hidesearch)) echo ' style="display: none;"'; ?>>
			<form action="settings/auth" method="post">
			<?php 
			if(!Session::instance()->get('canModSetting')){?>
				<input type="text" class="search noshadow" title="<?php echo __('setting.auth'); ?>" name="llog" id="llog" value="<?php if (isset($filter)) echo $filter; ?>" />
			<?php } else {
				
				echo Form::submit(NULL, 'OUT');
			}?>
			</form>
		</div>
		<span><?php echo __('setting.main_title');?></span>

		<?php
			include Kohana::find_file('views\Setting','topbuttonbar');
		?>

	</div>
	<br class="clear"/>
	<div class="content">
	
	<?php 
	
	//$group='main';
	if(Session::instance()->get('canModSetting') OR 1){
	 ?>
		<fieldset>
						<legend><?php echo __('setting.'.$group); ?></legend>
						<div>
							
							<?php 
							echo Form::open('settings/save');
							echo Form::hidden('group', $group);
							foreach($mainConfg as $key=>$value){
								echo $key.' '.__('setting.'.$key).' '.Form::input('key['.$key.']', Arr::get($mainConfg, $key), array('value'=>$value)). Kohana::message('messmain', $key);
								echo '<br>';
								}
								
								 echo Form::submit(NULL, 'Save');
								echo '<br>';
							echo Form::close();
							?>
							
					

						</div>
						</fieldset>
	<?php }
	
	echo Kohana::message('messmain', 'formatDescription');
	?>

	</div>
</div>
