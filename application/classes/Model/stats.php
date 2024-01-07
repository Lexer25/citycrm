<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Stats extends Model
{
	
	

	public function getList()
	{
		$list=array();
		
		$query = DB::query(Database::SELECT, 'select count(*) from people')
				->execute(Database::instance('fb'))
				->current();
		$list['people_count']=$query['COUNT'];
		
		$list['event_err_count']=Model::Factory('eventlog')->getCountAdminAlarm(24);
		
		$list['event_card_count']=Model::Factory('eventlog')->getCountEvent(24);
		
		
		$query = DB::query(Database::SELECT, 'select count(*) from people where people."ACTIVE"=0')
				->execute(Database::instance('fb'))
				->current();
		$list['people_count_na']=$query['COUNT'];
		
		
		$query = DB::query(Database::SELECT, 'select count(*) from card')
				->execute(Database::instance('fb'))
				->current();
		$list['card_count']=$query['COUNT'];
		
		$query = DB::query(Database::SELECT, 'select count(*) from  accessname')
				->execute(Database::instance('fb'))
				->current();
		$list['accessname_count']=$query['COUNT'];
		
		$query = DB::query(Database::SELECT, 'select count(*) from  device where device.id_reader is null')
				->execute(Database::instance('fb'))
				->current();
		$list['device_count']=$query['COUNT'];
		
		$query = DB::query(Database::SELECT, 'select count(*) from  device where device.id_reader is not null')
				->execute(Database::instance('fb'))
				->current();
		$list['door_count']=$query['COUNT'];
		
		$query = DB::query(Database::SELECT, 'select count(*) from events where  events.datetime >\'now\'')
				->execute(Database::instance('fb'))
				->current();
		$list['event_in_future']=$query['COUNT'];
		
		$query = DB::query(Database::SELECT, 'select count(*) from cardidx ci where ci.id_card=\'0000000000\'')
				->execute(Database::instance('fb'))
				->current();
		$list['card_as_null']=$query['COUNT'];
		
		return $list;
	}

	public function controller($filter = null) // info about card in controller
	{
	$sql='select cardidx.id_dev as id_door, device.name as door_name, count(cardidx.id_card) as door_card_count
		from cardidx
		join device on device.id_dev=cardidx.id_dev
		group by cardidx.id_dev, device.name';
	$query = DB::query(Database::SELECT, $sql)
				->execute(Database::instance('fb'))
				->as_array();
		
		return $query;
	}
	
	public function events ($filter = null) // info about card count in controller
	{
		$date_calc=date('d.m.Y H:i:s', mktime(date('H'),date('i'), date('s'),date('m'),date('d'), date('Y'))-24*3600);
				
		$sql='select d.id_dev as ID_DOOR, d.name as DOOR_NAME  from device d where device.id_reader is not null';
		$device_list = DB::query(Database::SELECT, $sql)
					->execute(Database::instance('fb'))
					->as_array();
		
		$sql='select e.id_dev as ID_DOOR, count(e.id_event) as DOOR_EVENT_COUNT from events e
			join device d on d.id_dev=e.id_dev
			where e.datetime>\''.$date_calc.'\' and e.datetime<\'now\' 
				and e.id_dev in (select d.id_dev from device d where device.id_reader is not null)
			and e.id_eventtype in (50,65,46)
			group by e.id_dev, d.name';
		//Kohana::$log->add(Kohana::ERROR, $sql);
		$query = DB::query(Database::SELECT, $sql)
					->execute(Database::instance('fb'))
					->as_array();
			
		$event_all= $query; //массив со всеми событиями по карте.
		
		$sql='select e.id_dev as ID_DOOR, count(e.id_event) as DOOR_EVENT_COUNT from events e
			join device d on d.id_dev=e.id_dev
			where e.datetime>\''.$date_calc.'\' and e.datetime<\'now\' 
				and e.id_dev in (select d.id_dev from device d where device.id_reader is not null)
			and e.id_eventtype in (65)
			group by e.id_dev, d.name';
		//Kohana::$log->add(Kohana::ERROR, $sql);
		$query = DB::query(Database::SELECT, $sql)
					->execute(Database::instance('fb'))
					->as_array();
			
			$event_alarm= $query; //массив с тревожными событиями по карте.
			
		$data=array();
		
		foreach ($device_list as $value) { 
				
				$data[$value['ID_DOOR']]['id_door']=$value['ID_DOOR'];
				$data[$value['ID_DOOR']]['door_name']=$value['DOOR_NAME'];
				$data[$value['ID_DOOR']]['count_all']=0;
				$data[$value['ID_DOOR']]['count_alarm']=0;
				$data[$value['ID_DOOR']]['proc_err']=0;
					foreach ($event_all as $arr){
						if($arr['ID_DOOR']==$value['ID_DOOR']) $data[$value['ID_DOOR']]['count_all']=$arr['DOOR_EVENT_COUNT'];
					};
					foreach ($event_alarm as $arr){
						if($arr['ID_DOOR']==$value['ID_DOOR']) $data[$value['ID_DOOR']]['count_alarm']=$arr['DOOR_EVENT_COUNT'];
					};
				if($data[$value['ID_DOOR']]['count_all']>0 and $data[$value['ID_DOOR']]['count_alarm']>0) $data[$value['ID_DOOR']]['proc_err']=round($data[$value['ID_DOOR']]['count_alarm']/$data[$value['ID_DOOR']]['count_all'],2);
				};
				
	return $data;
	}
	
	
	
	public function delete($id)
	{
		$query = DB::query(Database::DELETE,
			'DELETE FROM people WHERE id_pep = :id')
			->param(':id', $id)
			->execute(Database::instance('fb'));
	}
	
	public function makereport($html)
	{
		//$html = '<h1><a name="top"></a>mPDF</h1>h2>Basic HTML Example</h2>';
		//$html = View::capture(APPPATH.'views/stat/common_pdf.php', $data);
		$arr = array (
  'odd' => array (
    'L' => array (
      'content' => date('d.m.Y H:i'),
      'font-size' => 10,
      'font-style' => 'B',
      'font-family' => 'serif',
      'color'=>'#000000'
    ),
    'C' => array (
      'content' => '{PAGENO} из {nb}',
      'font-size' => 10,
      'font-style' => 'B',
      'font-family' => 'serif',
      'color'=>'#000000'
    ),
    'R' => array (
      'content' => __('stat.report.header2'),
      'font-size' => 10,
      'font-style' => 'B',
      'font-family' => 'serif',
      'color'=>'#000000'
    ),
    'line' => 1,
  ),
  'even' => array ()
);
		include APPPATH."/vendor/mpdf/mpdf.php";
		$mpdf=new mPDF(); 
		$mpdf->SetHeader ( __('stat.report.header1'));
		$mpdf->SetFooter('||{PAGENO} из {nb}'.__('stat.report.header2') );
		$mpdf->SetFooter($arr);
		$mpdf->WriteHTML($html);
		$mpdf->Output();
		exit;
		
	}
	public function que_mess($filter = 1) // выборка сообщений о результатах загрузки
	{
	$sql='select count(cd.id_cardindev), ci.load_result, cd.id_dev, d.name, d."ACTIVE" as is_active from cardindev cd
        join device d on d.id_dev=cd.id_dev
        join cardidx ci on ci.id_cardindev=cd.id_cardindev
        group by ci.load_result, cd.id_dev, d.name, d."ACTIVE"
        order by  cd.id_dev';
	$result = DB::query(Database::SELECT, $sql)
					->execute(Database::instance('fb'))
					->as_array();
	
	$sql='select distinct cd.id_dev, d.name, d."ACTIVE", d2."ACTIVE", count(*) from cardindev cd
        join device d on cd.id_dev=d.id_dev
        join device d2 on d.id_dev=d2.id_dev
		where d."ACTIVE"='.$filter.'
		group by cd.id_dev, d.name, d."ACTIVE", d2."ACTIVE"
        order by cd.id_dev';	
	$list_id = DB::query(Database::SELECT, $sql)// список устройств, стоящих в очереди
					->execute(Database::instance('fb'))
					->as_array();
	
	
	//далее надо сгруппировать сообщения для каждого устройства
	$aaa=array();//результирующий массив для вывода.
	
	foreach ($list_id as $id_dev){
					
				$aaa[$id_dev['ID_DEV']]['COUNT']=$id_dev['COUNT'];
				$aaa[$id_dev['ID_DEV']]['LOAD_RESULT']='';
				$aaa[$id_dev['ID_DEV']]['NAME']=$id_dev['NAME'];
				$aaa[$id_dev['ID_DEV']]['IS_ACTIVE']='';
				$aaa[$id_dev['ID_DEV']]['ID_DEV']=$id_dev['ID_DEV'];;
			foreach ($result as $key) {
					if ($key['ID_DEV']==$id_dev['ID_DEV']) {
						$aaa[$id_dev['ID_DEV']]['LOAD_RESULT'] .=$key['LOAD_RESULT'];
						$aaa[$id_dev['ID_DEV']]['IS_ACTIVE']=$key['IS_ACTIVE'];
						
					}
				
			}
	}
	
	//echo Kohana::Debug($aaa);
	//return  $result; //массив с данными об очереди загрузки.
	return  $aaa; //массив с данными об очереди загрузки.
	}
	
	public function que_attempt_count($filter)
	{
	
	$sql='select  cd.id_dev, d.name, count(cd.id_cardindev), max(cd.attempts), d."ACTIVE" as door_IS_ACTIVE, d2."ACTIVE" as controller_is_active from cardindev cd
        join device d on d.id_dev=cd.id_dev
        join device d2 on d.id_ctrl=d2.id_ctrl and d2.id_reader is null
	where cd.operation='.$filter.'
        group by cd.id_dev, d.name, d."ACTIVE", d2."ACTIVE"';
	$result = DB::query(Database::SELECT, $sql)
					->execute(Database::instance('fb'))
					->as_array();
	
	return  $result; //массив с данными об очереди загрузки.
	}
	public function save ($data_for_save)
	{
		$fp = fopen("counter_".date('Y')."_".date('m')."_".date('d')."_".date('H')."_".date('i').".html", "a"); // Открываем файл в режиме записи
		$mytext = "Это строку необходимо нам записать\r\n"; // Исходная строка
		$test = fwrite($fp, $data_for_save); // Запись в файл
		if ($test) echo 'Данные в файл успешно занесены.';
		else echo 'Ошибка при записи в файл.';
		fclose($fp); //Закрытие файла
	
	}
	
	public function getLocalFileInfo($file) //информация о файле
	{
	$lfi['filemtime']=date('j F Y H:i', filemtime($file));
	$lfi['stat']=stat ($file);
	return $lfi;
	}
	
	public function getProductVersion($file_name)
	{
	   $key = "P\x00r\x00o\x00d\x00u\x00c\x00t\x00V\x00e\x00r\x00s\x00i\x00o\x00n\x00\x00\x00";
	   $fptr = fopen($file_name, "rb");
	   $data = "";
	   while (!feof($fptr))
	   {
		  $data .= fread($fptr, 65536);
		  if (strpos($data, $key)!==FALSE)
			 break;
		  $data = substr($data, strlen($data)-strlen($key));
	   }
	   fclose($fptr);
	   if (strpos($data, $key)===FALSE)
		  return "";
	   $pos = strpos($data, $key)+strlen($key);
	   $version = "";
	   for ($i=$pos; $data[$i]!="\x00"; $i+=2)
		  $version .= $data[$i];
	   return $version;
	}
	
	public function getApplicationInfo() {//вывод информации о приложениях системы
	$config = Kohana::$config->load('config_newcrm');
	$app_list=$config->get('app_name');
	$base_dir=$config->get('app_base_dir');
	//echo Kohana::Debug($app_list);
	foreach ($app_list as $key){
	$filename=$base_dir.'\\'.$key;
		if (file_exists($filename)){
			$result[$key]['name']=$key;
			$result[$key]['ver']=Model::Factory('stats')->GetFileVersion($filename);
			$result[$key]['size']=filesize($filename) ;
			} else {
			$result[$key]['name']=$key;
			$result[$key]['ver']=__('stat.filename_no_ver', array(':path'=>$filename));
			$result[$key]['size']=__('stat.filename_no_size') ;
			
			}
		}
	return $result;
	}
	
	
	public function GetFileVersion($FileName) {// получении версии файла
 		$handle=fopen($FileName,'rb');
		if (!$handle) return FALSE;
		$Header=fread ($handle,64);
		if (substr($Header,0,2)!='MZ') return FALSE;
		$PEOffset=unpack("V",substr($Header,60,4));
		if ($PEOffset[1]<64) return FALSE;
		fseek($handle,$PEOffset[1],SEEK_SET);
		$Header=fread ($handle,24);
		if (substr($Header,0,2)!='PE') return FALSE;
		$Machine=unpack("v",substr($Header,4,2));
		if ($Machine[1]!=332) return FALSE;
		$NoSections=unpack("v",substr($Header,6,2));
		$OptHdrSize=unpack("v",substr($Header,20,2));
		fseek($handle,$OptHdrSize[1],SEEK_CUR);
		$ResFound=FALSE;
		for ($x=0;$x<$NoSections[1];$x++) {
			$SecHdr=fread($handle,40);
			if (substr($SecHdr,0,5)=='.rsrc') {         //resource section
				$ResFound=TRUE;
				break;
			}
		}
		if (!$ResFound) return FALSE;
		$InfoVirt=unpack("V",substr($SecHdr,12,4));
		$InfoSize=unpack("V",substr($SecHdr,16,4));
		$InfoOff=unpack("V",substr($SecHdr,20,4));
		fseek($handle,$InfoOff[1],SEEK_SET);
		$Info=fread($handle,$InfoSize[1]);
		$NumDirs=unpack("v",substr($Info,14,2));
		$InfoFound=FALSE;
		for ($x=0;$x<$NumDirs[1];$x++) {
			$Type=unpack("V",substr($Info,($x*8)+16,4));
			if($Type[1]==16) {                          //FILEINFO resource
				$InfoFound=TRUE;
				$SubOff=unpack("V",substr($Info,($x*8)+20,4));
				break;
			}
		}
		if (!$InfoFound) return FALSE;
		$SubOff[1]&=0x7fffffff;
		$InfoOff=unpack("V",substr($Info,$SubOff[1]+20,4)); //offset of first FILEINFO
		$InfoOff[1]&=0x7fffffff;
		$InfoOff=unpack("V",substr($Info,$InfoOff[1]+20,4));    //offset to data
		$DataOff=unpack("V",substr($Info,$InfoOff[1],4));
		$DataSize=unpack("V",substr($Info,$InfoOff[1]+4,4));
		$CodePage=unpack("V",substr($Info,$InfoOff[1]+8,4));
		$DataOff[1]-=$InfoVirt[1];
		$Version=unpack("v4",substr($Info,$DataOff[1]+48,8));
		/*
		$x=$Version[2];
		$Version[2]=$Version[1];
		$Version[1]=$x;
		$x=$Version[4];
		$Version[4]=$Version[3];
		$Version[3]=$x;
		*/
		$result=$Version[2].'.'.$Version[1].'.'.$Version[4].'.'.$Version[3];
		return $result;
		}
	
}