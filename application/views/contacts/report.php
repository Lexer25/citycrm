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
//echo Debug::vars('17', $id_org);
//echo Debug::vars('18', $parents);
//echo Debug::vars('19', $alert);
//echo Debug::vars('20', $acl);
//exit;

$company= new company($id_org);
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
		<span class="error"><?php echo $company ? __('report.title') . ': ' . iconv('CP1251', 'UTF-8', $company->name) . ' '.$company->id_org : __('company.new'); ?></span>
		<?php if (isset($company->id_org)) { ?>
		<div class="switch">
			<table cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td>
						<a href="javascript:" class="left_switch active"><?php echo __('company.data'); ?></a>
					</td>

					<td>
						<?php echo HTML::anchor('companies/acl/' . $company->id_org, __('company.acl'), array('class' => 'middle_switch')); ?>
					</td>
					<td>
						<?php echo HTML::anchor('companies/people/' . $company->id_org, __('company.contacts'), array('class' => 'right_switch')); ?>
					</td>
				</tr>
			</tbody>
			</table>
			
		</div>
		<?php } ?>
	</div>
	<br class="clear" />
	<div class="content">
		<form action="companies/report" method="post" onsubmit="return validate()">
			
			<table cellspacing="5" cellpadding="5">
								<tbody>
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

								</tr>
								</tbody>
							</table>
		
		<?php
				
				echo Form::hidden('id_org', $company->id_org); 
				echo Form::hidden('todo', 'make_report'); 
				echo Form::submit(NULL, __('button.report1'));
				echo Form::close();
		?>
		
		
	</div>
</div>
