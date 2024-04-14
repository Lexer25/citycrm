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
<?php 
include Kohana::find_file('views','alert');
if (isset($alert)) { ?>
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
					echo __('card.titleHistory', array (':id_card'=>$key->id_card_on_screen));
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
						<?php echo HTML::anchor('/cards/edit/' . $key->id_card, __('card.common'), array('class' => 'left_switch ')); ?>
					</td>

					<td>
						<?php echo HTML::anchor('cards/load/' . $key->id_card, __('card.load'), array('class' => 'middle_switch ')); ?>
					</td>
					<td>
						<?php echo HTML::anchor('cards/history/' . $key->id_card, __('card.history'), array('class' => 'right_switch active')); ?>
					</td>
				</tr>
			</tbody>
			</table>
		</div>
	</div>
	<br class="clear" />
	<div class="content">
		<div id="cardinfo">
		<form action="cards/loadcard" method="post" onsubmit="return validatecard()">
			<input type="hidden" name="hidden" value="form_sent" />
			<input type="hidden" name="id" value="<?php echo $key->id_pep; ?>" />
			<input type="hidden" name="id_cardtype" value="1" />


			<br />
			<?php if (isset($key)) { ?>
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

			<?php } ?>
			<br />
			<br />
			

		</form>
		</div>
	</div>
</div>
