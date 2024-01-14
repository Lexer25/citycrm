<?php
/*
Эта страница выводит список категорий доступа для указаного пипла
*/
?>
<script language="javascript">
	function validate()
	{
		$('.error').hide();
		if ($('#surname').val() == '') {
			$('#error1').show();
			$('#surname').focus();
			return false;
		}
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
		ymd = $('#datedoc').val(); 
		if (ymd == '') {
			$('#error31').show();
			return false;
		}
		if (!ymd.match(/^\d{4}-\d{2}-\d{2}$/)) {
			$('#error32').show();
			return false;
		}
		ymd = ymd.split('-');
		if (ymd[1] > 12 || ymd[1] < 1 || ymd[2] > 31 || ymd[2] < 1) {
			$('#error33').show();
			return false;
		}
		var hm = $('#workstart').val();
		if (hm == '') {
			$('#error41').show();
			$('#workstart').focus();
			return false;
		}
		if (!hm.match(/^\d{2}:\d{2}$/) && !hm.match(/^\d{2}:\d{2}:\d{2}$/)) {
			$('#error42').show();
			$('#workstart').focus();
			return false;
		}
		hm = hm.split(':');
		if (hm[0] > 23 || hm[1] > 59 || (hm.length == 3 && hm[2] > 59)) {
			$('#error43').show();
			$('#workstart').focus();
			return false;
		}
		hm = $('#workend').val();
		if (hm == '') {
			$('#error51').show();
			$('#workend').focus();
			return false;
		}
		if (!hm.match(/^\d{2}:\d{2}$/) && !hm.match(/^\d{2}:\d{2}:\d{2}$/)) {
			$('#error52').show();
			$('#workend').focus();
			return false;
		}
		hm = hm.split(':');
		if (hm[0] > 23 || hm[1] > 59 || (hm.length == 3 && hm[2] > 59)) {
			$('#error53').show();
			$('#workstart').focus();
			return false;
		}
		if ($('#tabnum').val() == '') {
			$('#error6').show();
			$('#tabnum').focus();
			return false;
		} else if ($('#login').val() == '') {
			$('#error7').show();
			$('#login').focus();
			return false;
		} else if ($('#password').val() == '') {
			$('#error8').show();
			$('#password').focus();
			return false;
		}
	}
</script>

<?php 
//echo Debug::vars('94', $contact);
//echo Debug::vars('95', $contact_acl);

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
		<span>
			<?php echo $contact ? __('guest.title') . ': ' . iconv('CP1251', 'UTF-8', $contact['NAME']) . ' ' . iconv('CP1251', 'UTF-8', $contact['SURNAME']) : __('contact.new'); ?>
		</span>
		<?php if ($contact) { ?>
		<div class="switch">
			<table cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td>
						<?php echo HTML::anchor('guests/edit/' . $contact['ID_PEP'], __('contact.common'), array('class' => 'left_switch')); ?>
					</td>
					<td>
						<a href="javascript:" class="middle_switch active"><?php echo __('contact.acl'); ?></a>
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
		<?php } ?>
	</div>
	<br class="clear" />
	<div class="content">
		<form action="contacts/saveACL" method="post" onsubmit="return validate()">
			<input type="hidden" name="hidden" value="form_sent" />
			<input type="hidden" name="id" value="<?php echo $contact['ID_PEP']; ?>" />
		
		<div style="padding-left: 15px">
		

		
					<div>
					<table style="margin: 0">
				<tr>

	
					<td style="padding-left: 80px; vertical-align: top;">
					<div>
						
						<?php
							//echo Debug::vars('296', AccessName::getList());
							echo __('Категории доступа (всего ancount)<br>', array('ancount'=>count(AccessName::getList())));
							$res=array();
							foreach($contact_acl as $key=>$value)
							{
								$res[]=Arr::get($value, 'ID_ACCESSNAME');
								
							}
							
							foreach (AccessName::getList() as $key=>$value)
							{
								echo Form::checkbox('aclList['.Arr::get($value, 'ID_ACCESSNAME').']', 1, in_array (Arr::get($value, 'ID_ACCESSNAME'), $res), array('disabled'=>'disabled')).' '. iconv('CP1251', 'UTF-8', Arr::get($value, 'NAME', '')).'<br>';
							}
						?>
						</div>
					</td>
				</tr>
			</table>
					</div>
<div>
			<br />
			<?php if (!$contact) {?>
			<input type="submit" value="<?php echo __('button.save'); ?>" />
			&nbsp;&nbsp;
			<input type="button" value="<?php echo __('button.cancel'); ?>" onclick="document.forms[0].reset()" />
			&nbsp;&nbsp;
			<?php }?>
			<!-- <input type="button" value="<?php echo __('button.backtolist'); ?>" onclick="location.href='<?php echo URL::base(); ?>guests/edit/<?php echo $contact['ID_PEP']; ?>/guest_mode'" /> -->
		</form>
	</div>
</div>
