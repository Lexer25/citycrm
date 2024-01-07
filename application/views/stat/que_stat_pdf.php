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
		<span><?php echo  __('stat.title.que_mess') ; ?></span>
	</div>
	<br class="clear" />
	
	<div class="content">
	<?echo __('queue.desc_1', array(':tables' => __('queue.list_card_for_load_mess'), ':column'=>__('stat.result_load'), ':attempt'=>__('queue.count_attempt')));?>
	<br>
	
	<label for="name"><?echo __('queue.list_card_for_load_mess');?></label>
	<table border="1">
		<tr>
			<th><?php echo __('stat.device.id_door');?></th>
			<th><?php echo __('load.device');?></th>
			<th><?php echo __('stat.card_count');?></th>
			<th><?php echo __('stat.door_is_active');?></th>
			<th><?php echo __('stat.result_load');?></th>
			
		</tr>
		<?php foreach ($que_mess as $mess){ ?>
		<tr>
			<td><?php echo $mess['ID_DEV'];?></td>
			<td><?php echo iconv('CP1251', 'UTF-8', $mess['NAME']);?></td>
			<td><?php echo $mess['COUNT'];?></td>
			<td><?php echo ($mess['IS_ACTIVE']==0)? __('stat.not_is_active') : __('stat.is_active');?></td>
			<td><?php echo iconv('CP1251', 'UTF-8', $mess['LOAD_RESULT']);?></td>
		</tr>
		<?php };?>
	</table>
	<?echo __('queue.desc_1');?>
	<br>
	<br>
	<label for="name"><?echo __('queue.list_card_for_load_common');?></label>
	<?if (!empty($list_load)){?>
	<table border="1">
		<tr>
			<th><?php echo __('stat.device.id_door');?></th>
			<th><?php echo __('load.device');?></th>
			<th><?php echo __('stat.card_count');?></th>
			<th><?php echo __('queue.count_attempt');?></th>
			<th><?php echo __('stat.door_is_active');?></th>
		</tr>
		<?php foreach ($list_load as $mess){ ?>
		<tr>
			<td><?php echo $mess['ID_DEV'];?></td>
			<td><?php echo iconv('CP1251', 'UTF-8', $mess['NAME']);?></td>
			<td><?php echo $mess['COUNT'];?></td>
			<td><?php echo iconv('CP1251', 'UTF-8', $mess['MAX']);?></td>
			<td><?php echo iconv('CP1251', 'UTF-8', $mess['DOOR_IS_ACTIVE'] * $mess['CONTROLLER_IS_ACTIVE']);?></td>
			</tr>
		<?php };?>
	</table>
	<? } else {
		echo __('queue.list_card_for_load_empty');
		}
	?>
	<br>
	<label for="name"><?echo __('queue.list_card_for_del_common');?></label>
	<br>
	<?if (!empty($list_delete)){?>
	<table border="1">
		<tr>
			<th><?php echo __('stat.device.id_door');?></th>
			<th><?php echo __('load.device');?></th>
			<th><?php echo __('stat.card_count');?></th>
			<th><?php echo __('queue.count_attempt');?></th>
			<th><?php echo __('stat.door_is_active');?></th>
		</tr>
		<?php foreach ($list_delete as $mess){ ?>
		<tr>
			<td><?php echo $mess['ID_DEV'];?></td>
			<td><?php echo iconv('CP1251', 'UTF-8', $mess['NAME']);?></td>
			<td><?php echo $mess['COUNT'];?></td>
			<td><?php echo iconv('CP1251', 'UTF-8', $mess['MAX']);?></td>
			<td><?php echo iconv('CP1251', 'UTF-8', $mess['DOOR_IS_ACTIVE'] * $mess['CONTROLLER_IS_ACTIVE']);?></td>
			</tr>
		<?php };?>
	</table>
	<? } else {
		echo __('queue.list_card_for_del_empty');
		}
	?>
		
	</div>
</div>
