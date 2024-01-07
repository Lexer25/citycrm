<div class="onecolumn">
	<div class="header">
		<span><?php echo  __('stat.title') ; ?></span>
		
	</div>
	<br class="clear" />
	<div class="content">
	<table border="1">
		<tr>
			<th><?php echo __('stat.header1');?></th>
			<th><?php echo __('stat.header2');?></th>
		</tr>
		<tr>
			<td><?php echo __('stat.header5');?></td>
			<td><?php echo $list['people_count'];?></td>
			
		</tr>
		<tr>
			<td><?php echo __('stat.header8');?></td>
			<td><?php echo $list['card_count'];?></td>
			
		</tr><tr>
			<td><?php echo __('stat.header11');?></td>
			<td><?php echo $list['accessname_count'];?></td>
			
		</tr><tr>
			<td><?php echo __('stat.header14');?></td>
			<td><?php echo $list['device_count'];?></td>
			
		</tr><tr>
			<td><?php echo __('stat.event_card_count');?></td>
			<td><?php echo $list['event_card_count'];?></td>
		</tr>
		<tr>
			<td><?php echo __('stat.event_err_count');?></td>
			<td><?php echo $list['event_err_count'];?></td>
		</tr>

	</table>
		
	</div>
</div>
