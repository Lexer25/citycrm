<?php
/*
Эта страница выводит список категорий доступа для указаного пипла
*/
?>
<script language="javascript">
	function validate()
	{
		$('.error').hide();
		if ($('#surname').val() == '') {
			$('#error1').show();
			$('#surname').focus();
			return false;
		}
		var ymd = $('#datebirth').val();
		if (ymd == '') {
			$('#error21').show();
			return false;
		}
		if (!ymd.match(/^\d{4}-\d{2}-\d{2}$/)) {
			$('#error22').show();
			return false;
		}
		ymd = ymd.split('-');
		if (ymd[1] > 12 || ymd[1] < 1 || ymd[2] > 31 || ymd[2] < 1) {
			$('#error23').show();
			return false;
		}
		ymd = $('#datedoc').val(); 
		if (ymd == '') {
			$('#error31').show();
			return false;
		}
		if (!ymd.match(/^\d{4}-\d{2}-\d{2}$/)) {
			$('#error32').show();
			return false;
		}
		ymd = ymd.split('-');
		if (ymd[1] > 12 || ymd[1] < 1 || ymd[2] > 31 || ymd[2] < 1) {
			$('#error33').show();
			return false;
		}
		var hm = $('#workstart').val();
		if (hm == '') {
			$('#error41').show();
			$('#workstart').focus();
			return false;
		}
		if (!hm.match(/^\d{2}:\d{2}$/) && !hm.match(/^\d{2}:\d{2}:\d{2}$/)) {
			$('#error42').show();
			$('#workstart').focus();
			return false;
		}
		hm = hm.split(':');
		if (hm[0] > 23 || hm[1] > 59 || (hm.length == 3 && hm[2] > 59)) {
			$('#error43').show();
			$('#workstart').focus();
			return false;
		}
		hm = $('#workend').val();
		if (hm == '') {
			$('#error51').show();
			$('#workend').focus();
			return false;
		}
		if (!hm.match(/^\d{2}:\d{2}$/) && !hm.match(/^\d{2}:\d{2}:\d{2}$/)) {
			$('#error52').show();
			$('#workend').focus();
			return false;
		}
		hm = hm.split(':');
		if (hm[0] > 23 || hm[1] > 59 || (hm.length == 3 && hm[2] > 59)) {
			$('#error53').show();
			$('#workstart').focus();
			return false;
		}
		if ($('#tabnum').val() == '') {
			$('#error6').show();
			$('#tabnum').focus();
			return false;
		} else if ($('#login').val() == '') {
			$('#error7').show();
			$('#login').focus();
			return false;
		} else if ($('#password').val() == '') {
			$('#error8').show();
			$('#password').focus();
			return false;
		}
	}
</script>
<?php 
//echo Debug::vars('89', $contact);
//echo Debug::vars('90', $contact_acl);
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
		<span>
		
		<?php 
			
			switch($mode) {
				case('new'):
					;
				break;
				
				case('edit'):
					echo __('contact.titleEditContact', array( 
					':name'=> iconv('CP1251', 'UTF-8', $contact->name),
					':surname'=> iconv('CP1251', 'UTF-8', $contact->surname),
					':patronymic'=> iconv('CP1251', 'UTF-8', $contact->patronymic)));
				break;
				
				case('fired'):
					echo __('contact.titlefiredContact', array( 
					':name'=> iconv('CP1251', 'UTF-8', $contact->name),
					':surname'=> iconv('CP1251', 'UTF-8', $contact->surname),
					':patronymic'=> iconv('CP1251', 'UTF-8', $contact->patronymic)));
				break;
				default:
					echo __('form.editContact');
				break;
			}
				
				
				?>
		
		</span>
		<?php if ($contact) { ?>
		<div class="switch">
			<table cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td>
						<?php echo HTML::anchor('contacts/edit/' . $contact->id_pep, __('contact.common'), array('class' => 'left_switch')); ?>
					</td>
					<td>
						<a href="javascript:" class="middle_switch active"><?php echo __('contact.acl'); ?></a>
					</td>
					<td>
						<?php echo HTML::anchor('contacts/cardlist/' . $contact->id_pep, __('contact.cardlist'), array('class' => 'middle_switch')); ?>
					</td>
					<td>
						<?php echo HTML::anchor('contacts/history/' . $contact->id_pep, __('contact.history'), array('class' => 'right_switch')); ?>
					</td>
				</tr>
			</tbody>
			</table>
		</div>
		<?php } ?>
	</div>
	<br class="clear" />
	<div class="content">
		<form action="contacts/saveACL" method="post" onsubmit="return validate()">
			<input type="hidden" name="hidden" value="form_sent" />
			<input type="hidden" name="id" value="<?php echo $contact->id_pep; ?>" />
		
		<div style="padding-left: 15px">
		
		Категории доступа<br>	
		<div style="padding-left: 15px">
		
		<?php
			$column=4;// количество колонок в таблице
			$accessNameList=AccessName::getList();
			$row= ceil(count($accessNameList)/$column);
			$aaa=array_chunk($accessNameList, ceil(count($accessNameList)/$column));
			//echo Debug::vars('145', ceil (101/4), $aaa); exit;
			
			//echo count($accessNameList);
			$res=array();
				foreach($contact_acl as $key=>$value)
				{
					$res[]=Arr::get($value, 'ID_ACCESSNAME');
				}
		
		//echo Debug::vars('186', Arr::get($aaa, 0)); exit
		?>
		
		
					<div>
					<table>
						<tr>
 
														  
		  
						
						<?php
						/*
						вывод категорий доступа вертикальными колонками
						
						*/
						//echo Debug::vars('145', $column); //exit;
						for ($i=0; $i<$column;  $i++)
						{
							echo '<td>';
	   
							foreach (Arr::get($aaa, $i) as $key=>$value)
							{
								echo Form::checkbox('aclList['.Arr::get($value, 'ID_ACCESSNAME').']', 1, in_array (Arr::get($value, 'ID_ACCESSNAME'), $res)).' '. iconv('CP1251', 'UTF-8', Arr::get($value, 'NAME', '')).'<br>';
							}
							echo '</td>';
						}
						?>
						</tr>
		  
		 
					</table>
					</div>
