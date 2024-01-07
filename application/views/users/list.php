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
		<div id="search">
			<form action="users/search" method="post">
				<input type="text" class="search noshadow" title="<?php echo __('search'); ?>" name="q" id="q" value="<?php if (isset($filter)) echo $filter; ?>" />
			</form>
		</div>
		<span><?php echo __('users.title'); ?></span>
	</div>
	<br class="clear"/>
	<div class="content">
		<form id="form_data" name="form_data" action="" method="post">
			<table class="data" width="100%" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<!--
						<th style="width:10px"><input type="checkbox" id="check_all" name="check_all"/></th>
						-->
						<th style="width:5%">ID</th>
						<th style="width:15%"><?php echo __('users.username'); ?></th>
						<th style="width:30%"><?php echo __('users.email'); ?></th>
						<th style="width:40%"><?php echo __('users.fullname'); ?></th>
						<th style="width:10%"><?php echo __('users.action'); ?></th>
						<th style="width:10%"><?php echo __('users.action'); ?></th>
						
					</tr>
				</thead>
				<tbody>
					<?php //echo Kohana::Debug($users);
					foreach ($users as $user) {
					?>
					<tr>
						<!--
						<td><input type="checkbox"/></td>
						-->
						
						<td><?php echo $user->id; ?></td>
						<td><?php echo HTML::anchor('users/edit/' . $user->id, $user->username); ?></td>
						<td><?php echo $user->email; ?></td>
						<td nowrap="nowrap"><?php echo $user->name . ' ' . $user->surname; ?></td>
						<td>
							<?php if ($user->id != 1) { ?>
							<a href="users/acl/<?php echo $user->id; ?>"><img src="images/icon_access.png" alt="acl" class="help" title="<?php echo __('tip.acl'); ?>"/></a>
							
							<a href="javascript:" onclick="if (confirm('<?php echo __('users.confirmdelete'); ?>')) location.href='<?php echo URL::base() . 'users/delete/' . $user->id; ?>';">
								<?php echo HTML::image('images/icon_delete.png', array('title' => __('tip.delete'), 'class' => 'help')); ?>
							</a>
							<?php } ?>
						</td>
						<td><?php echo $user->username; ?></td>
						
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<div id="chart_wrapper" class="chart_wrapper"></div>
		<!-- End bar chart table-->
		</form>
		
		<?php echo $pagination; ?>
	</div>
</div>
