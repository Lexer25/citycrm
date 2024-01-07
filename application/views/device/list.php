<?php

//echo Debug::vars('3', $devices);
?>

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
		<div id="search">
			<form action="devices/search" method="post">
				<input type="text" class="search noshadow" title="<?php echo __('search'); ?>" name="q" id="q" value="<?php if (isset($filter)) echo $filter; ?>" />
			</form>
		</div>
		<span><?php echo __('companies.title'); ?></span>
	</div>

	<?php
		//echo Debug::vars('19', $org_tree);
		echo '<br><div class="content">'.$org_tree.'</div>';
	?>
	<br class="clear"/>
	<div class="content">
		<form id="form_data" name="form_data" action="" method="post">
			<table class="data" width="100%" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<!--
						<th style="width:10px">
							<input type="checkbox" id="check_all" name="check_all"/>
						</th>
						-->
						<?php

						echo '<th>' . __('npp') . '</th>';
						echo '<th>' . __('device.id_dev') . '</th>';
						echo '<th>' . __('device.id_server') . '</th>';
						echo '<th>' . __('device.id_devtype') . '</th>';
						echo '<th>' . __('device.nataddr') . '</th>';
						echo '<th>' . __('device.name') . '</th>';
						echo '<th>' . __('device.action') . '</th>';
						echo '<th>' . __('device.action') . '</th>';
						?>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i=1;
					foreach ($devices as $device) 
					{ ?>
						<tr>
						
							<?php 
							echo '<td align="center">' . $i . '</td>';
							echo '<td align="center">' . Arr::get($device, 'ID_DEV') . '</td>';
							echo '<td align="center">' . Arr::get($device, 'ID_SERVER') . '</td>';
							echo '<td align="center">' . Arr::get($device, 'ID_DEVTYPE') . '</td>';
							echo '<td align="center">' . Arr::get($device, 'NETADDR') . '</td>';
							echo '<td align="center">' . HTML::anchor('devices/edit/'.Arr::get($device, 'ID_DEV'), iconv('windows-1251','UTF-8',Arr::get($device, 'NAME'))) . '</td>';
							echo '<td align="center">' . Arr::get($device, 'VERSION') . '</td>';
							echo '<td>' . HTML::anchor('devices/view/' . Arr::get($device, 'ID_ORG'), HTML::image('images/icon_edit.png', array('title' => __('tip.view'), 'class' => 'help')));
							if (Auth::instance()->logged_in('admin') || Arr::get($device, 'SUMODELETE') > 0) 
							{ ?>
								<a href="javascript:" onclick1="if (confirm('<?php echo __('devices.confirmdelete'); ?>')) location.href='<?php //echo URL::base() . 'devices/delete/' . $device['ID_ORG']; ?>';">
									<?php echo HTML::image('images/icon_delete.png', array('title' => __('tip.delete'), 'class' => 'help')); ?>
								</a>
							<?php } ?>
							</td>
						</tr>
					<?php $i++;
					} ?>
				</tbody>
			</table>
			<div id="chart_wrapper" class="chart_wrapper"></div>
		<!-- End bar chart table-->
		</form>
		
		<?php 
		echo $pagination; ?>
	</div>
</div>
