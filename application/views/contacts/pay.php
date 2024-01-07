<div class="onecolumn">
	<div class="header">
		<span><?php echo __('contact.pay') . ' - ' . iconv('CP1251', 'UTF-8', $contact['NAME'] . ' ' . $contact['SURNAME']); ?></span>
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
						<?php echo HTML::anchor('contacts/cardlist/' . $contact['ID_PEP'], __('contact.cardlist'), array('class' => 'middle_switch')); ?>
					</td>
					<td>
						<a href="javascript:" class="middle_switch active"><?php echo __('contact.pay'); ?></a>
					</td>
					<td>
						<?php echo HTML::anchor('contacts/history/' . $id, __('contact.history'), array('class' => 'right_switch')); ?>
					</td>
				</tr>
			</tbody>
			</table>
		</div>
	</div>
	<br class="clear" />
	<div class="content">
		<?php if (count($data) > 0) { ?>
		<table class="data" width="100%" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th style="width:15%"><?php echo __('payment.date'); ?></th>
					<th style="width:10%"><?php echo __('payment.card'); ?></th>
					<th style=""><?php echo __('payment.place'); ?></th>
					<th style="width:45%"><?php echo __('payment.service'); ?></th>
					<th style="width:10%"><?php echo __('payment.sum'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($data as $p) { ?>
				<tr>
					<td><?php echo $p['pdate']; ?></td>
					<td><?php echo $p['cardnum']; ?></td>
					<td><?php echo $p['pplace']; ?></td>
					<td><?php echo $p['serv_name'] . ' - ' . $p['servtype_desc']; ?></td>
					<td><?php echo $p['psum']; ?></td>
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