<div>
			<br />
				<?php
			if($contact->is_active) {
			?>
			<input type="submit" value="<?php echo __('button.save'); ?>" />
			<input type="button" value="<?php echo __('button.cancel'); ?>" onclick="document.forms[0].reset()" />
			<input type="button" value="<?php echo __('button.backtolist'); ?>" onclick="location.href='<?php echo URL::base(); ?>contacts'" />
			<?php }?>
		</form>
	</div>
</div>
Таблица точек прохода и категорий доступа
<?php
$doorAll=array();
$outDoorList=array();//массив для вывода на печать
foreach($contact_acl as $key=>$value){
	$access=new Access(Arr::get($value,'ID_ACCESSNAME'));
	$result=$access->getDoorIdList();
	
	if( $result== 0){
		$doorList[Arr::get($value,'ID_ACCESSNAME')]=$access->dataResult;
		$doorAll=array_merge($doorAll, $access->dataResult);
		//echo Debug::vars('238', $doorList); //exit;
	} else {
		echo Debug::vars('242',$result, $access->actionDesc); exit;
	}
	
	
}
//echo Debug::vars('245',$doorList); //exit;
//echo Debug::vars('245',$doorAll); //exit;
//echo Debug::vars('249', array_values($doorAll)); exit;
// теперь строю таблицу
echo Form::open('reports/doorList');
//echo Form::open('reports/savecsv');
?>
	<table class="data tablesorter-blue" width="60%" cellpadding="0" cellspacing="0" id="tablesorter" >
			<tbody>
				<tr>
					<th>№</th>
					<th>Название двери</th>
					<?php
						foreach($contact_acl as $key=>$value){
							echo '<th>';
							$an=new Access(Arr::get($value,'ID_ACCESSNAME'));
								echo iconv('CP1251', 'UTF-8',$an->name);
							echo '</th>';
						}
					
					?>
					<th>Повтор</th>
					
				</tr>
				<?php
				$i=0;
			foreach(array_unique($doorAll) as $key=>$value){	
				$m=0;// счетчик количества повторов точки доступа в категориях
				echo '<tr>';
					echo '<td>'.++$i.'</td>';
					$door=new Door($value);
					echo '<td>'. iconv('CP1251', 'UTF-8',$door->name).' ('.$door->id.')</td>';//тут надо название двери
					
					$outDoorList[]=array('sn'=>$i, 'id_door'=>$door->id, 'name'=>$door->name);
					foreach($contact_acl as $key=>$value){
						echo '<td>';
							
							//echo Debug::vars(in_array($door->id, Arr::get($doorList,Arr::get($value,'ID_ACCESSNAME'))));
							if(in_array($door->id, Arr::get($doorList,Arr::get($value,'ID_ACCESSNAME')))){
								echo HTML::image('images\icon_accept.png');
								$m++;
							} else {
								echo HTML::image('images\icon_delete.png');
					}
						echo '</td>';
						
					}
					echo '<td>';
					//echo HTML::image('images\icon_delete.png');	
						if($m>1) echo HTML::image('images\icon_warning.png');
					
				echo '</tr>';
			}
				?>
			</tbody>
			</table>
			<?php
				echo Form::hidden('id_pep', $contact->id_pep); 
		
				echo Form::submit('savecvs', __('button.savecsv'));
	
				echo Form::submit('savexls', __('button.savexlsx'));
	
				echo Form::submit('savepdf', __('button.savepdf'));
	
		
				echo Form::hidden('doorList', serialize($doorAll)); 
			//echo Debug::vars('314', $outDoorList);exit;
				echo Form::hidden('outDoorList', iconv('CP1251', 'UTF-8', serialize($outDoorList))); 
				
				
			echo Form::close();
			?>
</div>
			