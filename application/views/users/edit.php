<script language="javascript">
	function validate()
	{
		return true;
	}
</script>
<div class="alert_success" style="display: none">
	<p>
		<img class="mid_align" alt="success" src="images/icon_accept.png" />
		Success Notification
	</p>
</div>
<?php //echo Kohana::Debug($user);?>
<?php if (isset($alert)) { ?>
<div class="alert_error">
	<p>
		<img class="mid_align" alt="error" src="images/icon_error.png" />
		<?php echo $alert; ?>
	</p>
</div>
<?php } ?>
<div class="onecolumn">
	<div class="header">
		<span><?php echo __('user.title') ?></span>
		<?php if ($user->id && $user->id != 1) { ?>
		<div class="switch">
			<table cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td>
						<a href="javascript:" class="left_switch active"><?php echo __('user.data'); ?></a>
					</td>
					<td>
						<?php echo HTML::anchor('users/acl/' . $user->id, __('user.acl'), array('class' => 'right_switch')); ?>
					</td>
				</tr>
			</tbody>
			</table>
		</div>
		<?php } ?>
	</div>
	<br class="clear" />
	<div class="content">
		<form action="" method="post" onsubmit="return validate()">
			<input type="hidden" name="hidden" value="form_sent" />
			<input type="hidden" name="id" value="<?php echo $user->id; ?>" />
			<p>
				<label for="surname"><?php echo __('contact.surname'); ?></label>
				<br />
				<input type="text" size="50" name="surname" id="surname" value="<?php echo $user->surname; ?>" />
			</p>
			<br />
			<p>
				<label for="name"><?php echo __('contact.name'); ?></label>
				<br />
				<input type="text" size="50" name="name" id="name" value="<?php echo $user->name; ?>" />
			</p>
			<br />
			<p>
				<label for="email"><?php echo __('contact.email'); ?></label>
				<br />
				<input type="text" size="50" name="email" id="email" value="<?php echo $user->email; ?>" />
			</p>
			<br />
			<p>
				<label for="username"><?php echo __('contact.login'); ?></label>
				<br />
				<input type="text" name="username" id="username" size="12" value="<?php echo $user->username; ?>" />
			</p>
			<br />
			<p<?php if ($user->id < 0) echo ' style="display: none"'; ?>>
				<label for="password"><?php echo __('contact.password'); ?></label>
				<br />
				<input type="password" size="12" name="password" id="password" value="" />
			</p>
			<br />
			<br />
			<input type="submit" value="<?php echo __('button.save'); ?>" />
			&nbsp;&nbsp;
			<input type="button" value="<?php echo __('button.cancel'); ?>" onclick="document.forms[0].reset()" />
			&nbsp;&nbsp;
			<input type="button" value="<?php echo __('button.backtolist'); ?>" onclick="location.href='<?php echo URL::base(); ?>users'" />
		</form>
	</div>
</div>
