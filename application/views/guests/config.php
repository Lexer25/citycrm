<?php 
/*
Страница настроек режимов работы Гостя

*/
//echo Debug::vars('6', $org_tree);
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

		<span><?php echo __('guests.config_title'); if (isset($company)) echo ' - ' . iconv('CP1251', 'UTF-8', $company['NAME']); ?></span>
		<?php if (isset($company)) { ?>

		<?php } ?>
	</div>
	<br class="clear"/>
	<div class="content">
		
		<form action="guests/saveconfig" method="post" onsubmit="return validate()">
			<table class="data" width="100%" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<!--
						<th style="width:10px">
							<input type="checkbox" id="check_all" name="check_all"/>
						</th>
						-->
						<th style="width:15%"><?php echo __('config.name'); ?></th>
						<th style="width:45%"><?php echo __('config.code'); ?></th>
						
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							ID_ORG гостевой организации
						</td>
						<td>
							<select name="idOrgGuest">
								<option></option>
								<?php
								$tree=new Tree();
									echo $tree->out_options($tree->array_to_tree($org_tree),$guestConfig->idOrgGuest);
								?>
							</select>
							<?php echo __('id_org=_id_org', array('_id_org'=>$guestConfig->idOrgGuest));?>

							
							
						</td>
					</tr>
					<tr>
						<td>
							ID_ORG Архива гостевой организации
						</td>
						<td>
							<select name="idOrgGuestArchive">
								<option></option>
								<?php
								$tree=new Tree();
									echo $tree->out_options($tree->array_to_tree($org_tree),$guestConfig->idOrgGuestArchive);
								?>
							</select>
							<?php echo __('id_org=_id_org', array('_id_org'=>$guestConfig->idOrgGuestArchive));?>
							
							
						</td>
					</tr>
						
				</tbody>
			</table>
			<div id="chart_wrapper" class="chart_wrapper"></div>
		<!-- End bar chart table-->
		<br />
			<input type="submit" value="<?php echo __('button.save'); ?>" />
			&nbsp;&nbsp;
			<input type="button" value="<?php echo __('button.cancel'); ?>" onclick="document.forms[0].reset()" />
			&nbsp;&nbsp;
			<input type="button" value="<?php echo __('button.backtolist'); ?>" onclick="location.href='<?php echo URL::base(); ?>contacts'" />
		</form>

	</div>
</div>
