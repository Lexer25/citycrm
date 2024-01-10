<script type="text/javascript" language="javascript">
	function toggleBlock(idCard)
	{
		$.get("<?php echo URL::base(); ?>ajax/togglecard/" + idCard, function(data) {
				var a = $('#cardisactive');
				if (data == '00') {
					a.removeAttr('checked');
					$('#blockButton').val('<?php echo __('cards.unblock'); ?>');
				} else {
					a.attr('checked', 'checked');
					$('#blockButton').val('<?php echo __('cards.block'); ?>');
				}
		});
	}
	
	function validatecard()
	{
		$('.error').hide();
		if ($('#idcard').val() == '') {
			$('#error11').show();
			$('#idcard').focus();
			return false;
		}

		if (!$('#idcard').val().match(/^[0-9a-f]{8}$/i)) {
			$('#error12').show();
			$('#idcard').focus();
			return false;
		}
		
		if ($('#carddatestart').val() == '') {
			$('#error2').show();
			return false;
		}
		
		if ($('#carddateend').val() != '') {
			var a1 = $('#carddatestart').val().split('-'),
				a2 = $('#carddateend').val().split('-'),
				d1 = (new Date()).setFullYear(a1[0], a1[1] - 1, a1[2]),
				d2 = (new Date()).setFullYear(a2[0], a2[1] - 1, a2[2]);
			
			if (d1 > d2) {
				$('#error3').show();
				return false;
			}
		}
	}
	
	function deletecard(id) {
		if (confirm('<?php echo __('cards.confirmdelete'); ?>')) {
			location.href = '<?php echo URL::base(); ?>guests/deletecard/' + id;
		}
	}
	function reload(id) {
		if (confirm('<?php echo __('cards.reload'); ?>')) {
			location.href = '<?php echo URL::base(); ?>guests/reload/' + id;
		}
	}
	
