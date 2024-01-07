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
		<?php if (1) { ?>
		<div class="switch">
			<table cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td>
						<?php echo HTML::anchor('stats/about/' , __('stat.form1') , array('class' => 'left_switch')); ?>
					</td>
					<td>
						<?php echo HTML::anchor('stats/queue_message/' ,  __('stat.title.que_but') ,array('class' => 'middle_switch')); ?>
					</td>
					
					<td>
						<a href="javascript:" class="middle_switch active"><?php echo __('stat.form2'); ?></a>
					</td>
					<td>
						<?php echo HTML::anchor('stats/events/' , __('stat.form3') , array('class' => 'middle_switch')); ?>
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
	<table class="data" width="100%" cellpadding="0" cellspacing="0">
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
