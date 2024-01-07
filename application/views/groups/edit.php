<script type="text/javascript">
<!--
	function validate()
	{
		$('#error1, #error2').hide();
		if ($('#name').val() == '') {
			$('#error1').show();
			$('#name').focus();
			return false;
		} else if ($('#code').val() == '') {
			$('#error2').show();
			$('#code').focus();
			return false;
		}
	}
//-->
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
		<span class="error"><?php echo $group ? __('group.title') . ' "' . iconv('CP1251', 'UTF-8', $group['NAME']) . '"' : __('group.newgroup');  ?></span>
		<?php if ($group) { ?>
		<div class="switch">
			<table cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td>
						<a href="javascript:" class="left_switch active"><?php echo __('group.common'); ?></a>
					</td>
					<td>
						<?php echo HTML::anchor('companies/grouplist/' . $group['ID_GROUP'], __('group.list'), array('class' => 'middle_switch')); ?>
					</td>
					<td>
						<?php echo HTML::anchor('companies/groupacl/' . $group['ID_GROUP'], __('group.acl'), array('class' => 'right_switch')); ?>
					</td>
				</tr>
			</tbody>
			</table>
		</div>
		<?php } ?>
	</div>
	<br class="clear" />
	<div class="content">
		<form action="companies/groupsave" method="post" onsubmit="return validate()">
			<?php echo Form::hidden('hidden', 'form_sent') . Form::hidden('id', $group['ID_GROUP']); ?>
			<p>
				<label for="name"><?php echo __('group.name'); ?></label>
				<br />
				<input type="text" id="name" name="name" size="50" value="<?php echo iconv('CP1251', 'UTF-8', $group['NAME']); ?>" />
				<br />
				<span class="error" id="error1" style="color: red; display: none;"><?php echo __('group.emptyname'); ?></span>
			</p>
			<br />
			<p>
				<label for="desc"><?php echo __('group.description'); ?></label>
				<br />
				<input type="text" id="desc" name="desc" size="50" value="<?php echo iconv('CP1251', 'UTF-8', $group['DESCRIPTION']); ?>" />
				<br />
			</p>
			<br />
			<br />
			<input type="submit" value="<?php echo __('button.save'); ?>" />
			&nbsp;&nbsp;
			<input type="button" value="<?php echo __('button.cancel'); ?>" onclick="document.forms[0].reset();" />
			&nbsp;&nbsp;
			<input type="button" value="<?php echo __('button.backtolist'); ?>" onclick="location.href='<?php echo URL::base(); ?>companies/groups'" />
		</form> 
	</div>
</div>
