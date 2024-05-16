<?php 
/**
*заголовок для отчетов
*@input id_pep
*@input id_pep_admin
*
*/
//echo Debug::vars('8-8'); exit;
?>
<p3>Заголовок отчета 1</p3>
	<table class="data tablesorter-blue"  width="100%" cellpadding="0" cellspacing="0" id="reportHeader" >
	<tr>
		<td><?php echo HTML::image('static/images/kohana.png', array('alt' => 'My Company'));?>;</td>
		<td colspan="3">Перечень точек прохода сотрудника</td>
		
	</tr>
	<tr>
		<td>Организация</td>
		<td>ООО "Рик"</td>
		<td>Отчет подготовлен</td>
		<td>Сидоров М.Ю.</td>
	</tr>
		<tr>
		<td>Отдел</td>
		<td>Поизводство картофеля кубического КаКуб</td>
		<td>Отчет подготовлен</td>
		<td><?php echo date('d.m.Y H:i:s');?></td>
	</tr>
		<tr>
		<td>Фамилия</td>
		<td>Иванов Федор Иванович</td>
		<td>3</td>
		<td>4</td>
	</tr>
	</table>
	<p3>Заголовок завершен</p3>