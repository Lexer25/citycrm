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
		<?php if (1) { ?>
		<div class="switch">
			<table cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td>
						<?php echo HTML::anchor('stats/about/' , __('stat.form1') , array('class' => 'left_switch')); ?>
					</td>
					<td>
						<a href="javascript:" class="middle_switch active"><?php echo __('stat.title.que_but'); ?>
					</td>
					<td>
						<?php echo HTML::anchor('stats/device/' ,  __('stat.form2') ,array('class' => 'middle_switch')); ?>
					</td>
					<td>
						<?php echo HTML::anchor('stats/events/' , __('stat.form3') ,array('class' => 'middle_switch')); ?>
					</td>
					<td>
						<?php echo HTML::anchor('stats/save/' , __('stat.form4') ,array('class' => 'right_switch')); ?>
					</td>
				</tr>
			</tbody>
			</table>
		</div>
		<?php } ?>
	</div>
	<br class="clear" />
	
	<div class="content">
	<?echo __('queue.desc_1', array(':tables' => __('queue.list_card_for_load_mess'), ':column'=>__('stat.result_load'), ':attempt'=>__('queue.count_attempt')));?>
	<br>
	<label for="name"><?echo __('queue.list_card_for_load_mess');?></label>
	<table class="data" width="100%" cellpadding="0" cellspacing="0">
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
			<td><?php echo HTML::anchor('queue/search_id_dev/'.$mess['ID_DEV'], $mess['COUNT']);?></td>
			<td><?php echo ($mess['IS_ACTIVE']==0)? __('stat.not_is_active') : __('stat.is_active');?></td>
			<td><?php echo (!empty($mess['LOAD_RESULT']))? Text::limit_chars(iconv('CP1251', 'UTF-8', $mess['LOAD_RESULT']), 400) : __('stat.queue.no_result');?></td>
		</tr>
		<?php };?>
	</table>
	<br>
	<br>
	<label for="name"><?echo __('queue.list_card_for_load_common');?></label>
	<?if (!empty($list_load)){?>
	<table class="data" width="100%" cellpadding="0" cellspacing="0">
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
	<table class="data" width="100%" cellpadding="0" cellspacing="0">
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

