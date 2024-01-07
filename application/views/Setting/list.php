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
		<span><?php echo __('setting.grouptitle');?></span>
		<?php if (TRUE) { ?>
		<div class="switch">
			<table cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td>
						<?php
							echo HTML::anchor('settings/main', __('setting.mainConfig'), array('class' => 'left_switch', 'disabled'=>'disabled')); 
						 	
						?>
					</td>
					<td>
						<?php
							echo HTML::anchor('settings/list', __('setting.listConfig'), array('class' => 'left_switch', 'disabled'=>'disabled')); 
						 	
						?>
					</td>
					<td>
						<a href="javascript:" class="right_switch active"><?php echo __('setting'); ?></a>
					</td>
				</tr>
			</tbody>
			</table>
		</div>
		<?php }
		 ?>
	</div>
	<br class="clear"/>
	<div class="content">
				<fieldset>
						<legend><?php echo __('setting.settingList'); ?></legend>
						<div>
							<?php
							//echo Debug::vars('89', $groupList); exit;
							echo Form::open('settings/changegroup');
								 foreach($groupList as $key=>$value){
									
									//echo Debug::vars('62', $key, $value);exit;
									
									echo HTML::anchor('settings/edit/'.$value, $value ).'<br>';
									
									
								} 

							 echo Form::close();
							 
							 								

							 ?>
							
							
						</div>
					</fieldset>
					<?php if(Session::instance()->get('canModSetting')){?>
					<fieldset>
							<legend><?php echo __('setting.addNewGroup'); ?></legend>
							
							<br />
						<div>	
							<?php
							$group='main';
							echo Form::open('settings/addNewGroup');
									echo Form::hidden('group', $group);
									echo __('setting.addNewGroup').' '.Form::input('key[addNewGroup]', 'new').'<br>';
								
								
								 echo Form::submit(NULL, 'Add');
								echo '<br>';
							 echo Form::close();
							 ?>
							 </div>
					</fieldset>
					<?php }?>
						

	</div>
</div>
