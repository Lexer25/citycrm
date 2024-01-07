<script language="javascript">
	$(function() {
		$('input[id="edit"]').each(function() {
			if (!this.checked) $('#add, #delete', $(this).parent().parent()).attr('disabled', 'disabled').removeAttr('checked');
		});
		$('input[id="view"]').each(function() {
			if (!this.checked) $('#edit, #add, #delete', $(this).parent().parent()).attr('disabled', 'disabled').removeAttr('checked');
		});
		
		$('input[id="view"]').click(function() {
			if (this.checked)
				$('#edit, #add, #delete', $(this).parent().parent()).removeAttr('disabled');
			else
				$('#edit, #add, #delete', $(this).parent().parent()).attr('disabled', 'disabled').removeAttr('checked');
		});
		$('input[id="edit"]').click(function() {
			if (this.checked)
				$('#add, #delete', $(this).parent().parent()).removeAttr('disabled');
			else
				$('#add, #delete', $(this).parent().parent()).attr('disabled', 'disabled').removeAttr('checked');
		});
	});
</script>
<?php if (isset($alert)) { ?>
<div class="alert_success">
	<p>
		<img class="mid_align" alt="success" src="images/icon_accept.png" />
		<?php echo $alert; ?>
	</p>
</div>
<?php } ?>
<div class="onecolumn">
	<div class="header">
		<span><?php echo __('acl.title', array(':user' => $user->username)) ?></span>
		<?php if ($user) { ?>
		<div class="switch">
			<table cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td>
						<?php echo HTML::anchor('users/edit/' . $user->id, __('user.data'), array('class' => 'left_switch')); ?>
					</td>
					<td>
						<a href="javascript:" class="right_switch active"><?php echo __('user.acl'); ?></a>
					</td>
				</tr>
			</tbody>
			</table>
		</div>
		<?php } ?>
	</div>
	<br class="clear" />
	<div class="content">
		<form action="" method="post">
			<input type="hidden" name="hidden" value="form_sent" />
			<input type="hidden" name="id" value="<?php echo $user->id; ?>" />
			<table class="data" width="100%" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th width="30"><?php echo __('acl.userid'); ?></th>
						<th width="70%"><?php echo __('acl.group'); ?></th>
						<th><?php echo __('acl.view'); ?></th>
						<th><?php echo __('acl.edit'); ?></th>
						<th><?php echo __('acl.addnew'); ?></th>
						<th><?php echo __('acl.delete'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($acls as $id => $g) {
						echo	'<tr id="line' . $id . '">' .
								'<td>' . $id . Form::hidden('gid[]', $id) . '</td>' .
								'<td>' . HTML::anchor('companies/groupacl/' . $id, iconv('CP1251', 'UTF-8', $g['name'])) . '</td>' .
								'<td>' . Form::checkbox('view[]', $id, $g['view'] == 1, array('id' => 'view')) . '</td>' .	
								'<td>' . Form::checkbox('edit[]', $id, $g['edit'] == 1, array('id' => 'edit')) . '</td>' .	
								'<td>' . Form::checkbox('add[]', $id, $g['add'] == 1, array('id' => 'add')) . '</td>' .	
								'<td>' . Form::checkbox('delete[]', $id, $g['delete'] == 1, array('id' => 'delete')) . '</td>' .
								'</tr>';
					} ?>
				</tbody>
			</table>
			<br />
			<?php if (!$isadmin) { ?>
			<input type="submit" value="<?php echo __('button.save'); ?>" />
			&nbsp;&nbsp;
			<input type="button" value="<?php echo __('button.cancel'); ?>" onclick="document.forms[0].reset()" />
			&nbsp;&nbsp;
			<?php } ?>
			<input type="button" value="<?php echo __('button.backtolist'); ?>" onclick="location.href='<?php echo URL::base(); ?>users'" />
		</form>
	</div>
</div>
