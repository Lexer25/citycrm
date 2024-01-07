<?php
//Форма отражения события в истории Гости

//echo Debug::vars('4', $contact);
//echo Debug::vars('5', $data);
//echo Debug::vars('6', $id);

?>
<div class="onecolumn">
	<div class="header">
		<span><?php echo __('contact.history') . ' - ' . iconv('CP1251', 'UTF-8', $contact['NAME'] . ' ' . $contact['SURNAME']); ?></span>
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
						<?php echo HTML::anchor('guests/cardlist/' . $contact['ID_PEP'], __('contact.cardlist'), array('class' => 'middle_switch')); ?>
					</td>
					<td>
						<a href="javascript:" class="right_switch active"><?php echo __('contact.history'); ?></a>
					</td>
				</tr>
			</tbody>
			</table>
		</div>
	</div>
	<br class="clear" />
	<div class="content">
		<?php
		
		if (count($data) > 0) { ?>
		<table class="data" width="100%" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th style="width:20%"><?php echo __('history.id_event'); ?></th>
					<th style="width:20%"><?php echo __('history.date'); ?></th>
					<th style="width:30%"><?php echo __('history.event'); ?></th>
					<th style="width:30%"><?php echo __('history.eventadd'); ?></th>
					<th style="width:50%"><?php echo __('history.any'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($data as $h) { 
				$event=new Eventguest(Arr::get($h, 'ID_EVENT'));
				?>
				
				<tr>
					<td><?php echo $event->id_event; ?></td>
					<td><?php echo date ('H:i:s d.m.Y ', strtotime($event->eventtime)); ?></td>
					<td><?php echo iconv('CP1251', 'UTF-8', $event->eventname).' ('.$event->eventtype.')'; ?></td>
					<td><?php echo __($event->eventnameadd); ?></td>
					<td><?php echo iconv('CP1251', 'UTF-8', $event->evendesc); ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php } else { ?>
		<div style="margin: 100px 0; text-align: center;">
			<?php echo __('history.empty'); ?><br /><br />
		</div>
		<?php } ?>
		
		
	</div>
</div>
