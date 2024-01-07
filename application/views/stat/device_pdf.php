<pagebreak />
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
		<span><?php echo  __('stat.device.title') ; ?></span>
		
	</div>
	<br class="clear" />
	<div class="content">
		<table border="1">
			<tr>
				<th><?php echo __('stat.device.id_door');?></th>
				<th><?php echo __('stat.device.door_name');?></th>
				<th><?php echo __('stat.device.door_card_count');?></th>
				
			</tr>
			<?php foreach ($list as $data) { ?>
			<tr>
				<td><?php echo iconv('CP1251', 'UTF-8',$data['ID_DOOR']);?></td>
				<td><?php echo iconv('CP1251', 'UTF-8',$data['DOOR_NAME']);?></td>
				<td><?php echo iconv('CP1251', 'UTF-8',$data['DOOR_CARD_COUNT']);?></td>
			</tr>
			<?php };?>
		</table>
	</div>
</div>




