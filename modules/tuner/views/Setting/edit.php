<?php if ($alert) { ?>
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
				<input type="text" class="search noshadow" title="<?php echo __('setting.auth'); ?>" name="llog" id="llog" value="<?php if (isset($filter)) echo $filter; ?>" />
			</form>
		</div>
		<span><?php echo __('setting.keytitle').' '.$group;?></span>
		<?php if (TRUE) { ?>
		<div class="switch">
			<table cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td>
						<?php
							echo HTML::anchor('settings/main', __('setting.main'), array('class' => 'left_switch', 'disabled'=>'disabled')); 
						 	
						?>
					</td>
					<td>
						<?php
							echo HTML::anchor('settings/list', __('setting.list'), array('class' => 'left_switch', 'disabled'=>'disabled')); 
						 	
						?>
					</td>
					<td>
						<a href="javascript:" class="right_switch active"><?php echo __('setting'); ?></a>
					</td>
				</tr>
			</tbody>
			</table>
		</div>
		<?php } ?>
	</div>
	<br class="clear"/>
	<div class="content">
					<fieldset>
						<legend><?php echo __('setting.keyList'); ?></legend>
						<div>
							<?php
							//echo Debug::vars('89', $groupList); 
							echo Form::open('settings/changekey');
								 foreach($keyList as $key=>$value){
									
									//echo Debug::vars('62', $key, $value);//exit;
									echo Form::hidden('group', $group);
									echo Form::radio('selectKey', $key).Form::input('key['.$key.']', $key, array('value'=>$key)).'<br>';
									
									
								} 
								
								echo '<br>';
								if(Session::instance()->get('canModSetting') || 1) echo __('withSelected').' '.Form::submit('deleteKey', __('deleteKey')).Form::submit('updateKeyName', __('updateKeyName'));
								echo '<br>';
							 echo Form::close();
							 ?>
							
							
						</div>
					</fieldset>

			<?php if(Session::instance()->get('canModSetting') || 1){ ?>		
							<fieldset>
								<legend><?php echo __('setting.addNewKey'); ?></legend>
							
							<br />
							
							<?php
							echo Form::open('settings/addNewKey');
									echo Form::hidden('group', $group);
									echo __('setting.addNewKey').' '.Form::input('key[addNewKey]', 'new').'<br>';
								
								if(Session::instance()->get('canModSetting') || 1) echo Form::submit(NULL, 'Save');
								echo '<br>';
							 echo Form::close();
							 ?>
							</fieldset>
							
			<?php }?>
			
			<?php 
	
	//$group='main';
	if(Session::instance()->get('canModSetting') OR 1){
	 ?>
		<fieldset>
						<legend><?php echo __('setting.'.$group); ?></legend>
						<div>
							<fieldset>
								<legend><?php echo __('setting.mainoptions'); ?></legend>
							<?php 
							echo Form::open('settings/save');
							echo Form::hidden('group', $group);
							foreach(Kohana::$config->load($group) as $key=>$value)
							{
								$var=Arr::get(Kohana::$config->load($group), $key);
								//echo Debug::vars('105',$group, $key, $value, Kohana::$config->load($group)); exit;
								if(!is_array($value)){
									echo __('setting.'.$key).' ';
									
									echo Form::input($key, $value, array('value'=>$value));
									echo '<br>';
									
								}
								
								}
								
								 echo Form::submit(NULL, 'Save');
								echo '<br>';
							echo Form::close();
							?>
							
							</fieldset>

						</div>
						</fieldset>
	<?php }?>
	
	
				
		<?php if(Session::instance()->get('canModSetting') || 1){ ?>		
						<fieldset>
						<legend><?php echo __('setting.'.$group); ?></legend>
						<div>
							<fieldset>
								<legend><?php echo __('setting.mainoptions'); ?></legend>
							<?php 
							echo Form::open('settings/save');
							echo Form::hidden('group', $group);
							?>
							<table class="data tablesorter-blue" width="100%" cellpadding="0" cellspacing="0" id="tablesorter" >

							<tr>
								<th>key</th>
								<th>Val</th>
								<th>Default</th>
								<th>Desc</th>
								<th>is_active</th>
								<th>mode</th>
							</tr>
							<?php
							foreach(Kohana::$config->load($group) as $key=>$value){
								if(is_array($value)){
									$var=Arr::get(Kohana::$config->load($group), $key);
									//echo Debug::vars($key, Kohana::$config->load($group));
									//echo Debug::vars('154', Arr::get($var, 'val'));
									echo '<tr>';
									echo '<td>'.__($key).'</td>';
									echo '<td>';	
									
									echo Form::input('key['.$key.'][val]', Arr::get($var, 'val', 0), array('value'=>$value)).'</td>';
									echo '<td>'.Form::input('key['.$key.'][default]', Arr::get($var, 'default', 0), array('value'=>$value)).'</td>';
									echo '<td>'.Form::input('key['.$key.'][desc]',  Arr::get($var, 'desc', 0), array('value'=>$value)).'</td>';
									echo '<td>'.Form::input('key['.$key.'][is_active]',  Arr::get($var, 'is_active', 0), array('value'=>$value)).'</td>';
									echo '<td>'.Form::input('key['.$key.'][mode]',  Arr::get($var, 'mode', 0), array('value'=>$value)).'</td>';
								echo '</tr>';
								}
							}
								
								echo '<br>';
							
							?>

							</table>
							</fieldset>
						<?php 
							
							echo Form::submit(NULL, 'Save');
							echo Form::close();
						?>
						</div>
						</fieldset>
							
			<?php }?>			

	</div>
</div>
