<div class="onecolumn">
	<div class="header">
		<span><?php echo __('contact.history') . ' - ' . iconv('CP1251', 'UTF-8', $contact['NAME'] . ' ' . $contact['SURNAME']); ?></span>
<?php
	echo $topbuttonbar;
    //echo Debug::vars('6', ::get('reportdatestart'));
    //echo Debug::vars('6', ::get('reportdateend'));
    //echo Debug::vars('6', $data); exit;

	?>
	</div>
	<br class="clear" />
    <div class="content">
        <form action="contacts/gethistory" method="post" onsubmit="return validate()">

            <table cellspacing="5" cellpadding="5">
                <tbody>
                <tr>
                    <th align="right" style="padding-right: 10px;">
                        <label for="reportdatestart"><?php echo __('report.datestart'); ?></label>
                    </th>
                    <td>
                        <div style="padding-bottom: 10px;">

                            <input type="text" size="12" name="reportdatestart" id="carddatestart" value="<?php
                            echo Cookie::get('reportdatestart', date('d.m.Y'));														?>" />
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
                            echo Cookie::get('reportdateend', date('d.m.Y'));														?>" />
                            <br />
                            <span class="error" id="error3" style="color: red; display: none;"><?php echo __('report.wrongendtime'); ?></span>
                        </div>
                    </td>
                </tr>


                </tbody>
            </table>

            <?php

            
            echo Form::hidden('id_pep', $contact['ID_PEP']);
            
            echo Form::hidden('todo', 'wtOncePep');
            echo Form::submit(NULL, __('button.reportEvents'));
            echo Form::close();
            echo __('event.availableEventPeriod', array(':eventFromDate'=>$about->eventFromDate,':eventToDate'=>$about->eventToDate, ':countEvent'=>count($data)));
			
            ?>


    </div>

	<div class="content">


		<?php 
		//echo Debug::vars('33 check', $exportbuttonbar);
		echo Form::open('reports/saveEvents');
		$forsave=array();

		//echo Form::submit('savecvs', __('button.savecsv'));
		//echo Form::submit('savexlsx', __('button.savexlsx'));
		echo Form::submit('savepdf', __('button.savepdf'));
		echo Form::hidden('dateFrom', Cookie::get('reportdatestart', date('d.m.Y')));
            echo Form::hidden('dateTo', Cookie::get('reportdateend', date('d.m.Y')));
		
		$titleTH=array();
		//$titleTH[]= __('history.id_event');
		$titleTH[]= __('history.date');
		$titleTH[]= __('history.device');
		$titleTH[]= __('history.event');
		$titleTH[]= __('history.any');
		echo Form::hidden('titleTH', serialize($titleTH));
		echo Form::hidden('id_pep', Arr::get($contact, 'ID_PEP'));
		
		if (count($data) > 0) { ?>
		<table class="data" width="100%" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
							
					<?php
					
					foreach($titleTH as $key=>$value)
					{
					    //echo Debug::vars('81', $key, $value); exit;
					    echo '<th>'. $value.'</th>'; 
					}
					
					?>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($data as $h) {
                    //echo Debug::vars('81', $data, $h); exit;
                    $key=new keyk($h['ID_CARD']);
                    $event=new EventMonitor($h['ID_EVENT']);
					//echo Debug::vars('109',$event );exit;
                   
				echo '<tr>';
					//echo '<td>'. $h['ID_EVENT'].'</td>';
					echo '<td>'. Arr::get($h,'DATETIME').'/'.$event->id_event.'</td>';
					echo '<td>'. iconv('CP1251', 'UTF-8', Arr::get($h, 'DEVICENAME', iconv('UTF-8','CP1251', 'База данных'))).'</td>';
					echo '<td>'. iconv('CP1251', 'UTF-8',$event->name).' ('.$event->eventCode.')</td>';
					echo '<td>'. iconv('CP1251', 'UTF-8', Arr::get($h, 'NOTE')).' '.$event->note.'</td>';
	
					 
                   //$forsave[Arr::get($h, 'ID_EVENT')][]=$h['ID_EVENT'];
                   $forsave[Arr::get($h, 'ID_EVENT')][]=Arr::get($h,'DATETIME');
                   $forsave[Arr::get($h, 'ID_EVENT')][]=iconv('CP1251', 'UTF-8', Arr::get($h, 'DEVICENAME', iconv('UTF-8','CP1251', 'База данных')));
                   $forsave[Arr::get($h, 'ID_EVENT')][]=iconv('CP1251', 'UTF-8', Arr::get($h, 'EVENTNAME'));
                   $forsave[Arr::get($h, 'ID_EVENT')][]=iconv('CP1251', 'UTF-8', Arr::get($h, 'NOTE')).' '.$event->note;
					
				echo '</tr>';
				} ?>
			</tbody>
		</table>
		<?php } else { ?>
		<div style="margin: 100px 0; text-align: center;">
			<?php echo __('history.empty'); ?><br /><br />
		</div>
		<?php } ?>
			<?php 	
			//echo Debug::vars('129',serialize($forsave));exit;
			echo Form::hidden('forsave', serialize($forsave));
			
			
			echo Form::close();
			?>
	</div>
</div>