</script>
<?php if (isset($alert)) { ?>
<div class="alert_success">
	<p>
		<img class="mid_align" alt="success" src="images/icon_accept.png" />
		<?php echo $alert; ?>
	</p>
</div>
<?php } ?>
<div class="onecolumn">
	<div class="header">
		<span><?php echo __('contact.card') . ' - ' . iconv('CP1251', 'UTF-8', $contact['NAME'] . ' ' . $contact['SURNAME']); ?></span>
		<div class="switch">
			<table cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td>
						<?php
							if ($contact['CANEDIT'] == 0) 
								echo HTML::anchor('guests/view/' . $contact['ID_PEP'], __('contact.common'), array('class' => 'left_switch'));
							else
								echo HTML::anchor('guests/edit/' . $contact['ID_PEP'], __('contact.common'), array('class' => 'left_switch')); 
						?>
					</td>
					<td>
						<a href="javascript:" class="middle_switch active"><?php echo __('contact.card'); ?></a>
					</td>
					<td>
						<?php echo HTML::anchor('guests/cardlist/' . $contact['ID_PEP'], __('contact.cardlist'), array('class' => 'middle_switch')); ?>
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
		<div id="cardinfo">
		<form action="guests/savecard" method="post" onsubmit="return validatecard()">
			<input type="hidden" name="hidden" value="form_sent" />
			<input type="hidden" name="id" value="<?php echo $contact['ID_PEP']; ?>" />
			<input type="hidden" name="id_cardtype" value="1" />
			<?php if (isset($card)) { ?>
			<input type="hidden" name="id0" value="<?php echo Arr::get($card, 'ID_CARD'); ?>" />
			<?php } ?>
			<table >
				<tr valign="top">
					<td align="left" width="10%">
						<fieldset>
							<legend><?php echo __('cards.details'); ?></legend>
							<table cellspacing="5" cellpadding="5">
								<tbody>
									<tr>
										<th align="right" style="padding-right: 10px;">
											<label for="idcard"><?php echo __('contact.cardid'); ?></label>
										</th>
										<td>
											<div style="padding-bottom: 10px;">
											<?php 
											if (isset($card))
											{
												if (isset($card)) echo Arr::get($card, 'ID_CARD'); 
												
											?>
											<input type="hidden" size="12" maxlength="8" id="idcard" name="idcard" value="<?php if (isset($card)) echo Arr::get($card, 'ID_CARD'); ?>" />	
												
											<?php } else {
											?>
												<input type="text" size="12" maxlength="8" id="idcard" name="idcard" value="<?php if (isset($card)) echo Arr::get($card, 'ID_CARD'); ?>" />
												<br />
											<?php } ?>
												<span class="error" id="error11" style="color: red; display: none;"><?php echo __('card.emptyid'); ?></span>
												<span class="error" id="error12" style="color: red; display: none;"><?php echo __('card.wrongcharacter'); ?></span>
												<?php if (isset($newcard)) { ?>
												&nbsp;&nbsp;
												<input type="button" value="<?php echo __('contact.cardstore'); ?>" />
												<?php } ?>
											</div>
										</td>
									</tr>
									<tr>
										<th align="right" style="padding-right: 10px;">
											<label for="carddatestart"><?php echo __('cards.datestart'); ?></label> 
										</th>
										<td>
											<div style="padding-bottom: 10px;">
												<input type="text" size="12" name="carddatestart" id="carddatestart" value="<?php 
													if (isset($card)) 
													{
														echo substr($card['TIMESTART'], 0, 10);
													} else {
														echo date("d.m.Y");
													}														?>" />
												<br />
												<span class="error" id="error2" style="color: red; display: none;"><?php echo __('card.emptystarttime'); ?></span>
											</div>
										</td>
									</tr>
									<tr>
										<th align="right" style="padding-right: 10px;">
											<label for="carddateend"><?php echo __('cards.dateend'); ?></label> 
										</th>
										<td>
											<div style="padding-bottom: 10px;">
												<input type="text" size="12" name="carddateend" id="carddateend" value="<?php 
													if (isset($card))
													{
														echo substr($card['TIMEEND'], 0, 10);
													} else {

														echo date('d.m.Y', strtotime('+1 year'));
													}														?>" />
												<br />
												<span class="error" id="error3" style="color: red; display: none;"><?php echo __('card.wrongendtime'); ?></span>
											</div>
										</td>
									</tr>
									<!--<tr>
										<th colspan="2">
											<input type="checkbox" id="useenddate" name="useenddate" <?php if (isset($card)) if ($card['FLAG'] != 0) echo 'checked="checked"'; ?>/>
											<label for="useenddate"><?php echo __('cards.useenddate'); ?></label>
										</th>
									</tr> -->
									<tr>
									<th colspan="2">
										<input type="checkbox" id="cardisactive" name="cardisactive" readonly="readonly" <?php if (!isset($card) || $card['ACTIVE']) echo 'checked="checked" '; ?>/>
										<label for="cardisactive"><?php echo __('cards.active'); ?></label>
									</th>
								</tr>
								</tbody>
							</table>
						</fieldset>
						<br />
						<br />

						<br />
						
						<br />
						<div id="cardactions1">
							<br />
							<?php if (isset($card) or 1) { ?>
			<input type="submit" value="<?php echo __('button.save'); ?>" />
			&nbsp;&nbsp;
			<input type="button" value="<?php echo __('button.cancel'); ?>" onclick="document.forms[0].reset()" />
			&nbsp;&nbsp;
			<input type="button" value="<?php echo __('button.backtocardlist'); ?>" onclick="location.href='<?php echo URL::base() . 'guests/cardlist/' . $contact['ID_PEP']; ?>'" />
						</div>
						<?php } ?>
					</td>
					<td width="5%">
					<fieldset>
							<legend><?php echo __('Категории доступа, присвоенные сотруднику'); ?></legend>
						<?php
							//echo Debug::vars('216', $contact_acl);
							if(isset($contact_acl))
							{
								foreach($contact_acl as $key=>$value)
								{
									echo iconv('CP1251', 'UTF-8', Arr::get($value, 'NAME')).'<br>';
									
								}
							} else {
								echo __('Нет категорий доступа, присвоенных сотруднику.');
							}
						?>
						</fieldset>
					</td>
				
				</tr>
			</table>
			<br />
			<?php if (isset($card)) { ?>
			<div id="cardhistory">
			<br />
			<h3><?php echo __('cards.loadhistory'); ?></h3>
			<table class="data" width="100%" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th style=""><?php echo __('load.device'); ?></th>
						<th style="width:20%"><?php echo __('load.date'); ?></th>
						<th style=""><?php echo __('load.status'); ?></th>
						<th style=""><?php echo __('load.insert'); ?></th>
						<th style=""><?php echo __('load.in_order'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($loads as $load) { ?>
					<tr>
						<td><?php echo iconv('CP1251', 'UTF-8', Arr::get($load,'NAME')); ?></td>
						<td><?php echo  Arr::get($load,'LOAD_TIME')? Arr::get($load,'LOAD_TIME') : __('device.no_data_about_load_time') ;?></td>
						<td><?php echo  (iconv('CP1251', 'UTF-8', Arr::get($load,'LOAD_RESULT')))? iconv('CP1251', 'UTF-8', Arr::get($load,'LOAD_RESULT')) : __('device.no_data_about_load_result') ;?></td>
						<td><?php echo  Arr::get($load,'TIME_STAMP')? Arr::get($load,'TIME_STAMP') : __('device.no_data_about_load_time_stamp') ;?></td>
						<td><?php echo  Arr::get($load,'OPERATION')? __('device.card_in_order_from_load') : __('device.card_not_in_order_from_load') ;?></td>
					</tr>
					<?php } ?>
					<?php if (count($loads) == 0) { ?>
					<tr> 
						<td colspan="5" style="text-align: center; line-height: 300%">
							<?php echo __('cards.nohistory'); ?>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<br />
			
			<input type="button" value="<?php echo __('cards.reload'); ?>" onclick="reload('<?php echo Arr::get($card, 'ID_CARD'); ?>')" />
			<br />
			</div>
			<?php } ?>
			<br />
			<br />
			<?php if (isset($card)) 
			{?>
				<input type="button" value="<?php echo __('cards.delete'); ?>" onclick="deletecard('<?php echo Arr::get($card, 'ID_CARD'); ?>')" />
			<?php };?>
		</form>
		</div>
	</div>
</div>
