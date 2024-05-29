<?php
/*
29.05.2024 Панель с кнопками экспорт.
Панель формируется на основе тут же формируемого массива.
'class'=>'left_switch'
'class'=>'middle_switch',
'class'=>'right_switch',
*/

	$listButton=array('cvs', 'xlsx', 'pdf');

$battArray=array(
	'cvs'=>array(
			'anchor'=>'reports/cvs',
			'messOnbatton'=>__('export.cvs'),
			'class'=>'left_switch',
			'disabled'=>'disabled',		
			'tittle'=>'Экспорт в CVS',		
					
	),
	'xlsx'=>array(
			'anchor'=>'reports/xlsx',
			'messOnbatton'=>__('export.xlsx'),
			'class'=>'right_switch',
			'disabled'=>'disabled',	
			'tittle'=>'Экспорт в XLSX',			
	),
	
	'pdf'=>array(
				'anchor'=>'reports/pdf',
				'messOnbatton'=>__('export.pdf'),
				'class'=>'right_switch',
				'disabled'=>'disabled',	
				'tittle'=>'Экспорт в PDF',			
	),
	
	
	
);

?>
	
	<div class="switch">
			<table cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
				<?php 
					foreach($listButton as $key)
					{
						$value=Arr::get($battArray, $key);
					echo '<td>';
					$isActive='';
					if(isset($group)){
							if($group==$key) $isActive =' active' ;
					} else {
						if($key=='controlConfig') $isActive =' active' ;
					}
								echo HTML::anchor(Arr::get($value,'anchor'), Arr::get($value,'messOnbatton'), array('class' => Arr::get($value,'class').$isActive, 'disabled'=>Arr::get($value,'disabled'), 'title'=>Arr::get($value,'tittle'))); 
							echo '</td>';
					
						
					}
				?>
					<tr>
			</tbody>
			</table>
		</div>

