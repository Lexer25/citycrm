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
			<form action="cards/search" method="post">
				<input type="text" class="search noshadow" title="<?php echo __('search'); ?>" name="q" id="q" value="<?php if (isset($filter)) echo $filter; ?>" />
			</form>
		</div>
		<span><?php echo __('cards.title'); ?></span>
	</div>
	<br class="clear"/>
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
						<th><?php echo __('cards.code'); ?></th>
						<th><?php echo __('cards.datestart'); ?></th>
						<th><?php echo __('cards.dateend'); ?></th>
						<th><?php echo __('cards.active'); ?></th>
						<th><?php echo __('cards.holder'); ?></th>
						<th><?php echo __('cards.company'); ?></th>
						<th><?php echo __('cards.action'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($cards as $card) { ?>
					<tr>
						<!--
						<td>
							<input type="checkbox" />
						</td>
						-->
						<td><?php 
							echo HTML::anchor('contacts/card/' . $card['ID_CARD'], $card['ID_CARD']); ?></td>
						<td><?php echo $card['TIMESTART']; ?></td> 
						<td><?php echo $card['TIMEEND']; ?></td>
						<td><?php echo $card['ACTIVE'] == '1' ? __('yes') : __('no'); ?> 
						<td><?php 
							if (Auth::instance()->logged_in('admin'))
								echo HTML::anchor('contacts/edit/' . $card['ID_PEP'], iconv('CP1251', 'UTF-8', $card['NAME'] . ' ' . $card['SURNAME'])); 
							else 
								echo HTML::anchor('contacts/view/' . $card['ID_PEP'], iconv('CP1251', 'UTF-8', $card['NAME'] . ' ' . $card['SURNAME']));
						?></td>
						<td><?php 
							if (Auth::instance()->logged_in('admin'))
								echo HTML::anchor('companies/edit/' . $card['ID_ORG'], iconv('CP1251', 'UTF-8', $card['CNAME'])); 
							else 
								echo HTML::anchor('companies/view/' . $card['ID_ORG'], iconv('CP1251', 'UTF-8', $card['CNAME']));
						?></td>
						<td>
							<a href="javascript:" onclick="if (confirm('<?php echo __('cards.confirmdelete'); ?>')) location.href='<?php echo URL::base() . 'cards/delete/' . $card['ID_CARD']; ?>';">
								<?php echo HTML::image('images/icon_delete.png', array('title' => __('tip.delete'), 'class' => 'help')); ?>
							</a>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<div id="chart_wrapper" class="chart_wrapper"></div>
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
