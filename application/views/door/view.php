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
     closest(this).querySelector('[name=save_door_data]').disabled = false;
}
function closest(el) {
    while ((el = el.parentElement) && el.tagName != 'TR');
    return el;
}


</script>
<?php

$door=new Door($id_door);
$device=new Device($door->parent );
//echo Debug::vars('2', $id_door, $door);//exit;
?>
<form action="doors/save" method="post" onsubmit="return validate()">
<div class="onecolumn">
	<div class="header">
		<span class="error"><?php echo __('door.title') . ': ' . iconv('CP1251', 'UTF-8', $door->name).' (id_dev='.$door->id.')'; ?></span>
		<?php 	
					echo $topbuttonbar;
	
			
		
		?>
	</div>
	
		
	<br class="clear" />
	<div class="content">
				<input type="hidden" name="id_dev" size="15"  value="<?php echo $door->id; ?>" />
				<input type="hidden" name="id_server" size="15"  value="<?php echo $door->id; ?>" />
			<p>
				<label for="name"><?php echo __('door.name'); ?></label>
				<br />
				<input type="text" id="name" name="name" disabled  size="50" value="<?php echo iconv('CP1251', 'UTF-8', $door->name); ?>" />
				<br />
				<span class="error" id="error1" style="color: red; display: none;"><?php echo __('door.emptyname'); ?></span>
			</p>
			<br />
		<p>
				<label for="name"><?php echo __('door.parentname'); ?></label>
				<br />
				<input type="text" id="name" name="name" disabled  size="50" value="<?php echo iconv('CP1251', 'UTF-8', $device->name); ?>" />
				<br />
				<span class="error" id="error1" style="color: red; display: none;"><?php echo __('door.emptyname'); ?></span>
			</p>
			<br />
		
			
			<p>
				<label for="access"><?php echo __('door.is_active'); ?></label>
				<br />
				<?php echo Form::checkbox('is_active', 1, $door->is_active ==1, array('disabled'=>'disabled')).'<br>'; 
				
				?>
			</p>
			<br />
			<br />
			<br />
			<?php
				echo 'id_dev='.$door->id;
			?>
			
			<!--
			<input type="submit" name="save_door_data" value="<?php echo __('button.save'); ?>" />
			&nbsp;&nbsp;
			<input type="button" value="<?php echo __('button.cancel'); ?>" onclick="document.forms[0].reset()" />
			&nbsp;&nbsp;
			<input type="button" value="<?php echo __('button.backtolist'); ?>" onclick="location.href='<?php echo URL::base(); ?>doors'" />
			->
			<?php
				Form::close();
			?>
	</div>
</div>
