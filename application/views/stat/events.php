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
		<span><?php echo  __('stat.events.title') ; ?></span>
		
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
						<?php echo HTML::anchor('stats/device/' , __('stat.form2') , array('class' => 'middle_switch')); ?>

					</td>
					<td>
						<a href="javascript:" class="middle_switch active"><?php echo __('stat.form3'); ?></a>						
					</td>
					<td>
						<?php echo HTML::anchor('stats/save/' , __('stat.form4') ,array('class' => 'right_switch')); ?>
					</td>
				</tr>
			</tbody>
			</table>
		</div>
		
	</div>
	<br class="clear" />
	<div class="content">
	<table class="data" width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo __('stat.events.header1');?></th>
			<th><?php echo __('stat.events.header2');?></th>
			<th><?php echo __('stat.events.header3');?></th>
			<th><?php echo __('stat.events.header4');?></th>
			<th><?php echo __('stat.events.header5');?></th>
		</tr>
		<?php 
			foreach ($list as $value) { 
				
			?>
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
</div>
