<script type="text/javascript">


 
  	$(function() {		
  		$("#tablesorter").tablesorter();
		
  	});	
	
</script>
<?php 
$timestart=microtime(true);
//echo Debug::vars('13', $report);
$pep=new Contact($report->id_pep);
//echo Debug::vars('15', $report->result); exit;


			
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
		<?php
				echo Form::open('reports/savecsv');
				echo Form::hidden('id_pep', $pep->id_pep); 
				echo Form::hidden('forsave', serialize ($report->result)); 
				echo Form::hidden('todo', 'savecvs'); 
				echo Form::submit(NULL, __('button.savecsv'));
				echo Form::close();
		
				echo Form::open('reports/savexlsx');
				echo Form::hidden('id_pep', $pep->id_pep); 
				echo Form::hidden('forsave', serialize ($report->result)); 
				echo Form::hidden('todo', 'savecvs'); 
				echo Form::submit(NULL, __('button.xlsx'));
				echo Form::close();
				
				echo Form::open('reports/savepdf');
				echo Form::hidden('id_pep', $pep->id_pep); 
				echo Form::hidden('forsave', serialize ($report->result)); 
				echo Form::hidden('todo', 'savecvs'); 
				echo Form::submit(NULL, __('button.pdf'));
				echo Form::close();
				
				
		?>
	<br class="clear"/>
	<div class="content">
		<?php 
		$weekDay=array('Вскр','Пнд','Вт','Ср','Чт','Птн','Сб');
		if (count($report->result)) { 
		
		$count=0;
		?>
		<form id="form_data" name="form_data" action="" method="post">
			<table class="data tablesorter-blue" width="100%" cellpadding="0" cellspacing="0" id="tablesorter" >
			<thead  allign="center">
					<tr>
						<th><?php echo __('report.count'); ?></th>
						<th><?php echo __('report.date'); ?></th>
						<!-- <th><?php echo __('report.id_pep'); ?></th>
						<th><?php echo __('report.org'); ?></th> 
						<th><?php echo __('report.pepname'); ?></th>-->
						<th><?php echo __('report.time_in'); ?></th>
						<th><?php echo __('report.time_out'); ?></th>
						<th><?php echo __('report.time_work'); ?></th>
						<th><?php echo __('report.time_startCount'); ?></th>
						<th><?php echo __('report.time_endCount'); ?></th>
						<th><?php echo __('report.time_workCount'); ?></th>
						<th><?php echo __('Длительность рабочего дня'); ?></th>
						<th><?php echo __('Недоработал'); ?></th>
						
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
						//echo '<td>11</td>';
						//echo '<td>12</td>';
						//echo '<td>13</td>';
						
						
					
					?>
						
					</tr>
					
				
				<tbody>
					<?php foreach ($report->result as $key=>$value) { 
						//echo Debug::vars('83', $key, $value);exit;
						$workTimeStart=Arr::get($value, 'timeStartNormative');
						$workTimeEnd=Arr::get($value, 'timeEndNormative');
						$duration_day=Arr::get($value, 'timeLongWorkDayNormative');
						
					?>
					<tr>
						
						<td><?php echo ++$count; ?></td>
						<td><?php echo Arr::get($value, 'date'); ?></td>
						<!--<td><?php echo HTML::anchor('' . $report->id_pep, $report->id_pep); ?></td>
						<td><?php echo HTML::anchor('' . $pep->id_org, $pep->id_org); ?></td> 
						<td><?php echo iconv('CP1251', 'UTF-8', $pep->surname).' '.iconv('CP1251', 'UTF-8', $pep->name).' '.iconv('CP1251', 'UTF-8', $pep->patronymic); ?></td> -->
						<td><?php 
							$var=Arr::get($value, 'time_in');
							echo floor($var/3600).':'
								.str_pad(floor($var%3600/60),2, 0,STR_PAD_LEFT).':'
								.str_pad(($var%3600)%60,2, 0,STR_PAD_LEFT); ?>
						</td>
						
						<td><?php 
							$var=Arr::get($value, 'time_out');
							echo floor($var/3600).':'
								.str_pad(floor($var%3600/60),2, 0,STR_PAD_LEFT).':'
								.str_pad(($var%3600)%60,2, 0,STR_PAD_LEFT); ?>
						</td>
						
						<td><?php 
							$var=Arr::get($value, 'time_out') - Arr::get($value, 'time_in');
							echo floor($var/3600).':'
								.str_pad(floor($var%3600/60),2, 0,STR_PAD_LEFT).':'
								.str_pad(($var%3600)%60,2, 0,STR_PAD_LEFT); ?>
						</td>
						
						
						
						<td><?php 
							$var=Arr::get($value, 'time_startCount');
							echo floor($var/3600).':'
								.str_pad(floor($var%3600/60),2, 0,STR_PAD_LEFT).':'
								.str_pad(($var%3600)%60,2, 0,STR_PAD_LEFT); ?>
						</td>
						
						<td><?php 
							$var=Arr::get($value, 'time_endtCount');
							echo floor($var/3600).':'
								.str_pad(floor($var%3600/60),2, 0,STR_PAD_LEFT).':'
								.str_pad(($var%3600)%60,2, 0,STR_PAD_LEFT); ?>
						</td>
						
						
						<td><?php 
							$var=Arr::get($value, 'time_endtCount') - Arr::get($value, 'time_startCount');
							echo floor($var/3600).':'
								.str_pad(floor($var%3600/60),2, 0,STR_PAD_LEFT).':'
								.str_pad(($var%3600)%60,2, 0,STR_PAD_LEFT); ?>
						</td>
						
						<td><?php 
							$var=Arr::get($value, 'timeEndNormative') - Arr::get($value, 'timeStartNormative');
							echo floor($var/3600).':'
								.str_pad(floor($var%3600/60),2, 0,STR_PAD_LEFT).':'
								.str_pad(($var%3600)%60,2, 0,STR_PAD_LEFT); ?>
						</td>
						
						<td><?php 
							$var=Arr::get($value, 'timeEndNormative') - Arr::get($value, 'timeStartNormative') - (Arr::get($value, 'time_endtCount') - Arr::get($value, 'time_startCount'));
							echo floor($var/3600).':'
								.str_pad(floor($var%3600/60),2, 0,STR_PAD_LEFT).':'
								.str_pad(($var%3600)%60,2, 0,STR_PAD_LEFT); ?>
						</td>
						
							
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
