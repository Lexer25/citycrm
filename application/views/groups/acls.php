<script language="javascript">
	$(function() {
		$('#line1 input[type="checkbox"]').attr('disabled', 'disabled').attr('checked', 'checked');

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
		<span class="error"><?php echo __('group.acltitle') . ' "' . iconv('CP1251', 'UTF-8', $group['NAME']) . '"';  ?></span>
		<div class="switch">
			<table cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td>
						<?php echo HTML::anchor('companies/groupedit/' . $group['ID_GROUP'], __('group.common'), array('class' => 'left_switch')); ?>
					</td>
					<td>
						<?php echo HTML::anchor('companies/grouplist/' . $group['ID_GROUP'], __('group.list'), array('class' => 'middle_switch')); ?>
					</td>
					<td>
						<a href="javascript:" class="right_switch active"><?php echo __('group.acl'); ?></a>
					</td>
				</tr>
			</tbody>
			</table>
		</div>
	</div>
	<br class="clear" />
	<div class="content">
		<?php if (count($users) > 0) { ?>
		<form id="form_data" name="form_data" action="" method="post">
			<table class="data" width="100%" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th width="30" rowspan="2"><?php echo __('acl.userid'); ?></th>
						<th width="70%" rowspan="2"><?php echo __('acl.group'); ?></th>
						<th colspan="4" style="text-align: center"><?php echo __('acl.groupwork'); ?></th>
						<th colspan="3" style="text-align: center"><?php echo __('acl.contactwork'); ?>
						<th colspan="1" style="text-align: center"><?php echo __('acl.cardwork'); ?>
					</tr>
					<tr>
						<th><?php echo __('acl.view'); ?></th>
						<th><?php echo __('acl.edit'); ?></th>
						<th><?php echo __('acl.addnew'); ?></th>
						<th><?php echo __('acl.delete'); ?></th>
						<th><?php echo __('acl.edit'); ?></th>
						<th><?php echo __('acl.addnew'); ?></th>
						<th><?php echo __('acl.delete'); ?></th>
						<th><?php echo __('acl.edit'); ?></th>
						<!--
						<th><?php echo __('acl.addnew'); ?></th>
						<th><?php echo __('acl.delete'); ?></th>
						-->
					</tr>
				</thead>
				<tbody>
					<?php foreach ($users as $g) {
						echo	'<tr id="line' . $g['id'] . '">' .
								'<td>' . $g['id'] . Form::hidden('uid[]', $g['id']) . '</td>' .
								'<td>' . HTML::anchor('users/acl/' . $g['id'], $g['name'] . ' ' . $g['surname']) . '</td>' .
								'<td>' . Form::checkbox('o_view[]',		$g['id'], $g['o_view']		== 1, array('id' => 'o_view')) . '</td>' .	
								'<td>' . Form::checkbox('o_edit[]', 	$g['id'], $g['o_edit']		== 1, array('id' => 'o_edit')) . '</td>' .	
								'<td>' . Form::checkbox('o_add[]',		$g['id'], $g['o_add']		== 1, array('id' => 'o_add')) . '</td>' .	
								'<td>' . Form::checkbox('o_delete[]',	$g['id'], $g['o_delete']	== 1, array('id' => 'o_delete')) . '</td>' .
								'<td>' . Form::checkbox('p_edit[]',		$g['id'], $g['p_edit']		== 1, array('id' => 'p_edit')) . '</td>' .	
								'<td>' . Form::checkbox('p_add[]',		$g['id'], $g['p_add']		== 1, array('id' => 'p_add')) . '</td>' .	
								'<td>' . Form::checkbox('p_delete[]',	$g['id'], $g['p_delete']	== 1, array('id' => 'p_delete')) . '</td>' .
								'<td>' . Form::checkbox('c_edit[]',		$g['id'], $g['c_edit']		== 1, array('id' => 'c_edit')) . '</td>' .	
								'<td style="display: none">' . Form::checkbox('c_add[]',		$g['id'], $g['c_add']		== 1, array('id' => 'c_add')) . '</td>' .	
								'<td style="display: none">' . Form::checkbox('c_delete[]',	$g['id'], $g['c_delete']	== 1, array('id' => 'c_delete')) . '</td>' .
								'</tr>';
					} ?>
				</tbody>
			</table>
			<br />
			<br />
			<input type="submit" value="<?php echo __('button.save'); ?>" />
			&nbsp;&nbsp;
			<input type="button" value="<?php echo __('button.cancel'); ?>" onclick="document.forms[0].reset();" />
			&nbsp;&nbsp;
			<input type="button" value="<?php echo __('button.backtolist'); ?>" onclick="location.href='<?php echo URL::base(); ?>companies/groups'" />
		</form>
		<?php } else { ?>
			<div style="margin: 100px 0; text-align: center;">
				<?php echo __('acl.nousers'); ?><br /><br />
			</div>
		<?php } ?>
	</div>
</div>
