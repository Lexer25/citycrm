<script type="text/javascript">


 
  	$(function() {		
  		$("#tablesorter").tablesorter();
		
  	});	
	
</script>
<?php 
$timestart=microtime(true);
//https://webformyself.com/sortirovka-tablic-pri-pomoshhi-plagina-tablesorter-js/?ysclid=lrgdz4nrzp693511651
// Отчет рабочего времени
//echo Debug::vars('2', $id_org); exit;
//echo Debug::vars('2', $catdTypelist); //exit;
//echo Debug::vars('3', $alert); //exit;
//echo Debug::vars('4', $filter); //exit;
//echo Debug::vars('5', $pagination); //exit;


	$report=Model::factory('ReportWorkTime');
	//$report->init_org($id_org);
	$report->init_pep($id_pep);
	$report->getReportWT();
			
			
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

		<span><?php echo __('report.title'); ?></span>
	</div>
	<br class="clear"/>
	<div class="content">
		<?php 
		//объявление типов идентификаторов
		$valuetype=array(
			0=>'',);
		
		if (true) { 
		
		$count=0;
		?>
		<form id="form_data" name="form_data" action="" method="post">
			<table class="data tablesorter-blue" width="100%" cellpadding="0" cellspacing="0" id="tablesorter" >
			<thead  allign="center">
					<tr>
						<th><?php echo __('report.count'); ?></th>
						<th><?php echo __('report.id_pep'); ?></th>
						<th><?php echo __('report.id_org'); ?></th>
						<th><?php echo __('report.pepname'); ?></th>
						<th><?php echo __('report.orgname'); ?></th>
						<th><?php echo __('report.time_in'); ?></th>
						<th><?php echo __('report.time_out'); ?></th>
						<th><?php echo __('report.workstart'); ?></th>
						<th><?php echo __('report.workend'); ?></th>
						<th><?php echo __('report.time_delay'); ?></th>
						<th><?php echo __('report.time_work'); ?></th>
						<th><?php echo __('report.time_before'); ?></th>
						<th><?php echo __('report.sec_work'); ?></th>
						<th><?php echo __('report.sec_delay'); ?></th>
						<th><?php echo __('report.sec_before'); ?></th>
						
					</tr>
					</thead>
					<tr align="center">
					<?php
						echo '<td>0</td>';
						echo '<td>1</td>';
						echo '<td>2</td>';
						echo '<td>3</td>';
						echo '<td>4</td>';
						echo '<td>5</td>';
						echo '<td>6</td>';
						
					
					?>
						
					</tr>
					
				
				<tbody>
					<?php foreach ($report->result as $key=>$value) { 
						//echo Debug::vars('78', $key, $value); exit;
						
					?>
					<tr>
						
						<td><?php echo $count++; ?></td>
						<td><?php echo HTML::anchor('' . Arr::get($value, 'ID_PEP'), Arr::get($value, 'ID_PEP')); ?></td>
						<td><?php echo  Arr::get($value, 'ID_ORG'); ?></td>
						<td><?php echo iconv('CP1251', 'UTF-8', Arr::get($value, 'PEPNAME')); ?></td> 
						<td><?php echo iconv('CP1251', 'UTF-8', Arr::get($value, 'ORGNAME')); ?></td> 
						<td><?php echo Arr::get($value, 'TIME_IN'); ?></td> 
						<td><?php echo Arr::get($value, 'TIME_OUT'); ?></td> 
						<td><?php echo Arr::get($value, 'WORKSTART'); ?></td> 
						<td><?php echo Arr::get($value, 'WORKEND'); ?></td> 
						<td><?php echo Arr::get($value, 'TIME_DELAY'); ?></td> 
						<td><?php echo Arr::get($value, 'TIME_WORK'); ?></td> 
						<td><?php echo Arr::get($value, 'TIME_BEFORE'); ?></td> 
						<td><?php echo Arr::get($value, 'SEC_WORK'); ?></td> 
						<td><?php echo Arr::get($value, 'SEC_DELAY'); ?></td> 
						<td><?php echo Arr::get($value, 'SEC_BEFORE'); ?></td> 
						
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<div id="chart_wrapper" class="chart_wrapper"></div>
		<!-- End bar chart table-->
		</form>
		<?php //echo $pagination; ?>
		<?php } else { ?>
		<div style="margin: 100px 0; text-align: center;">
			<?php echo __('report.empty'); ?><br /><br />
		</div>
		<?php }
		echo __('Time executed').' '. (microtime(true) - $timestart);
		?>
	</div>
</div>
