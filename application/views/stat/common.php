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
		<span><?php echo  __('stat.title') ; ?></span>
		<?php if (1) { ?>
		<div class="switch">
			<table cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td>
						<a href="javascript:" class="left_switch active"><?php echo __('stat.form1'); ?></a>
					</td>
					<td>
						<?php echo HTML::anchor('stats/queue_message/' ,  __('stat.title.que_but') ,array('class' => 'middle_switch')); ?>
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
	<table class="data" width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo __('stat.header1');?></th>
			<th><?php echo __('stat.header2');?></th>
			
		</tr>
		<tr>
			<td><?php echo __('stat.header5');?></td>
			<td><?php echo $list['people_count'];?></td>
			
		</tr>
		<tr>
			<td><?php echo __('stat.count_people_na');?></td>
			<td><?php echo $list['people_count_na'];?></td>
			
		</tr>
		<tr>
			<td><?php echo __('stat.header8');?></td>
			<td><?php echo $list['card_count'];?></td>
			
		</tr>
		<tr>
			<td><?php echo __('stat.header11');?></td>
			<td><?php echo $list['accessname_count'];?></td>
			
		</tr>
		<tr>
			<td><?php echo __('stat.header14');?></td>
			<td><?php echo $list['device_count'];?></td>
			
		</tr>
		<tr>
			<td><?php echo __('stat.header17');?></td>
			<td><?php echo $list['door_count'];?></td>
		</tr>
		</tr>
		<tr>
			<td><?php echo __('stat.event_card_count');?><sup>1</sup></td>
			<td><?php echo HTML::anchor('eventlog',$list['event_card_count']);?></td>
		</tr>
		<tr>
			<td><?php echo __('stat.event_err_count');?><sup>2</sup></td>
			<td><?php echo HTML::anchor('eventlog/alarm',$list['event_err_count']);?></td>
		</tr>
		<tr>
			<td><?php echo __('stat.event_in_future');?><sup>3</sup></td>
			<td><?php echo $list['event_in_future'];?></td>
		</tr>
		<tr>
			<td><?php echo __('stat.card_as_null');?><sup>4</sup></td>
			<td><?php echo $list['card_as_null'];?></td>
		</tr>
		

	</table>
	
</div>
	
</div>
<?
$event_list[]= __('event.desc.46');
$event_list[]= __('event.desc.47');
$event_list[]= __('event.desc.48');
$event_list[]= __('event.desc.50');
$event_list[]= __('event.desc.65');
?>
<sup>1</sup><?php echo __('stat.common.desc_1', array(':eventtype'=>implode(",",$event_list))); ?>
	<br>
<sup>2</sup><?php echo __('stat.common.desc_2'); ?>
	<br>
<sup>3</sup><?php echo __('stat.common.desc_3'); ?>
	<br>
<sup>4</sup><?php echo __('stat.common.desc_4'); ?>
</br>
<label for="name"><?echo __('stat.app_about');?></label>
<div class="content">
	<table class="data" width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo __('stat.app_name');?></th>
			<th><?php echo __('stat.app_version');?></th>
			<th><?php echo __('stat.app_size');?></th>
			
		</tr>
	</table>
	
</div>

