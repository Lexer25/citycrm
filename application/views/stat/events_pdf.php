<?php if ($alert) { ?>
<div class="alert_success">
	<p>
		<img class="mid_align" alt="success" src="images/icon_accept.png" />
		<?php echo $alert; ?>
	</p>
</div>
<?php } ?>

	<div class="header">
		<span><?php echo  __('stat.events.title') ; ?></span>
		
	</div>
	<br class="clear" />
	<div class="content">
	<table border="1">
		<tr>
			<th><?php echo __('stat.events.header1');?></th>
			<th><?php echo __('stat.events.header2');?></th>
			<th><?php echo __('stat.events.header3');?></th>
			<th><?php echo __('stat.events.header4');?></th>
			<th><?php echo __('stat.events.header5');?></th>
		</tr>
		<?php foreach ($list as $value) { ?>
		<tr>
			<td><?php echo $value['id_door'];?></td>
			<td><?php echo iconv('CP1251', 'UTF-8',$value['door_name']);?></td>
			<td><?php echo $value['count_all'];?></td>
			<td><?php echo $value['count_alarm'];?></td>
			<td><?php echo $value['proc_err'];?></td>
		</tr>
		<?php };?>

	</table>
		
	</div>


