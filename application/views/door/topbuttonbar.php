<?php
/*
20.05.2024 Панель с кнопками в разделе Точки прохода.
Панель формируется на основе тут же формируемого массива.
недостающие параметры передаются из контроллера
left_switch
middle_switch
right_switch
*/

if(!isset($id_door)) $id_door=0;
$battArray=array(
	'view'=>array(
			'anchor'=>'doors/doorinfo/' . $id_door,
			'messOnbatton'=>__('doors.common'),
			'class'=>'left_switch',
			'disabled'=>'disabled',		
			'tittle'=>'Свойства контакта',		
					
	),
		
	'doorcontactlist'=>array(
				'anchor'=>'doors/doorcontactlist/' . $id_door,
				'messOnbatton'=>__('doors.contactlist'),
				'class'=>'right_switch',
				'disabled'=>'disabled',	
				'tittle'=>'Управление всеми конфигурациями',			
	),
	
		
	/* 'history'=>array(
				'anchor'=>'doors/history/' . $id_door,
				'messOnbatton'=>__('dors.history'),
				'class'=>'left_switch',
				'disabled'=>'disabled',	
				'tittle'=>'Журнал событий',			
	),
	
	'test'=>array(
				'anchor'=>'',
				'messOnbatton'=>__('doors.test'),
				'class'=>'right_switch',
				'disabled'=>'disabled',	
				'tittle'=>'test',			
	), */
	
	
	
);

?>
	
	<div class="switch">
			<table cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
				<?php 
					foreach($battArray as $key=>$value)
					{
	
					echo '<td>';
					$isActive='';
					
						if($key==$_is_active) $isActive =' active' ;// если параметр $is_active совпадает с названием кнопки, то она будет отмечена как выделенная
						//echo Debug::vars('76', $key, $_is_active, $isActive);exit;
				
								echo HTML::anchor(Arr::get($value,'anchor'), Arr::get($value,'messOnbatton'), array('class' => Arr::get($value,'class').$isActive, 'disabled'=>Arr::get($value,'disabled'), 'title'=>Arr::get($value,'tittle'))); 
							echo '</td>';
					
						
					}
				?>
					<tr>
			</tbody>
			</table>
		</div>

