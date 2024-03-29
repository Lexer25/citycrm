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
								echo HTML::anchor('contacts/view/' . $id, __('contact.common'), array('class' => 'left_switch'));
							else
								echo HTML::anchor('contacts/edit/' . $id, __('contact.common'), array('class' => 'left_switch')); 
						?>
					</td>
					<td>
						<?php echo HTML::anchor('contacts/acl/' . $id, __('contact.acl'), array('class' => 'middle_switch')); ?>
					</td>
					<td>
						<?php echo HTML::anchor('contacts/cardlist/' . $contact['ID_PEP'], __('contact.cardlist'), array('class' => 'middle_switch')); ?>
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
		//echo Debug::vars('33', $data);
		
		if (count($data) > 0) { ?>
		<table class="data" width="100%" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th style="width:20%"><?php echo __('history.id_event'); ?></th>
					<th style="width:20%"><?php echo __('history.date'); ?></th>
					<th style="width:30%"><?php echo __('history.device'); ?></th>
					<th style="width:50%"><?php echo __('history.event'); ?></th>
					<th style="width:50%"><?php echo __('history.any'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($data as $h) { ?>
				<tr>
					<td><?php echo $h['ID_EVENT']; ?></td>
					<td><?php echo $h['DATETIME']; ?></td>
					<td><?php echo iconv('CP1251', 'UTF-8', $h['DEVICENAME']); ?></td>
					<td><?php echo iconv('CP1251', 'UTF-8', $h['EVENTNAME']); ?></td>
					<td><?php echo iconv('CP1251', 'UTF-8', $h['ID_CARD']); ?></td>
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
