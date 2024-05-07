<?php 
/**
*форма вывода в отчет
*@input id_pep
*@input id_pep_admin
*
*/
//echo Debug::vars('8', $dataForSave); exit;
?>
	<table class="data tablesorter-blue" width="60%" cellpadding="0" cellspacing="0" id="tablesorter" >
			<tbody>
				<tr>
					<th>№</th>
					<th>id двери</th>
					<th>Название двери</th>
									
				</tr>
				<?php
				$i=0;
			foreach($dataForSave as $key=>$value){	
				
				echo '<tr>';
					echo '<td>'.Arr::get($value, 'sn').'</td>';
					echo '<td>'.Arr::get($value, 'id_door').'</td>';
					//echo '<td>'.Arr::get($value, 'name').'</td>';
					echo '<td>'. iconv('CP1251', 'UTF-8',Arr::get($value, 'name')).'</td>';//тут надо название двери

					
				echo '</tr>';
			}
				?>
			</tbody>
			</table>