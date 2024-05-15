<script type="text/javascript">
	function validate()
	{
		$('#error1, #error2').hide();
		if ($('#name').val() == '') {
			$('#error1').show();
			$('#name').focus();
			return false;
		} 
	}
</script>
<?php 
//echo Debug::vars('18', $parents);
//echo Debug::vars('19', $alert);
//echo Debug::vars('20', $acl);
//exit;

$contact= new Contact($id_pep);
if ($alert) { ?>
<div class="alert_success">
	<p>
		<img class="mid_align" alt="success" src="images/icon_accept.png" />
		<?php echo $alert; ?>
	</p>
</div>
<?php } 
?>
<div class="onecolumn">
	<div class="header">
		<span class="error"><?php 
		
		if ($contact){
			echo __('report.title', array(
			':surname'=>iconv('CP1251', 'UTF-8', $contact->surname),
			':name'=>iconv('CP1251', 'UTF-8', $contact->name), 
			':patronymic'=>iconv('CP1251', 'UTF-8', $contact->patronymic),
			':timefrom'=>(isset($key->timestart))? $key->timestart : date("d.m.Y"),
			':timeTo'=>(isset($key->timeend))? $key->timeend : date("d.m.Y")
			));
			} else {
				echo $contact->id_org ;
			}				?></span>

	</div>
	<br class="clear" />
	<div class="content">
		<form action="reports/wtOncePep" method="post" onsubmit="return validate()">
			
			<table cellspacing="5" cellpadding="5">
								<tbody>
									<tr>
										<th align="right" style="padding-right: 10px;">
											<label for="reportdatestart"><?php echo __('report.datestart'); ?></label> 
										</th>
										<td>
											<div style="padding-bottom: 10px;">
											
												<input type="text" size="12" name="reportdatestart" id="carddatestart" value="<?php 
													if (isset($key->timestart)) 
													{
														echo date("d.m.Y", strtotime($key->timestart));
													} else {
														echo date("d.m.Y");
													}														?>" />
												<br />
												<span class="error" id="error2" style="color: red; display: none;"><?php echo __('report.emptystarttime'); ?></span>
											</div>
										</td>
									</tr>
									
									<tr>
										<th align="right" style="padding-right: 10px;">
											<label for="reportdateend"><?php echo __('report.dateend'); ?></label> 
										</th>
										<td>
											<div style="padding-bottom: 10px;">
												<input type="text" size="12" name="reportdateend" id="carddateend" value="<?php 
													if (isset($key->timeend))
													{
														echo date("d.m.Y", strtotime($key->timeend));
													} else {

														echo date('d.m.Y');
													}														?>" />
												<br />
												<span class="error" id="error3" style="color: red; display: none;"><?php echo __('report.wrongendtime'); ?></span>
											</div>
										</td>
									</tr>

								</tr>
								</tbody>
							</table>
		
		<?php
				
				echo Form::hidden('id_pep', $contact->id_pep); 
				echo Form::hidden('todo', 'wtOncePep'); 
				echo Form::submit(NULL, __('button.report1'));
				echo Form::close();
		?>
		
		
	</div>
</div>
