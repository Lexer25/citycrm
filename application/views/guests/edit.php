<script language="javascript">
	function validate()
	{
		$('.error').hide();
		if ($('#surname').val() == '') {
			$('#error1').show();
			$('#surname').focus();
			return false;
		}
		
		/*
		var ymd = $('#datebirth').val();
		if (ymd == '') {
			$('#error21').show();
			return false;
		}
		if (!ymd.match(/^\d{4}-\d{2}-\d{2}$/)) {
			$('#error22').show();
			return false;
		}
		ymd = ymd.split('-');
		if (ymd[1] > 12 || ymd[1] < 1 || ymd[2] > 31 || ymd[2] < 1) {
			$('#error23').show();
			return false;
		}
		numdoc
		*/
		
		ndoc = $('#numdoc').val(); 
		if(ndoc != ''){
			ymd = $('#datedoc').val(); 
			if (ymd == '') {
				$('#error31').show();
				return false;
			}
			
			if (!ymd.match(/^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[13-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/)) {
				$('#error32').show();
				return false;
			}
		}

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
	
	function validatecard()
	{
		$('.error').hide();
		if ($('#idcard').val() == '') {
			$('#error11').show();
			$('#idcard').focus();
			return false;
		}

		if (!$('#idcard').val().match(/^[0-9a-f]{1,8}$/i)) {
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
</script>

<?php 
//Многофункциональное окно редактирования информации о пользователе работает в нескольких режимах.
//archive_mode - режим архива. Возможен просмотр данных, отметка о выходе.
//guest_mode - режим регистрации гостя.
//order_mode - резервный режим. предполагает работу только со списком ранее разреешенных гостей
//echo Debug::vars('173-', Session::instance()->get('mode'));
//echo Debug::vars('174', $mode);
//echo Debug::vars('166', $id_pep);
//echo Debug::vars('89 contact', $contact);
//echo Debug::vars('90', $contact_acl);
//echo Debug::vars('160', $cardlist);
//echo Debug::vars('95', $alert);
//echo Debug::vars('95', $org_tree);
//echo Debug::vars('96 force_org', $force_org);
//echo Debug::vars('98', array_to_tree($org_tree));
//echo Debug::vars('98', out_options(array_to_tree($org_tree)));
//echo Debug::vars('173', Session::instance());
//echo Debug::vars('174', Session::instance()->get('alert'));
//echo Debug::vars('174', Session::instance()->get('arrAlert'));
//echo Debug::vars('175', Session::instance()->get('mode'));

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
		<span><?php 
		
		$guest=new Guest($id_pep);
		//echo Debug::vars('150', $guest);exit;
		switch($mode){
			
			case 'guest_mode'://просмотр гостя с картой, можно сделать отметку о выходе
			echo $id_pep ? __('guest.title') . ': ' . iconv('CP1251', 'UTF-8', $guest->name) . ' ' . iconv('CP1251', 'UTF-8', $guest->surname) : '';
			
			break;
			case 'archive_mode'://просмотр архива
			
			echo $id_pep ? __('guest.titleinArchive') . ': ' . iconv('CP1251', 'UTF-8', $guest->name) . ' ' . iconv('CP1251', 'UTF-8', $guest->surname) : '';
			break;
			case 'issue'://выдача карты новому гостю
			echo '<span>'.__('guest.registration').'</span>';
			
			break;
			
			
		}
		//echo $contact ? __('contact.title') . ': ' . iconv('CP1251', 'UTF-8', $guest->name) . ' ' . iconv('CP1251', 'UTF-8', $guest->surname) : __('guest.new'); ?></span>
		<?php if ($id_pep) { ?>
		<div class="switch">
			<table cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td>
						<a href="javascript:" class="left_switch active"><?php echo __('contact.common'); ?></a>
					</td>
					<td>
						<?php echo HTML::anchor('guests/acl/' . $guest->id_pep, __('contact.acl'), array('class' => 'middle_switch')); ?>
					</td>
					<td>
						<?php echo HTML::anchor('guests/cardlist/' . $guest->id_pep, __('contact.cardlist'), array('class' => 'middle_switch')); ?>
					</td>
					<td>
						<?php echo HTML::anchor('guests/history/' . $guest->id_pep, __('contact.history'), array('class' => 'right_switch')); ?>
					</td>
				</tr>
			</tbody>
			</table>
		</div>
		<?php } ?>
	</div>
	<br class="clear" />
	<div class="content">
				
		<form action="guests/save" method="post" onsubmit="return validate()">
			<input type="hidden" name="hidden" value="form_sent" />
			<input type="hidden" name="id_pep" value="<?php echo $id_pep; ?>" />
			
			<table style="margin: 0">
				<tr>
					<td>
						
						
						<fieldset>
						<legend>Персональные данные</legend>
						<div>
							<label for="surname"><?php echo __('contact.surname'); ?></label>
							<br />
							<input type="text" size="50" name="surname" id="surname" value="<?php echo iconv('CP1251', 'UTF-8', $guest->surname); ?>" />
							<br />
							<span class="error" id="error1" style="color: red; display: none;"><?php echo __('contact.emptysurname'); ?></span>
						</div>
						<br />
						<div>
							<table align="left">
								<tr>
									<td>
										<label for="name"><?php echo __('contact.name'); ?></label>
										<br />
										<input type="text" size="50" name="name" id="name" value="<?php echo iconv('CP1251', 'UTF-8', $guest->name); ?>" style="width: 150px" />
									</td>
									<td style="padding-left: 15px">
										<label for="patronymic"><?php echo __('contact.patronymic'); ?></label>
										<br />
										<input type="text" size="50" name="patronymic" id="patronymic" value="<?php echo iconv('CP1251', 'UTF-8', $guest->patronymic); ?>" style="width: 150px" />
									</td>
								</tr>
							</table>
						</div>
						<br style="clear: both;" />
						<br />
						
						<br />
						<div>
							<table align="left">
								<tr>
									<td>
										<label for="numdoc"><?php echo __('contact.numdoc'); ?></label>
										<br />
										<input type="text" size="23" name="numdoc" id="numdoc" value="<?php echo iconv('CP1251', 'UTF-8', $guest->numdoc); ?>" />
									</td>
									<td style="padding-left: 15px">
										<label for="datedoc"><?php echo __('contact.datedoc'); ?></label>
										<br />
										<input type="text" name="datedoc" id="datedoc" value="<?php 
											if(!is_null($guest->datedoc)) {
													echo date('d.m.Y', strtotime($guest->datedoc));
											} else {
													echo date("d.m.Y");
											}												?>" style="width: 100px;" />
										<br />
										<span class="error" id="error31" style="color: red; display: none;"><?php echo __('contact.emptydatedoc'); ?></span>
										<span class="error" id="error32" style="color: red; display: none;"><?php echo __('contact.wrongdatedoc'); ?></span>
										<span class="errpr" id="error33" style="color: red; display: none;"><?php echo __('contact.wrongdate'); ?></span>
									</td>
								</tr>

								
								
							</table>
						</div>
						<br style="clear: both;" />
					
					</fieldset>
					</td>
					<td style="padding-left: 40px; vertical-align: top;">

						<br />
			<input type="hidden" name="hidden" value="form_sent" />
			<input type="hidden" name="id_cardtype" value="1" />
			<?php if (isset($card)) { ?>
			<input type="hidden" name="id0" value="<?php echo Arr::get($card, 'ID_CARD'); ?>" />
			<?php } 
			//echo Debug::vars('349', isset($cardlist), count($cardlist));
			$key=new Keyk();
			//echo Debug::vars('325', $key->getListByPeople($id_pep, 1)); exit;
			$cardlist=$key->getListByPeople($id_pep, 1);
			if(count($cardlist)>0)
			{
			?>
			<fieldset>
				<legend><?php echo __('Зарегистрированные RFID'); ?></legend>
				<?php  
				
				
				$cardList=$guest->getTypeCardList(1);
							 foreach ($cardList as $key1=>$value)
							 {
								 
								 $card=new Keyk(Arr::get($value, 'ID_CARD'));
								
								 echo $card->id_card;
								echo '<br>';
							 }
				?>
			</fieldset>
			<fieldset>
				<legend><?php echo __('Зарегистрированные ГРЗ'); ?></legend>
				<?php  $cardList=$guest->getTypeCardList(4);
				 $cardList=$guest->getTypeCardList(4);
							 foreach ($cardList as $key1=>$value)
							 {
								 
								 $card=new Keyk(Arr::get($value, 'ID_CARD'));
								
								 echo $card->id_card;
								echo '<br>';
							 }
				?>
			</fieldset>
			<?php // если карт нет, то вывожу форму для добавления карты
			} else {
				?>
				<table >
					<tr valign="top">
						<td align="left" width="10%">
							<fieldset>
								<legend><?php echo __('guests.regcard'); ?></legend>
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
												<input type="hidden" size="12" maxlength="8"  id="idcard" name="idcard" value="<?php if (isset($card)) echo $guest->rfid; ?>" />	
													
												<?php } else {
												?>
													<input type="text" size="12" maxlength="8"  id="idcard" name="idcard" value="<?php if (isset($card)) echo Arr::get($card, 'ID_CARD'); ?>" />
													<br />
												<?php } ?>
													<span class="error" id="error11" style="color: red; display: none;"><?php echo __('card.emptyid'); ?></span>
													<span class="error" id="error12" style="color: red; display: none;"><?php echo __('card.wrongcharacter'); ?></span>
													<span class="error" id="error13" style="color: red; display: none;"><?php echo __('card.wronglenght'); ?></span>
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

															echo date('d.m.Y', strtotime('+1 day'));
														}														?>" />
													<br />
													<span class="error" id="error3" style="color: red; display: none;"><?php echo __('card.wrongendtime'); ?></span>
												</div>
											</td>
										</tr>
										
										<tr>

									</tr>
									</tbody>
								</table>
							</fieldset>
						

						
					
					</tr>
				</table>

				<?php 
					}
				?>			
							<br />
							
							<br />
						</td>
						<td style="padding-left: 80px; vertical-align: top;">
						<br>
					
						<?php //формирование расцветки и надписей
						
						//echo Debug::vars('352', $contact );
						
							echo '<br><label for="note">'.__('guests.note').'</label><br>';
							echo Form::textarea('note', iconv('CP1251', 'UTF-8', $guest->note), array('id'=>'note'));
						
						?>
			
						
							
						</td>
					</tr>
				</table>
			<br />
			
				<?php
				
			switch($mode){
			
				case 'guest_mode'://просмотр гостя с картой, можно сделать отметку о выходе
					echo Form::hidden('todo', 'forceexit');// 
					echo Form::submit('forceexit', __('guest.forceexit'));
				
				break;
				case 'archive_mode'://просмотр архива
				
					echo Form::hidden('todo', 'reissue');// 
					echo Form::submit('reissue', __('guest.reissue'));
					
				break;
				case 'issue'://выдача карты новому гостю
					echo Form::hidden('todo', 'savenew');// 
					echo Form::submit('savenew', __('button.save'));
					echo '&nbsp;&nbsp';
					echo Form::submit(null, __('button.cancel'), array('onclick'=>'document.forms[0].reset()'));
				
				break;
			
			
		}
		

		echo Form::close();
		
		
		echo Form::open('guests');
			echo Form::submit(null, __('button.backtolist'), array('onclick'=>'location.href='.URL::base().'guests'));
		echo Form::close();
			
		echo Form::open('guests/testAddGuest');
		
			//echo Form::submit('testAddGuest', __('guest.testAddGuest'));
			echo Form::close();
			
		
		?>
	</div>
</div>

	
	