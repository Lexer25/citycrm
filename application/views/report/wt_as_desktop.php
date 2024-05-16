<script type="text/javascript">


 
  	$(function() {		
  		$("#tablesorter").tablesorter();
		
  	});	
	
</script>
<?php 
$timestart=microtime(true);
//echo Debug::vars('13', $report);
$pep=new Contact($report->id_pep);
$org=new Company($pep->id_org);
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
		<?php echo $topbuttonbar;?>
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
				echo Form::hidden('forsave', serialize ($report)); 
				echo Form::hidden('todo', 'savecvs'); 
				echo Form::submit(NULL, __('button.savexlsx'));
				echo Form::close();
				
				echo Form::open('reports/savepdf');
				echo Form::hidden('id_pep', $pep->id_pep); 
				echo Form::hidden('forsave', serialize ($report)); 
				echo Form::hidden('todo', 'savecvs'); 
				echo Form::submit(NULL, __('button.savepdf'));
				echo Form::close();
				
				
		?>
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
					<?php 
					foreach ($report->colimnTitle as $key=>$value)
					{
						echo '<th>'.$value.'</th>';
					}
					?>					
					</tr>
					</thead>
					<tr align="center">
					<?php
						$cc=1;
						foreach ($report->colimnTitle as $key)
						{
							echo '<td>'.$cc++.'</td>';
						}
						
					?>
						
					</tr>
					
				
				<tbody>
					<?php foreach ($report->result as $key=>$value) { 
						//echo Debug::vars('83', $key, $value);//exit;
						$workTimeStart=Arr::get($value, 'timeStartNormative');
						$workTimeEnd=Arr::get($value, 'timeEndNormative');
						$duration_day=Arr::get($value, 'timeLongWorkDayNormative');
						
					?>
					<tr>
						
						<td><?php echo Arr::get($value, 'date'); ?></td>
						<td><?php echo iconv('CP1251', 'UTF-8',$org->name); ?></td>
						<td><?php echo iconv('CP1251', 'UTF-8',$pep->surname).' '.iconv('CP1251', 'UTF-8',$pep->name).' '.iconv('CP1251', 'UTF-8',$pep->patronymic); ?></td>
						<td><?php 
							$var=Arr::get($value, 'time_in');
							echo floor($var/3600).':'
								.str_pad(floor($var%3600/60),2, 0,STR_PAD_LEFT).':'
								.str_pad(($var%3600)%60,2, 0,STR_PAD_LEFT); ?>
						</td>
						
											
						<td><?php 
							
							//Опоздал
							
							
							$var=Arr::get($value, 'lateness');
							echo floor($var/3600).':'
								.str_pad(floor($var%3600/60),2, 0,STR_PAD_LEFT).':'
								.str_pad(($var%3600)%60,2, 0,STR_PAD_LEFT);
								
								
								?>
						</td>
						
						<td><?php
							// ушел						
							$var=Arr::get($value, 'time_out');
							echo floor($var/3600).':'
								.str_pad(floor($var%3600/60),2, 0,STR_PAD_LEFT).':'
								.str_pad(($var%3600)%60,2, 0,STR_PAD_LEFT); ?>
						</td>
						
						
						<td><?php 
						// недоработал
						//deviation показывать время, если был на работе меньше нормативного
					
						$var=Arr::get($value, 'deviation');
						echo floor($var/3600).':'
								.str_pad(floor($var%3600/60),2, 0,STR_PAD_LEFT).':'
								.str_pad(($var%3600)%60,2, 0,STR_PAD_LEFT); 
								
								
								?>
						</td>
						
						<td><?php 
						//пробыл на работе
													
							$var=Arr::get($value, 'time_work');
							echo floor($var/3600).':'
								.str_pad(floor($var%3600/60),2, 0,STR_PAD_LEFT).':'
								.str_pad(($var%3600)%60,2, 0,STR_PAD_LEFT); 
								
								
								?>
						</td>
						
						
					
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<br>
		
			<div id="chart_wrapper" class="chart_wrapper"></div>
		<!-- End bar chart table-->
		</form>
		<?php 
		 } else { ?>
		<div style="margin: 100px 0; text-align: center;">
			<?php echo __('report.empty'); ?><br /><br />
		</div>
		<?php }
		echo __('Time executed').' '. (microtime(true) - $timestart);
			
		
		?>
	</div>
</div>
