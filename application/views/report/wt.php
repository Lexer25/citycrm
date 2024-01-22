<script type="text/javascript">


 
  	$(function() {		
  		$("#tablesorter").tablesorter();
		
  	});	
	
</script>
<?php 
$timestart=microtime(true);
//echo Debug::vars('13', $report);
$pep=new Contact($report->id_pep);
			
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

		<span><?php echo __('report.title', array(':surname'=>iconv('CP1251', 'UTF-8',$pep->surname),':name'=>iconv('CP1251', 'UTF-8',$pep->name),':patronymic'=>iconv('CP1251', 'UTF-8',$pep->patronymic), ':timefrom'=>$report->timestart, ':timeTo'=>$report->timeend)); ?></span>
	</div>
	<br class="clear"/>
	<div class="content">
		<?php 
		
		if (count($report->result)) { 
		
		$count=0;
		?>
		<form id="form_data" name="form_data" action="" method="post">
			<table class="data tablesorter-blue" width="100%" cellpadding="0" cellspacing="0" id="tablesorter" >
			<thead  allign="center">
					<tr>
						<th><?php echo __('report.count'); ?></th>
						<th><?php echo __('report.date'); ?></th>
						<th><?php echo __('report.dayOfWeek'); ?></th>
						<th><?php echo __('report.id_pep'); ?></th>
						<th><?php echo __('report.id_org'); ?></th>
						<th><?php echo __('report.pepname'); ?></th>
						<th><?php echo __('report.time_in'); ?></th>
						<th><?php echo __('report.time_out'); ?></th>
						<th><?php echo __('report.time_work'); ?></th>
						<th><?php echo __('report.длительность по факту, секунд'); ?></th>
						<th><?php echo __('report.длительность по графику, секунд'); ?></th>
						<th><?php echo __('report.Недоработал'); ?></th>
						<th><?php echo __('report.time_work'); ?></th>
						
						
						
					</tr>
					</thead>
					<tr align="center">
					<?php
						
						echo '<td>1</td>';
						echo '<td>2</td>';
						echo '<td>3</td>';
						echo '<td>4</td>';
						echo '<td>5</td>';
						echo '<td>6</td>';
						echo '<td>7</td>';
						echo '<td>8</td>';
						echo '<td>9</td>';
						echo '<td>10</td>';
						echo '<td>11</td>';
						echo '<td>12</td>';
						
						
					
					?>
						
					</tr>
					
				
				<tbody>
					<?php foreach ($report->result as $key=>$value) { 
						//echo Debug::vars('78', $key, $value); //exit;
						$long=strtotime(Arr::get($value, 'MAX'))-strtotime(Arr::get($value, 'MIN')); //- сколько времени провел на работе
						$duration_day=Arr::get($duration, date('w', strtotime(Arr::get($value, 'MIN'))));// длительность рабочего дня в секундах
						
					?>
					<tr>
						
						<td><?php echo ++$count; ?></td>
						<td><?php echo date('d.m.Y', strtotime(Arr::get($value, 'MIN'))); ?></td>
						<td><?php echo date('w', strtotime(Arr::get($value, 'MIN'))); ?></td>
						<td><?php echo HTML::anchor('' . $report->id_pep, $report->id_pep); ?></td>
						<td><?php echo HTML::anchor('' . $pep->id_org, $pep->id_org); ?></td>
						<td><?php echo iconv('CP1251', 'UTF-8', $pep->surname); ?></td> 
						<td><?php echo date('H:i:s', strtotime(Arr::get($value, 'MIN'))); ?></td> 
						<td><?php echo date('H:i:s', strtotime(Arr::get($value, 'MAX'))); ?></td> 
						<td><?php 
							$long=strtotime(Arr::get($value, 'MAX'))-strtotime(Arr::get($value, 'MIN'));
							echo floor($long/3600).':'
								.str_pad(floor($long%3600/60),2, 0,STR_PAD_LEFT).':'
								.str_pad(($long%3600)%60,2, 0,STR_PAD_LEFT); ?>
						</td> 
						<td><?php 
							echo $long; ?>
						</td> 
						
						<td><?php echo $duration_day; ?></td> 
						<td><?php 
						$var=$duration_day - $long;
						echo floor($var/3600).':'
								.str_pad(floor($var%3600/60),2, 0,STR_PAD_LEFT).':'
								.str_pad(($var%3600)%60,2, 0,STR_PAD_LEFT);?></td> 
						
						
					
						
						
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<br>
		
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
