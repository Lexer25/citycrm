<script type="text/javascript">


 
  	$(function() {		
  		$("#tablesorter").tablesorter({ headers: { 7:{sorter: false}},  widgets: ['zebra']});
		
  	});	
	
</script>
<?php 
//https://webformyself.com/sortirovka-tablic-pri-pomoshhi-plagina-tablesorter-js/?ysclid=lrgdz4nrzp693511651
// список идентификаторов
//echo Debug::vars('2', $cards); exit;
//echo Debug::vars('2', $catdTypelist); //exit;
//echo Debug::vars('3', $alert); //exit;
//echo Debug::vars('4', $filter); //exit;
//echo Debug::vars('5', $pagination); //exit;

if ($alert) { ?>
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
		<?php 
		//объявление типов идентификаторов
		$cardtype=array(
			0=>'',);
		
		if (count($cards) > 0) { ?>
		<form id="form_data" name="form_data" action="" method="post">
			<table class="data tablesorter-blue" width="100%" cellpadding="0" cellspacing="0" id="tablesorter" >
			<thead  allign="center">
					<tr>
						<th><?php echo __('cards.code'); ?></th>
						<th><?php echo __('cards.id_cardtype'); ?></th>
						<th><?php echo __('cards.datestart'); ?></th>
						<th><?php echo __('cards.dateend'); ?></th>
						<th><?php echo __('cards.active'); ?></th>
						<th><?php echo __('cards.holder'); ?></th>
						<th><?php echo __('cards.company'); ?></th>
						<th><?php echo __('cards.action'); ?></th>
					</tr>
				</thead>	
					<tr align="center">
					<?php
						echo '<td>1</td>';
						echo '<td>2</td>';
						echo '<td>3</td>';
						echo '<td>4</td>';
						echo '<td>5</td>';
						echo '<td>6</td>';
						echo '<td>7</td>';
						echo '<td>8</td>';
					
					?>
						
					</tr>
			
				<tbody>
					<?php foreach ($cards as $card) { 
						$cardtype=Arr::get($catdTypelist, $card['ID_CARDTYPE']);
						
					?>
					<tr>
						
						<td><?php 
							echo HTML::anchor('cards/edit/' . $card['ID_CARD'], $card['ID_CARD']);
							//echo Debug::vars('83', $cardtype);
							if(Arr::get($cardtype, 'id') == 1) echo ' ('.Model::factory('Stat')->reviewKeyCode(Arr::get($card, 'ID_CARD', __('No_card'))).')'; ?></td>
						<td><?php echo iconv('CP1251', 'UTF-8', Arr::get($cardtype, 'smallname')); ?></td> 
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
								<?php echo HTML::image('images/icon_delete.png', array('title' => __('cards.delete'), 'class' => 'help')); ?>
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

