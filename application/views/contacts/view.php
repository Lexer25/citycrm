<div class="onecolumn">
	<div class="header">
		<span><?php echo __('contact.title') . ": " . iconv('CP1251', 'UTF-8', $contact['NAME'] . ' ' . $contact['SURNAME']); ?></span>
		<?php if ($contact) { ?>
		<div class="switch">
			<table cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td>
						<a href="javascript:" class="left_switch active"><?php echo __('contact.common'); ?></a>
					</td>
					<td>
						<?php echo HTML::anchor('contacts/cardlist/' . $contact['ID_PEP'], __('contact.cardlist'), array('class' => 'middle_switch')); ?>
					</td>
					<td>
						<?php echo HTML::anchor('contacts/pay/' . $contact['ID_PEP'], __('contact.pay'), array('class' => 'middle_switch')); ?>
					</td>
					<td>
						<?php echo HTML::anchor('contacts/history/' . $contact['ID_PEP'], __('contact.history'), array('class' => 'right_switch')); ?>
					</td>
				</tr>
			</tbody>
			</table>
		</div>
		<?php } ?>
	</div>
	<br class="clear" />
	<div class="content">
		<form action="contacts/save" method="post" onsubmit="return validate()">
			<input type="hidden" name="hidden" value="form_sent" />
			<input type="hidden" name="id" value="<?php echo $contact['ID_PEP']; ?>" />
			<table style="margin: 0">
				<tr>
					<td>
						<div>
							<?php if ($contact['PHOTO'] != null) { ?>
							<img src="data:image/jpeg;base64,<?php echo base64_encode($contact['PHOTO']); ?>" height="200" alt="photo" />
							<?php } else { 
							
								echo HTML::image("images/nophoto.png", array('height' => 200, 'alt' => 'photo'));
							}?>
						</div>
						<div>
							<label for="surname"><?php echo __('contact.surname'); ?></label>
							<br />
							<input type="text" size="50" name="surname" id="surname" value="<?php echo iconv('CP1251', 'UTF-8', $contact['SURNAME']); ?>" disabled="disabled" />
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
										<input type="text" size="50" name="name" id="name" value="<?php echo iconv('CP1251', 'UTF-8', $contact['NAME']); ?>" style="width: 150px" disabled="disabled" />
									</td>
									<td style="padding-left: 15px">
										<label for="patronymic"><?php echo __('contact.patronymic'); ?></label>
										<br />
										<input type="text" size="50" name="patronymic" id="patronymic" value="<?php echo iconv('CP1251', 'UTF-8', $contact['PATRONYMIC']); ?>" style="width: 150px" disabled="disabled" />
									</td>
								</tr>
							</table>
						</div>
						<br style="clear: both;" />
						<br />
						<div>
							<label for="datebirth"><?php echo __('contact.datebirth'); ?></label>
							<br />
							<input type="text" name="datebirth" id="datebirth" value="<?php echo $contact['DATEBIRTH']; ?>" style="width: 100px;" disabled="disabled" />
							<br />
							<span class="error" id="error21" style="color: red; display: none;"><?php echo __('contact.emptydatebirth'); ?></span>
							<span class="error" id="error22" style="color: red; display: none;"><?php echo __('contact.wrongdatebirth'); ?></span>
							<span class="errpr" id="error23" style="color: red; display: none;"><?php echo __('contact.wrongdate'); ?></span>
						</div>
						<br />
						<div>
							<table align="left">
								<tr>
									<td>
										<label for="numdoc"><?php echo __('contact.numdoc'); ?></label>
										<br />
										<input type="text" size="23" name="numdoc" id="numdoc" value="<?php echo iconv('CP1251', 'UTF-8', $contact['NUMDOC']); ?>" disabled="disabled" />
									</td>
									<td style="padding-left: 15px">
										<label for="datedoc"><?php echo __('contact.datedoc'); ?></label>
										<br />
										<input type="text" name="datedoc" id="datedoc" value="<?php echo $contact['DATEDOC']; ?>" style="width: 100px;" disabled="disabled" />
										<br />
										<span class="error" id="error31" style="color: red; display: none;"><?php echo __('contact.emptydatedoc'); ?></span>
										<span class="error" id="error32" style="color: red; display: none;"><?php echo __('contact.wrongdatedoc'); ?></span>
										<span class="errpr" id="error33" style="color: red; display: none;"><?php echo __('contact.wrongdate'); ?></span>
									</td>
								</tr>
							</table>
						</div>
						<br style="clear: both;" />
					</td>
					<td style="padding-left: 80px; vertical-align: top;">
						<div>
							<label for="id_org"><?php echo __('contact.company'); ?></label>
							<br />
							<select name="id_org" disabled="disabled">
								<?php
								foreach ($companies as $c)
									if ($c['ID_ORG'] == $contact['ID_ORG'])
										echo '<option value="' . $c['ID_ORG'] . '" selected="selected">' . iconv('CP1251', 'UTF-8', $c['NAME']) . '</option>';
									else
										echo '<option value="' . $c['ID_ORG'] . '">' . iconv('CP1251', 'UTF-8', $c['NAME']) . '</option>';
								?>
							</select>
						</div>
						<br />
						<div>
							<table align="left">
								<tbody>
								<tr>
									<td>
										<label for="workstart"><?php echo __('contact.workstart'); ?></label>
										<br />
										<input type="text" name="workstart" id="workstart" value="<?php echo $contact['WORKSTART']; ?>" disabled="disabled" />
										<br />
										<span class="error" id="error41" style="color: red; display: none;"><?php echo __('contact.emptyworkstart'); ?></span>
										<span class="error" id="error42" style="color: red; display: none;"><?php echo __('contact.wrongworkstart'); ?></span>
										<span class="error" id="error43" style="color: red; display: none;"><?php echo __('contact.wrongtime'); ?></span>
									</td>
									<td style="padding-left: 15px;">
										<label for="workend"><?php echo __('contact.workend'); ?></label>
										<br />
										<input type="text" name="workend" id="workend" value="<?php echo $contact['WORKEND']; ?>" disabled="disabled" />
										<br />
										<span class="error" id="error51" style="color: red; display: none;"><?php echo __('contact.emptyworkend'); ?></span>
										<span class="error" id="error52" style="color: red; display: none;"><?php echo __('contact.wrongworkend'); ?></span>
										<span class="error" id="error53" style="color: red; display: none;"><?php echo __('contact.wrongtime'); ?></span>
									</td>
								</tr>
								</tbody>
							</table>
						</div>
						<br style="clear: both;" />
						<br />
						<div>
							<?php echo Form::checkbox('active', '1', $contact['ACTIVE'] == 1, array('disabled' => 'disabled')); ?>
							<label for="active"><?php echo __('contact.active'); ?></label>
						</div>
						<br />
						<!--
						<div>
							<?php echo Form::checkbox('flag', '1', $contact['FLAG'] == 1, array('disabled' => 'disabled')); ?>
							<label for="flag"><?php echo __('contact.flag'); ?></label>
						</div>
						<br />
						-->
						<div>
							<?php echo Form::checkbox('peptype', '1', $contact['PEPTYPE'] == 1); ?>
							<label for="peptype"><?php echo __('contact.peptype'); ?></label>
						</div>
						<br />
						<div>
							<label for="post"><?php echo __('contact.post'); ?></label>
							<br />
							<input type="text" size="50" name="post" id="post" value="<?php echo iconv('CP1251', 'UTF-8', $contact['POST']); ?>" disabled="disabled" />
						</div>
						<br />
						<div>
							<label for="tabnum"><?php echo __('contact.tabnum'); ?></label>
							<br />
							<input type="text" size="50" name="tabnum" id="tabnum" value="<?php echo iconv('CP1251', 'UTF-8', $contact['TABNUM']); ?>" disabled="disabled" />
							<br />
							<span class="error" id="error6" style="color: red; display: none;"><?php echo __('contact.emptytabnum'); ?></span>
						</div>
						<br />
						<div>
							<table align="left">
								<tr>
									<td>
										<label for="login"><?php echo __('contact.login'); ?></label>
										<br />
										<input type="text" name="login" id="login" size="12" value="<?php echo iconv('CP1251', 'UTF-8', $contact['LOGIN']); ?>" disabled="disabled" />
										<br />
										<span class="error" id="error7" style="color: red; display: none;"><?php echo __('contact.emptylogin'); ?></span>
									</td>
									<td style="padding-left: 15px">
										<label for="password"><?php echo __('contact.password'); ?></label>
										<br />
										<input type="password" size="12" name="password" id="password" value="<?php echo iconv('CP1251', 'UTF-8', $contact['PSWD']); ?>" disabled="disabled" />
										<br />
										<span class="error" id="error8" style="color: red; display: none;"><?php echo __('contact.emptypassword'); ?></span>
									</td>
								</tr>
							</table>
						</div>
						<br />
					</td>
				</tr>
			</table>
			<br />
			<!--
			<input type="submit" value="<?php echo __('button.save'); ?>" />
			&nbsp;&nbsp;
			<input type="button" value="<?php echo __('button.cancel'); ?>" onclick="document.forms[0].reset()" />
			&nbsp;&nbsp;
			-->
			<input type="button" value="<?php echo __('button.backtolist'); ?>" onclick="location.href='<?php echo URL::base(); ?>contacts'" />
		</form>
	</div>
</div>
