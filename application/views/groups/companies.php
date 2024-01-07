<script type="text/javascript">
	var data = {
		<?php 
		
		$first = true;
		foreach ($list as $item) {
			if (!$first) echo ',';
			echo $item['ID_ORG'] . ': {id: ' . $item['ID_ORG'] . ', name: "' . str_replace('"', '\"', iconv('CP1251', 'UTF-8', $item['NAME'])) . '", parent: '. $item['ID_PARENT'] . ', qty: ' . $item['QTY'] . ', gr: ' . ($item['GR'] == null ? 'false' : 'true') . ', level: ' . $item['LVL'] . '}';
			$first = false;
		}
		?>
	};

	function includeall()
	{
		$('#ph input:checkbox:not(:checked)').click();
	}

	function excludeall()
	{
		$('#ph input:checkbox:checked').click();
	}

	function prepare()
	{
		var s = '';
		$('#ph input[type="checkbox"]').each(function() {
			if (this.checked)
				s += this.id + '|';
		});
		$('#list1').val(s);
	}

	function addChildren(id)
	{
		for (var i in data) {
			if (!i) continue;
			if (i != id && data[i].parent == id) {
				data[i].level = data[id].level + 1;
				$('<div id="div' + i + '" style="padding-left: 30px"><input id="' + i + '" type="checkbox"' + (data[i].gr ? ' checked="checked"' : '') + ' /><span>' + data[i].name + '</span></div>').appendTo('#div' + id);
				if (data[i].qty > 0) {
					addChildren(i);
				} 
			}
		}
	}
	
	$(function() {
		var o = '';
		for (var i in data)
			if (i && data[i] && data[i].gr)
				o += i + '|';
		$('#list0').val(o);
		$('<div id="div1"><input type="checkbox"' + (data[1].gr ? ' checked="checked"' : '') + ' /><span>' + data[1].name + '</span></div>').appendTo('#ph');
		addChildren(1);
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
		<span class="error"><?php echo __('group.title') . ': ' . iconv('CP1251', 'UTF-8', $group['NAME']) ?></span>
		<div class="switch">
			<table cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td>
						<?php echo HTML::anchor('companies/groupedit/' . $group['ID_GROUP'], __('group.common'), array('class' => 'left_switch')); ?>
					</td>
					<td>
						<a href="javascript:" class="middle_switch active"><?php echo __('group.list'); ?></a>
					</td>
					<td>
						<?php echo HTML::anchor('companies/groupacl/' . $group['ID_GROUP'], __('group.acl'), array('class' => 'right_switch')); ?>
					</td>
				</tr>
			</tbody>
			</table>
		</div>
	</div>
	<br class="clear" />
	<div class="content">
		<form action="" method="post" onsubmit="prepare()">
			<div id="ph">
				<div style="float: right">
					<input type="button" value="<?php echo __('group.includeall'); ?>" onclick="includeall()" />
					<input type="button" value="<?php echo __('group.excludeall'); ?>" onclick="excludeall()" />
				</div>
			</div>
			<?php echo Form::hidden('hidden', 'form_sent') . Form::hidden('id', $group['ID_GROUP']); ?>
			<br />
			<br />
			<input type="hidden" name="list0" id="list0" value="" />
			<input type="hidden" name="list1" id="list1" value="" />
			<input type="submit" value="<?php echo __('button.save'); ?>" />
			&nbsp;&nbsp;
			<input type="button" value="<?php echo __('button.cancel'); ?>" onclick="document.forms[0].reset();" />
			&nbsp;&nbsp;
			<input type="button" value="<?php echo __('button.backtolist'); ?>" onclick="location.href='<?php echo URL::base(); ?>companies/groups'" />
		</form> 
	</div>
</div>
