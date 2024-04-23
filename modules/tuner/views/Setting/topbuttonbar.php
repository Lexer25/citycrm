<?php
/*
23.04.2024 Панель с кнопками в разделе настроек.
Панель формируется на основе тут же формируемого массива.

*/
$listButton=array('main', 'system', 'controlConfig' );
$battArray=array(
	'main'=>array(
			'anchor'=>'settings/main',
			'messOnbatton'=>__('setting.mainConfig'),
			'class'=>'left_switch',
			'disabled'=>'disabled',		
			'tittle'=>'Главные настройки системы',		
					
	),
	'system'=>array(
			'anchor'=>'settings/keyFormatConfig',
			'messOnbatton'=>__('setting.keyFormatConfig'),
			'class'=>'middle_switch',
			'disabled'=>'disabled',	
			'tittle'=>'Настройки форматов и их отображений',			
	),
	
	'controlConfig'=>array(
				'anchor'=>'settings/list',
				'messOnbatton'=>__('setting.listConfig'),
				'class'=>'right_switch',
				'disabled'=>'disabled',	
				'tittle'=>'Управление всеми конфигурациями',			
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

