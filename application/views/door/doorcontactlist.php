<?php

//echo Debug::vars('3', $id_door, $enable_card_list, $keys); exit;
$door=new Door($id_door);
?>
<script type="text/javascript">


 
  	$(function() {		
  		$("#tablesorter").tablesorter({ headers: { 7:{sorter: false}},  widgets: ['zebra']});
		
  	});	
	
</script>

<style>
.tree{
  --spacing : 1.5rem;
  --radius  : 10px;
}
.tree li{
  display      : block;
  position     : relative;
  padding-left : calc(2 * var(--spacing) - var(--radius) - 2px);
}

.tree ul{
  margin-left  : calc(var(--radius) - var(--spacing));
  padding-left : 0;
}

</style>
<?php if (isset($alert)) { ?>
<div class="alert_success">
	<p>
		<img class="mid_align" alt="success" src="images/icon_accept.png" />
		<?php echo $alert; ?>
	</p>
</div>
<?php } ?>
<div class="onecolumn">
	<div class="header">

		<span><?php echo __('doors.title').' '.iconv('windows-1251','UTF-8',$door->name); ?></span>
		<?php 
			echo $topbuttonbar;
		?>
	</div>


	<br class="clear"/>
	<div class="content">
	<?php
	echo Form::open('doors/export');
				echo __('doors.KeyCount :count', array(':count'=>count($enable_card_list))).'<br>';
				echo Form::hidden('id_door', $door->id ); 
		
				//echo Form::submit('savecvs', __('button.savecsv'), array('disabled'=>'disabled'));
	
				//echo Form::submit('savexls', __('button.savexlsx') , array('disabled'=>'disabled'));
	
				echo Form::submit('savepdf', __('button.savepdf'));
	
		
				//echo Form::hidden('doorList', serialize($doorAll)); 
			//echo Debug::vars('314', $outDoorList);exit;
				//echo Form::hidden('outDoorList', iconv('CP1251', 'UTF-8', serialize($outDoorList))); 
				
				
			
			?>

			<table class="data tablesorter-blue" width="100%" cellpadding="0" cellspacing="0" id="tablesorter" >
				<thead>
					<tr>
						<!--
						<th style="width:10px">
							<input type="checkbox" id="check_all" name="check_all"/>
						</th>
						-->
						<?php

						//echo '<th>' . __('npp') . '</th>';
						//echo '<th>' . __('id_pep') . '</th>';
						
						echo '<th>' . __('contact.name') . '</th>';
					
						echo '<th>' . __('contact.card') . '</th>';
						echo '<th>' . __('contact.load_time') . '</th>';
						echo '<th>' . __('contact.load_result') . '</th>';
						?>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i=1;
					foreach ($keys as $key=>$value) 
					{ 
					
					$card=new Keyk(Arr::get($value, 'ID_CARD'));
					$contact=new Contact($card->id_pep);
					?>
						<tr>
						
							<?php 
							//$door=new Door($device);
							//echo Debug::vars('78', $device, $door);//exit;
							//echo '<td align="center">' . Debug::vars('79', $value, $card) . '</td>';
							//echo '<td align="center">' . $i . '</td>';
							//echo '<td align="center">' . $contact->id_pep. '</td>';
							echo '<td align="center">' . HTML::anchor('contacts/edit/'.$contact->id_pep, iconv('windows-1251','UTF-8', $contact->surname.' '.$contact->name.' '.$contact->patronymic)) . '</td>';
							echo '<td align="center">' . HTML::anchor('cards/edit/'.Arr::get($value, 'ID_CARD'), $card->id_card_on_screen). '</td>';
							echo '<td align="center">' . Arr::get($value, 'LOAD_TIME') . '</td>';
							echo '<td align="center">' . Arr::get($value, 'LOAD_RESULT') . '</td>';
							$forsave[$contact->id_pep]['surname']=iconv('windows-1251','UTF-8', $contact->surname);
							$forsave[$contact->id_pep]['name']=iconv('windows-1251','UTF-8', $contact->name);
							$forsave[$contact->id_pep]['patronymic']=iconv('windows-1251','UTF-8', $contact->patronymic);
						
							?>
						</tr>
					<?php $i++;
					} ?>
				</tbody>
			</table>
			<div id="chart_wrapper" class="chart_wrapper"></div>
		<!-- End bar chart table-->

		<?php 	
			//echo Debug::vars('129',$forsave);exit;
			echo Form::hidden('forsave', serialize($forsave));
			echo Form::close();
			?>

	</div>
</div>
