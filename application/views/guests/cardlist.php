<?php
//echo Debug::vars('2', $cards);
$catdTypelist = Model::factory('Card')->getcatdTypelist();//получил список типов идентификаторов
?>
<div class="onecolumn">
	<div class="header">
		<span><?php echo __('contact.cardlist') . ' - ' . iconv('CP1251', 'UTF-8', $contact['NAME'] . ' ' . $contact['SURNAME']); ?></span>
		<div class="switch">
			<table cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td>
						<?php
							if ($contact['CANEDIT'] == 0) 
								echo HTML::anchor('guests/view/' . $id, __('contact.common'), array('class' => 'left_switch'));
							else
								echo HTML::anchor('guests/edit/' . $id, __('contact.common'), array('class' => 'left_switch')); 
						?>
					</td>
					<td>
						<?php echo HTML::anchor('guests/acl/' . $id, __('contact.acl'), array('class' => 'middle_switch')); ?>
					</td>
					<td>
						
						<a href="javascript:" class="middle_switch active"><?php echo __('contact.cardlist'); ?></a>
					</td>
					<td>
						<?php echo HTML::anchor('guests/history/' . $contact['ID_PEP'], __('contact.history'), array('class' => 'right_switch')); ?>
					</td>
				</tr>
			</tbody>
			</table>
		</div>
	</div>
	<br class="clear" />
	<div class="content">
		<?php if (count($cards) > 0) { ?>
		<form id="form_data" name="form_data" action="" method="post">
			<table class="data" width="100%" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<!--
						<th style="width:10px">
							<input type="checkbox" id="check_all" name="check_all"/>
						</th>
						-->
						<th style="width:20%"><?php echo __('cards.code'); ?></th>
						<th style="width:30%"><?php echo __('cards.datestart'); ?></th>
						<th style="width:30%"><?php echo __('cards.datestart'); ?></th>
						<th style="width:30%"><?php echo __('cards.dateend'); ?></th>
						<th style="width:20%"><?php echo __('cards.active'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($cards as $card) { 
					$cardtype=Arr::get($catdTypelist, $card['ID_CARDTYPE']);?>
					<tr>
						<!--
						<td>
							<input type="checkbox" />
						</td>
						-->
						<td><?php echo Arr::get($card, 'ID_CARD'); ?></td>
						<td><?php echo iconv('CP1251', 'UTF-8', Arr::get($cardtype, 'smallname')); ?></td> 
						<td><?php echo $card['TIMESTART']; ?></td>
						<td><?php echo $card['TIMEEND']; ?></td>
						<td><?php echo $card['ACTIVE'] == 1 ? __('yes') : __('no'); ?></td>

					</tr>
					<?php } ?>
				</tbody>
			</table>
			<div id="chart_wrapper" class="chart_wrapper"></div>
		<!-- End bar chart table-->
		</form>
		<?php } else { ?>
			<div style="margin: 100px 0; text-align: center;">
				<?php echo __('cards.none'); ?><br /><br />
			</div>
		<?php } ?>
		<br />
		<?php if (!$contact) {?>
		<input type="button" value="<?php echo __('cards.create'); ?>" onclick="location.href='<?php echo URL::base() . 'guests/addcard/' . $contact['ID_PEP']; ?>'" />
		<input type="button" value="<?php echo __('cards.create_grz'); ?>" onclick="location.href='<?php echo URL::base() . 'guests/addgrz/' . $contact['ID_PEP']; ?>'" />
		<?php } ?>
		
	</div>
</div>
