<?php
//echo Debug::vars('2', $cards);
//echo Debug::vars('3', Session::instance());
$catdTypelist = Model::factory('Card')->getcatdTypelist();//получил список типов идентификаторов
include Kohana::find_file('views','alert');
?>
<div class="onecolumn">
	<div class="header">
		<span>
		<?php 
			
			switch($mode) {
				case('new'):
					;
				break;
				
				case('edit'):
					echo __('contact.titleCardList', array( 
					':name'=> iconv('CP1251', 'UTF-8', $contact->name),
					':surname'=> iconv('CP1251', 'UTF-8', $contact->surname),
					':patronymic'=> iconv('CP1251', 'UTF-8', $contact->patronymic)));
				break;
				
				case('fired'):
					echo __('contact.titlefiredContact', array( 
					':name'=> iconv('CP1251', 'UTF-8', $contact->name),
					':surname'=> iconv('CP1251', 'UTF-8', $contact->surname),
					':patronymic'=> iconv('CP1251', 'UTF-8', $contact->patronymic)));
				break;
				default:
					echo __('form.editContact');
				break;
			}
				
				
				?>
		</span>
		<div class="switch">
			<table cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td>
						<?php
							if (true) 
								echo HTML::anchor('contacts/view/' . $id, __('contact.common'), array('class' => 'left_switch'));
							else
								echo HTML::anchor('contacts/edit/' . $id, __('contact.common'), array('class' => 'left_switch')); 
						?>
					</td>
					<td>
						<?php echo HTML::anchor('contacts/acl/' . $id, __('contact.acl'), array('class' => 'middle_switch')); ?>
					</td>
					<td>
						
						<a href="javascript:" class="middle_switch active"><?php echo __('contact.cardlist'); ?></a>
					</td>
					<td>
						<?php echo HTML::anchor('contacts/history/' . $contact->id_pep, __('contact.history'), array('class' => 'right_switch')); ?>
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
						<th><?php echo __('cards.code'); ?></th>
						<th><?php echo __('cards.datestart'); ?></th>
						<th><?php echo __('cards.datestart'); ?></th>
						<th><?php echo __('cards.dateend'); ?></th>
						<th><?php echo __('cards.active'); ?></th>
						<th><?php echo __('cards.action'); ?></th>
						
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
						<td><?php echo HTML::anchor('cards/edit/' . $card['ID_CARD'], $card['ID_CARD']); ?></td>
						<td><?php echo iconv('CP1251', 'UTF-8', Arr::get($cardtype, 'smallname')); ?></td> 
						<td><?php echo $card['TIMESTART']; ?></td>
						<td><?php echo $card['TIMEEND']; ?></td>
						<td><?php echo $card['ACTIVE'] == 1 ? __('yes') : __('no'); ?></td>
						<td>
							<a href="javascript:" onclick="if (confirm('<?php echo __('cards.confirmdelete'); ?>')) location.href='<?php echo URL::base() . 'contacts/deletecard/' . $card['ID_CARD']; ?>';">
								<?php echo HTML::image('images/icon_delete.png', array('title' => __('tip.fired'), 'class' => 'help')); ?>
							</a>
						</td>
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
			<?php
			if($contact->is_active) {
			?>
		<input type="button" value="<?php echo __('cards.create'); ?>" onclick="location.href='<?php echo URL::base() . 'contacts/addrfid/' . $contact->id_pep; ?>'" />
		<input type="button" value="<?php echo __('cards.create_grz'); ?>" onclick="location.href='<?php echo URL::base() . 'contacts/addgrz/' . $contact->id_pep; ?>'" />
		<?php }?>
	</div>
</div>
