<script language="javascript">
	function validate()
	{
		$('.error').hide();
		if ($('#name').val() == '') {
			$('#error1').show();
			$('#name').focus();
			return false;
		}
		/*
		if ($('#ip').val() == '') {
			$('#ip_empty').show();
			$('#ip').focus();
			return false;
		}
		
		var ymd = $('#ip').val();
		if (!ymd.match(/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/)) {
			$('#ipFormatError').show();
			$('#ip').focus();
			return false;
		}
		
		if ($('#port').val() == '') {
			$('#ipPortEmpty').show();
			$('#port').focus();
			return false;
		}
		
		
		if ($('#port').val() > 65535) {
			$('#ipPortFormatError').show();
			$('#port').focus();
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
		*/
	}
</script>	
<script>	
	
//https://www.cyberforum.ru/javascript/thread1439785.html	
	
	var d = document;
[].forEach.call(d.querySelectorAll('text'), function (tarea) {
    tarea.addEventListener('keyup', undisabledButton, false);
});
[].forEach.call(d.querySelectorAll('[name=devtype]'), function (sel) {
    sel.addEventListener('change', undisabledButton, false);
});
 
function undisabledButton() {
     closest(this).querySelector('[name=save_device_data]').disabled = false;
}
function closest(el) {
    while ((el = el.parentElement) && el.tagName != 'TR');
    return el;
}


</script>
<?php
echo Debug::vars('2', $device);
?>
<form action="devices/save" method="post" onsubmit="return validate()">
<div class="onecolumn">
	<div class="header">
		<span class="error"><?php echo __('device.title') . ': ' . iconv('CP1251', 'UTF-8', Arr::get($device,'NAME')).' (id_dev='.Arr::get($device,'ID_DEV').')'; ?></span>
		<div class="switch">
			<table cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td>
						<a href="javascript:" class="left_switch active"><?php echo __('device.data'); ?></a>
					</td>
					<td>
						<?php echo HTML::anchor('companies/people/' . Arr::get($device,'ID_ORG'), __('device.contacts'), array('class' => 'right_switch')); ?>
					</td>
				</tr>
			</tbody>
			</table>
		</div>
	</div>
	<br class="clear" />
	<div class="content">
				<input type="hidden" name="id_dev" size="15"  value="<?php echo Arr::get($device,'ID_DEV'); ?>" />
				<input type="hidden" name="id_server" size="15"  value="<?php echo Arr::get($device,'ID_SERVER'); ?>" />
			<p>
				<label for="name"><?php echo __('device.name'); ?></label>
				<br />
				<input type="text" id="name" name="name" size="50" value="<?php echo iconv('CP1251', 'UTF-8', Arr::get($device,'NAME')); ?>" />
				<br />
				<span class="error" id="error1" style="color: red; display: none;"><?php echo __('device.emptyname'); ?></span>
			</p>
			<br />
			<p>
				<label for="code"><?php echo __('device.ip'); ?></label>
				
				<input type="text" id="ip" name="ip" size="15"  value="<?php echo Arr::get($device,'IP'); ?>" />
				<span class="error" id="ip_empty" style="color: red; display: none;"><?php echo __('device.ip_empty'); ?></span>
				<span class="error" id="ipFormatError" style="color: red; display: none;"><?php echo __('device.ipFormatError'); ?></span>
				<br />
				<label for="code"><?php echo __('device.port'); ?></label>
				<input type="text" id="port" name="port" size="5"  value="<?php echo Arr::get($device,'PORT'); ?>" />
				<span class="error" id="ipPortEmpty" style="color: red; display: none;"><?php echo __('device.ipPortEmpty'); ?></span>
				<span class="error" id="ipPortFormatError" style="color: red; display: none;"><?php echo __('device.ipPortFormatError'); ?></span>
				<br />
				
				
				
				<span class="error" id="error2" style="color: red; display: none;"><?php echo __('device.emptycode'); ?></span>
			</p>
				<p>
				<?php echo Form::label('parent', __('device.devtype')); ?>
				<br />
				
					<?php
					
					$devtype=new ConfigType();
					echo Form::select('devtype', $devtype->getDeviceTypeList(), Arr::get($device,'ID_DEVTYPE'));
				
					?>
				
			</p>
			<br />
		
			<br />
			<p>
				<label for="access"><?php echo __('device.is_active'); ?></label>
				<br />
				<?php echo Form::checkbox('is_active', 1, Arr::get($device,'IS_ACTIVE')==1); ?>
			</p>
			<br />
			<br />
			<br />
			<input type="submit" name="save_device_data" value="<?php echo __('button.save'); ?>" />
			&nbsp;&nbsp;
			<input type="button" value="<?php echo __('button.cancel'); ?>" onclick="document.forms[0].reset()" />
			&nbsp;&nbsp;
			<input type="button" value="<?php echo __('button.backtolist'); ?>" onclick="location.href='<?php echo URL::base(); ?>devices'" />
			<?php
			Form::close();
			?>
	</div>
</div>
