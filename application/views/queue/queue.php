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
			<form action="queue/search_queue" method="post">
				<input type="text" class="search noshadow" title="<?php echo __('search'); ?>" name="q" id="q" value="<?php if (isset($filter)) echo $filter; ?>" />
			</form>
		</div>
		<span><?php echo __('queue.title_list'); ?></span>
	</div>
	<br class="clear"/>
	<div class="content">
		<?php if (count($queue) > 0) { ?>
		<form id="form_data" name="form_data" action="queue/load_cards" method="post">
			<table class="data" width="100%" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						
						<th style="width:10px"><input type="checkbox" id="check_all" name="check_all" <? if(Auth::instance()->logged_in('user')) echo 'disabled';?>/></th>
						<th><?php echo __('queue.door_id'); ?></th>
						<th><?php echo __('queue.door_name'); ?></th>
						<th><?php echo __('queue.id_card'); ?></th>
						<th><?php echo __('queue.attempt'); ?></th>
						<th><?php echo __('queue.door_isactive'); ?></th>
						<th><?php echo __('queue.controller_name'); ?></th>
						<th><?php echo __('queue.controller_isactive'); ?></th>
						<th><?php echo __('queue.load_result'); ?></th>
						<th><?php echo __('queue.action_name'); ?></th>
						</tr>
				</thead>
				<tbody>
					<?php foreach ($queue as $card) { ?>
					<tr>
						<td><input type="checkbox" <? if(Auth::instance()->logged_in('user')) echo 'disabled';?> id="check_all" name="select_cards[<?php echo iconv('CP1251', 'UTF-8', $card['ID_CARD']);?>][<?php echo iconv('CP1251', 'UTF-8', $card['ID_DEV']);?>]" value="<?PHP echo iconv('CP1251', 'UTF-8', $card['ID_DEV']);?>"/></td>
						<td><?php echo $card['ID_DEV']; ?></td>
						<td><?php echo iconv('CP1251', 'UTF-8', $card['DOOR_NAME']); ?></td>
						<td>
							<?php if(Auth::instance()->logged_in('admin') || Auth::instance()->logged_in('owner')) {
									echo HTML::anchor ('contacts/card/'.iconv('CP1251', 'UTF-8', $card['ID_CARD']), iconv('CP1251', 'UTF-8', $card['ID_CARD']));
								} else {
								echo iconv('CP1251', 'UTF-8', $card['ID_CARD']);
								};?>
						</td>
						<td><?php echo iconv('CP1251', 'UTF-8', $card['ATTEMPTS']); ?></td>
						<td><?php echo iconv('CP1251', 'UTF-8', $card['DOOR_ISACTIVE']) == '1' ? __('yes') : __('no'); ?></td>
						<td><?php echo iconv('CP1251', 'UTF-8', $card['CONTROLLER_NAME']); ?></td>
						<td><?php echo iconv('CP1251', 'UTF-8', $card['CONTROLLER_ISACTIVE']) == '1' ? __('yes') : __('no'); ?></td>
						<td><?php echo iconv('CP1251', 'UTF-8', $card['LOAD_TIME']).' '.iconv('CP1251', 'UTF-8', $card['LOAD_RESULT']); ?></td> 
						<td><?php switch (iconv('CP1251', 'UTF-8', $card['OPERATION'])){
							case "1":
								echo __('queue.action_1');
							break;
							case "2": echo __('queue.action_2');
							break;
							}
							?> 
						</tr>
					<?php } ?>
				</tbody>
			</table>
			<div id="chart_wrapper" class="chart_wrapper"></div>
			<? if(Auth::instance()->logged_in('admin') || Auth::instance()->logged_in('owner')) {?>
			<input type="submit"  name="start_load_cards" value="<?php echo __('queue.start_load_card_in_controller');?>"/>
			<input type="submit"  name="stop_load_cards" value="<?php echo __('queue.stop_load_card_in_controller');?>"/>
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
