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
		<div id="search"<?php if (isset($hidesearch)) echo ' style="display: none;"'; ?>>
			<form action="queue/search" method="post">
				<input type="text" class="search noshadow" title="<?php echo __('search'); ?>" name="q" id="q" value="<?php if (isset($filter)) echo $filter; ?>" />
			</form>
		</div>
		<span><?php echo __('queue.title'); ?></span>
	</div>
	<br class="clear"/>
	<div class="content">
		<?php if (count($queue) > 0) { ?>
		<form id="form_data" name="form_data" action="queue/load_device" method="post">
			<table class="data" width="100%" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th style="width:10px"><input type="checkbox" <? if(Auth::instance()->logged_in('user')) echo 'disabled';?> id="check_all" name="check_all"/></th>
						<th><?php echo __('queue.controller_id'); ?></th>
						<th><?php echo __('queue.controller_name'); ?></th>
						<th><?php echo __('queue.door_count'); ?></th>
						<th><?php echo __('queue.door_isactive'); ?></th>
						<th><?php echo __('queue.controller_name'); ?></th>
						<th><?php echo __('queue.controller_isactive'); ?></th>
						<th><?php echo __('queue.desc_err'); ?></th>
						<th><?php echo __('queue.desc'); ?></th>
						
						</tr>
				</thead>
				<tbody>
					<?php if (count($queue)>0) {
						foreach ($queue as $card) { ?>
						<tr>
							<td><input type="checkbox" <? if(Auth::instance()->logged_in('user')) echo 'disabled';?> name="select_id_device[]" value="<?php echo iconv('CP1251', 'UTF-8', $card['ID_DEV']);?>"/></td>
							<td><?php echo $card['DOOR_ID']; ?></td>
							<td><span title="">
								<?php if(Auth::instance()->logged_in('admin') || Auth::instance()->logged_in('owner')) 
								{ echo HTML::anchor('queue/search_id_dev/'.iconv('CP1251', 'UTF-8', $card['ID_DEV']), iconv('CP1251', 'UTF-8', $card['DOOR_NAME']));
								} else {
								echo iconv('CP1251', 'UTF-8', $card['DOOR_NAME']);}
								?></span></td> 
							<td><?php echo iconv('CP1251', 'UTF-8', $card['DOOR_COUNT']); ?></td>
							<td><?php echo iconv('CP1251', 'UTF-8', $card['DOOR_ISACTIVE']) == '1' ? __('yes') : __('no'); ?></td>
							<td><?php echo iconv('CP1251', 'UTF-8', $card['CONTROLLER_NAME']); ?></td> 
							<td><?php echo iconv('CP1251', 'UTF-8', $card['CONTROLLER_ISACTIVE']) == '1' ? __('yes') : __('no'); ?></td>
							<td><?php echo (!empty($card['err_desc']))? Text::limit_chars(iconv('CP1251', 'UTF-8', implode ('<br>', $card['err_desc'])) , 400): ''; ?></td>
							<td><?php echo iconv('CP1251', 'UTF-8', $card['DOOR_ISACTIVE']) == '1' ? __('yes') : __('no'); ?> </td>
							
							
						</tr>
						<?php };
					} else {
					echo 'no data';}
					?>
				</tbody>
			</table>
			<div id="chart_wrapper" class="chart_wrapper"></div>
			<? if(Auth::instance()->logged_in('admin') || Auth::instance()->logged_in('owner')) {?>
				<input type="submit" name="start_load" value="<?php echo __('queue.start_load_controller');?>"/>
				<input type="submit" name="stop_load" value="<?php echo __('queue.stop_load_controller');?>"/>
			<?};?>
		<!-- End bar chart table-->
		</form>
		<?php echo $pagination; ?>
		<?php } else { ?>
		<div style="margin: 100px 0; text-align: center;">
			<?php echo __('cards.empty'); ?><br /><br />
		</div>
		<?php } ?>
	</div>
</div>
