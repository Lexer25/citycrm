<script language="javascript">
	$(function() {
		$('input[id="o_edit"]').each(function() {
			if (!this.checked) $('#o_add, #o_delete', $(this).parent().parent()).attr('disabled', 'disabled').removeAttr('checked');
		});
		$('input[id="o_view"]').each(function() {
			if (!this.checked) $('#o_edit, #o_add, #o_delete', $(this).parent().parent()).attr('disabled', 'disabled').removeAttr('checked');
		});
		
		$('input[id="o_view"]').click(function() {
			if (this.checked)
				$('#o_edit, #o_add, #o_delete', $(this).parent().parent()).removeAttr('disabled');
			else
				$('#o_edit, #o_add, #o_delete', $(this).parent().parent()).attr('disabled', 'disabled').removeAttr('checked');
		});
		$('input[id="o_edit"]').click(function() {
			if (this.checked)
				$('#o_add, #o_delete', $(this).parent().parent()).removeAttr('disabled');
			else
				$('#o_add, #o_delete', $(this).parent().parent()).attr('disabled', 'disabled').removeAttr('checked');
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
						<th style="display: none"><?php echo __('acl.addnew'); ?></th>
						<th style="display: none"><?php echo __('acl.delete'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($acls as $id => $g) {
						//echo "<hr><pre>";
						//print_r($g);
						//echo "</pre><hr>";
						//die;
						echo	'<tr id="line' . $id . '">' .
								'<td style="text-align: center;">' . $id . Form::hidden('gid[]', $id) . '</td>' .
								'<td>' . HTML::anchor('companies/groupacl/' . $id, $g['name']) . '</td>' .
								'<td style="text-align: center;">' . Form::checkbox('o_view[]',		$id, $g['o_view']	== 1, array('id' => 'o_view')) . '</td>' .	
								'<td style="text-align: center;">' . Form::checkbox('o_edit[]',		$id, $g['o_edit']	== 1, array('id' => 'o_edit')) . '</td>' .	
								'<td style="text-align: center;">' . Form::checkbox('o_add[]',		$id, $g['o_add']	== 1, array('id' => 'o_add')) . '</td>' .	
								'<td style="text-align: center;">' . Form::checkbox('o_delete[]',	$id, $g['o_delete']	== 1, array('id' => 'o_delete')) . '</td>' .
								'<td style="text-align: center;">' . Form::checkbox('p_edit[]',		$id, $g['p_edit']	== 1, array('id' => 'p_edit')) . '</td>' .	
								'<td style="text-align: center;">' . Form::checkbox('p_add[]',		$id, $g['p_add']	== 1, array('id' => 'p_add')) . '</td>' .	
								'<td style="text-align: center;">' . Form::checkbox('p_delete[]',	$id, $g['p_delete']	== 1, array('id' => 'p_delete')) . '</td>' .
								'<td style="text-align: center;">' . Form::checkbox('c_edit[]',		$id, $g['c_edit']	== 1, array('id' => 'c_edit')) . '</td>' .	
								'<td style="text-align: center; display: none">' . Form::checkbox('c_add[]',		$id, $g['c_add']	== 1, array('id' => 'c_add')) . '</td>' .	
								'<td style="text-align: center; display: none">' . Form::checkbox('c_delete[]',	$id, $g['c_delete']	== 1, array('id' => 'c_delete')) . '</td>' .
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
