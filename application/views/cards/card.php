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
			location.href = '<?php echo URL::base(); ?>contacts/deletecard/' + id;
		}
	}
	function reload(id) {
		if (confirm('<?php echo __('cards.reload'); ?>')) {
			location.href = '<?php echo URL::base(); ?>contacts/reload/' + id;
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
		<span>
			<?php 
			
			switch($mode) {
				case('new'):
					;
				break;
				
				case('edit'):
					echo __('card.titleEdit', array(':id_card'=>$key->id_card));
				break;
				
				case('fired'):
					
				break;
				default:
					echo __('form.editCard');
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
							echo HTML::anchor('/cards/edit/' . $key->id_card, __('card.common'), array('class' => 'left_switch active'));
						?>
					</td>

					<td>
						<?php echo HTML::anchor('cards/load/' . $key->id_card, __('card.load'), array('class' => 'middle_switch')); ?>
					</td>
					<td>
						<?php echo HTML::anchor('cards/history/' . $key->id_card, __('card.history'), array('class' => 'right_switch')); ?>
					</td>
				</tr>
			</tbody>
			</table>
		</div>
	</div>
	<br class="clear" />
	<div class="content">
		<div id="cardinfo">
		<form action="cards/savecard" method="post" onsubmit="return validatecard()">
			<input type="hidden" name="hidden" value="form_sent" />
			<input type="hidden" name="id" value="<?php echo $key->id_pep; ?>" />
			<input type="hidden" name="id_cardtype" value="1" />

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
											
											<?php 
											 //echo Debug::vars('137', $key); 
											 echo $key->id_card; 
											 if($key->id_cardtype  == 1) echo ' ('.Model::factory('Stat')->reviewKeyCode($key->id_card).')';
											echo Form::hidden('idcard',$key->id_card);	
											?>
											
											
										</td>
									</tr>
									<tr>
										<th align="right" style="padding-right: 10px;">
											<label for="carddatestart"><?php echo __('cards.datestart'); ?></label> 
										</th>
										<td>
											<div style="padding-bottom: 10px;">
											
												<input type="text" size="12" name="carddatestart" id="carddatestart" value="<?php 
													if (isset($key->timestart)) 
													{
														
														echo date("d.m.Y", strtotime($key->timestart));
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
													if (isset($key->timeend))
													{
														echo date("d.m.Y", strtotime($key->timeend));
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
											<input type="checkbox" id="useenddate" name="useenddate" <?php if (isset($key)) if ($key->flag != 0) echo 'checked="checked"'; ?>/>
											<label for="useenddate"><?php echo __('cards.useenddate'); ?></label>
										</th>
									</tr> -->
									<tr>
									<th colspan="2">
										<input type="checkbox" id="cardisactive" name="cardisactive" readonly="readonly" <?php if (!isset($key) || $key->is_active) echo 'checked="checked" '; ?>/>
										<label for="cardisactive"><?php echo __('cards.active'); ?></label>
									</th>
								</tr>
								</tbody>
							</table>
						</fieldset>
						
					
							<br />
			<input type="submit" value="<?php echo __('button.save'); ?>" />
			&nbsp;&nbsp;

			<input type="button" value="<?php echo __('button.cancel'); ?>" onclick="document.forms[0].reset()" />
			&nbsp;&nbsp;
			<input type="button" value="<?php echo __('button.backtocardlist'); ?>" onclick="location.href='<?php echo URL::base() . 'cards'; ?>'" />
						

					</td>
					<td width="5%">
					<fieldset>
							<legend><?php echo __('Категории доступа, присвоенные сотруднику'); ?></legend>
						<?php
							//echo Debug::vars('216', $key_acl);
							if(isset($key_acl))
							{
								foreach($key_acl as $key=>$value)
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

			<br />
			<br />
			

		</form>
		</div>
	</div>
</div>
