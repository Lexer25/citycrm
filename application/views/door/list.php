<?php

//echo Debug::vars('3', $doors);
?>
<script type="text/javascript">


 
  	$(function() {		
  		$("#tablesorter").tablesorter({ headers: { 0:{sorter: false}},  widgets: ['zebra']});
		
  	});	
	
</script>
<style>
.tree{
  --spacing : 1.5rem;
  --radius  : 10px;
}
.tree li{
  display      : block;
  position     : relative;
  padding-left : calc(2 * var(--spacing) - var(--radius) - 2px);
}

.tree ul{
  margin-left  : calc(var(--radius) - var(--spacing));
  padding-left : 0;
}

</style>
<?php if (isset($alert)) { ?>
<div class="alert_success">
	<p>
		<img class="mid_align" alt="success" src="images/icon_accept.png" />
		<?php echo $alert; ?>
	</p>
</div>
<?php } ?>
<div class="onecolumn">
	<div class="header">

		<span><?php echo __('doors.list'); ?></span>
	</div>

	<?php
		//echo Debug::vars('19', isset($org_tree)); exit;
		if(isset($org_tree)) echo '<br><div class="content">'. str_replace('companies/edit/', 'doors/edit/', $org_tree).'</div>';
	?>
	<br class="clear"/>
	<div class="content">
		<form id="form_data" name="form_data" action="" method="post">
		<table class="data tablesorter-blue" width="100%" cellpadding="0" cellspacing="0" id="tablesorter" >
				<thead>
					<tr>
						<!--
						<th style="width:10px">
							<input type="checkbox" id="check_all" name="check_all"/>
						</th>
						-->
						<?php

						echo '<th width="10%">' . __('npp') . '</th>';
						echo '<th width="10%">' . __('id_dev') . '</th>';
						
						echo '<th>' . __('door.name') . '</th>';
					
						echo '<th>' . __('device.count') . '</th>';
						?>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i=1;
					foreach ($doors as $device) 
					{ ?>
						<tr>
						
							<?php 
							$door=new Door($device);
							//echo Debug::vars('78', $device, $door);//exit;
							echo '<td align="center">' . $i . '</td>';
							echo '<td align="center">' . $door->id. '</td>';
							echo '<td align="center">' . HTML::anchor('doors/doorInfo/'.$door->id, iconv('windows-1251','UTF-8',$door->name)) . '</td>';
							echo '<td align="center">' . HTML::anchor('doors/doorcontactlist/'.$door->id, $door->contactCount) . '</td>';
							
							?>
							</td>
						</tr>
					<?php $i++;
					} ?>
				</tbody>
			</table>
			<div id="chart_wrapper" class="chart_wrapper"></div>
		<!-- End bar chart table-->
		</form>

	</div>
</div>
