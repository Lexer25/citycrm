<script type="text/javascript">


 
  	$(function() {		
  		$("#tablesorter").tablesorter({ headers: { 7:{sorter: false}},  widgets: ['zebra']});
		
  	});	
	
</script>
<?php 
//echo Debug::vars('173', Session::instance());
//echo Debug::vars('3', Session::instance()->get('mode'));
//exit;

include Kohana::find_file('views','alert');
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
			<form action="guests/search" method="post">
				<input type="text" class="search noshadow" title="<?php echo __('search'); ?>" name="q" id="q" value="<?php if (isset($filter)) echo $filter; ?>" />
			</form>
		</div>
		
		
		<?php 
		switch(Session::instance()->get('mode')){
					
					case 'guest_mode'://просмотр гостя с картой
					
					
					echo '<span>'.__('guests.title'). ' '. __('guest.countGuest', array(':count'=> $listCount)).'</span>';
				
					
					break;
					case 'archive_mode'://просмотр архива
					
					
					echo '<span>'.__('guests.titleinArchive'). ' '. __('guest.countArchive', array(':count'=> $listCount)).'</span>';
					
					break;
					
					echo '<span>'.__('guests.unknow').'</span>';
					
					default:
					
					
				}
				
		?>

	</div>
	<br class="clear"/>
	<div class="content">
		<?php if (count($people) > 0) { ?>
		<form id="form_data" name="form_data" action="" method="post">
			<table class="data tablesorter-blue" width="100%" cellpadding="0" cellspacing="0" id="tablesorter" >
				<thead>
					<tr>
						<!--
						<th style="width:10px">
							<input type="checkbox" id="check_all" name="check_all"/>
						</th>
						-->
						<th><?php echo __('contacts.code'); ?></th>
						
						<th><?php echo __('contacts.name'); ?></th>
						<th><?php echo __('contacts.company'); ?></th>
						<th><?php echo __('key.rfid'); ?></th>
						<th><?php echo __('key.grz'); ?></th>
						<th><?php echo __('guest.dateregistration'); ?></th>
						<!--<th><?php echo __('card.datestart'); ?></th>
						<th><?php echo __('card.dateend'); ?></th>-->
						<th><?php echo __('contacts.action'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($people as $pep) { 
					$guest=new Guest(Arr::get($pep,'ID_PEP'));
						?>
					
					<tr>
						<!--
						<td>
							<input type="checkbox" />
						</td>
						-->
						<td><?php echo iconv('CP1251', 'UTF-8', $guest->tabnum); ?></td>
						
						<td><?php 
								echo HTML::anchor('guests/edit/' . $guest->id_pep.'/'.Session::instance()->get('mode'), iconv('CP1251', 'UTF-8', $guest->name . ' ' . $guest->surname));
						?></td>
						<td><?php 
								$org= new Company($guest->id_org);
								echo iconv('CP1251', 'UTF-8', $org->name);
						?></td>
						<td>
							<?php 
							//получаю список едентификаторв для rfid
							 $cardList=$guest->getTypeCardList(1);
							 foreach ($cardList as $key1=>$value)
							 {
								 
								 $card=new Keyk(Arr::get($value, 'ID_CARD'));
								
								 echo $card->id_card;
								echo '<br>';
							 }
							
							?>
						</td>
							<td>
							<?php 
							//получаю список едентификаторв для rfid
							 $cardList=$guest->getTypeCardList(4);
							 foreach ($cardList as $key1=>$value)
							 {
								 
								 $card=new Keyk(Arr::get($value, 'ID_CARD'));
								
								 echo $card->id_card;
								echo '<br>';
							 }
							
							?>
						</td>

						<td>
							<?php echo date('d.m.Y H:i', strtotime($guest->time_stamp));?>
							
						</td>
						<!---
						<td>
							
							<?php //echo date('H:i d.m.Y', strtotime($card->timestart));?>
							
						</td>
						<td>
							
							<?php //echo date('H:i d.m.Y', strtotime($card->timeend));?>
						</td>
						-->
						<td>
							<?php
							
							echo HTML::anchor('guests/delete/'.$guest->id_pep, 
							HTML::image('images/icon_delete.png', array('alt'=>'edit', 'class'=>'help', 'title'=>__('tip.delete'))),
							array('onclick'=>'return confirm(\''.__('guest.confirmdelete').'\')')
								);
							?>
							
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
			<?php echo __('contacts.empty'); ?><br /><br />
		</div>
		<?php } ?>
	</div>
</div>
