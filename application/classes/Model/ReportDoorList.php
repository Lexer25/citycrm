<?php defined('SYSPATH') OR die('No direct access allowed.');
//модель отчета список точек прохода.
class Model_ReportDoorList extends Model
{
	
	public $id_org;
	public $id_pep;
	public $timestart;
	public $timeend;
	public $devgroup_in;
	public $devgroup_out;
	public $result=array();
	public $dayList=array();
	
	
	public function init_org($id_org)
	{
		$this->id_org=$id_org;
		
		
	}
	
	public function init_pep($id_pep)
	{
		$this->id_pep=$id_pep;
		
	}
	
	
	
	
	/*
	
	23.01.2024 подготовка таблицы для распечатки отчета
	@bref подготовка отчета рабочего времени как на десктопном компьютере
	@input - расчет ведется по диапазону дат и id_pep
	@varout массив данных посуточно в следующем порядке:
	
	*/
	
	public function getReportWT()
	{
		$this->getWorkDayList();
		$result=array();
		foreach($this->dayList as $key=>$value)
		{
			$var1= $this->getWorkTimeInDay(Arr::get($value, 'CAST'));
			
			$result['date']= Arr::get($value, 'CAST');//Дата расчета
			$result['currentDay']= date('w', strtotime(Arr::get($var1, 'MIN')));//Дата расчета
			$result['time_in']= $this->secondFromMidNight(Arr::get($var1, 'MIN'));//время прихода контакта на работу
			$result['time_out']= $this->secondFromMidNight(Arr::get($var1, 'MAX'));//время ухода контакта с работы
			$result['time_on_work']=$result['time_out'] - $result['time_in'];//время нахождения на территории 
			if($result['time_out']>$result['time_in']) $result['time_on work']= $this->secondFromMidNight(Arr::get($var1, 'MAX'));//время нахождения на работе в течении суток
			
			$result['timeStartNormative']=Arr::get(Arr::get($this->workTimeOrder,$result['currentDay']), 0);//начало рабочего дня по нормативу
			$result['timeEndNormative']=Arr::get(Arr::get($this->workTimeOrder,$result['currentDay']), 1);//завершение рабочего дня по нормативу
			$result['timeDinnerNormative']=Arr::get(Arr::get($this->workTimeOrder,$result['currentDay']), 2);// длительность обеда по нормативу
			$result['timeLongWorkDayNormative']=$result['timeEndNormative']-$result['timeStartNormative'];// нормативная длительность рабочего дня (включая обед)
		
			$result['time_startCount']= ($result['time_in']> $result['timeStartNormative'])? $result['time_in'] : $result['timeStartNormative'];//время начала пребывания на работе для расчета
			$result['time_endtCount']= $result['time_out'];//время окончания пребывания на работе  рабочего дня для расчета
			
			
			$result['time_work']= $result['time_out']-$result['time_startCount'];//время пребывания на работе  рабочего дня для расчета
			
			//на lateness - на сколько опоздал
			if(Arr::get($result, 'timeStartNormative') < Arr::get($result, 'time_in')) {
								
								$result['lateness']=Arr::get($result, 'time_in') - Arr::get($result, 'timeStartNormative');
							
							} else {
								$result['lateness']=0;
								
							}
							
				// недоработал
						//deviation показывать время, если был на работе меньше нормативного
						$var1=Arr::get($result, 'timeEndNormative') - Arr::get($result, 'timeStartNormative');//нормативная длительность рабочего дня
						$var2=0;// сколько был на работе в рамках рабочего времени
							
							if(Arr::get($result, 'timeStartNormative') > Arr::get($result, 'time_in')) {
								$var2=Arr::get($result, 'time_out') - Arr::get($result, 'timeStartNormative');
							} else {
								$var2=Arr::get($result, 'time_out') - Arr::get($result, 'time_in');
							}
						
						$result['deviation']=0;// недоработал
						if($var2<$var1) $result['deviation']= $var1-$var2;
		
		//echo Debug::vars('92',   $key, $value,  $result); exit;
		
		$result_out=array(
			$result['date'],
			$result['time_in'],
			$result['lateness'],
			$result['time_out'],
			$result['deviation'],
			$result['time_work']
			
		);
		$this->result[]=$result;

		}
		
		return 0;
	}
	
	

	
	
	public function send_file ($file)// скачать указанный файл в браузер
	{
		//https://habr.com/ru/post/151795/
		/* $file = $name;
		header ("Content-Type: application/force-download");
		header ("Accept-Ranges: bytes");
		header ("Content-Length: ".filesize($file));
		header ("Content-Disposition: attachment; filename=".basename($file));  
		readfile($file);
		return basename($file); */
		
		if (file_exists($file)) {
    // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
    // если этого не сделать файл будет читаться в память полностью!
    if (ob_get_level()) {
      ob_end_clean();
    }
    // заставляем браузер показать окно сохранения файла
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    // читаем файл и отправляем его пользователю
    readfile($file);
    exit;
  }
	}
  
  
	/*
	Тут происходит преобразовние "голых" данных в формат 
	
	*/
	
	public function makeCvs($forsave)
	{
		$var2=0;
		$var3=0;
		$report=array();
		$contact=new Contact($this->id_pep);
		$report[]=array(iconv('UTF-8','windows-1251', 'Список точек прохода сотрудника ').$contact->surname.' '.$contact->name.' '.$contact->patronymic);
		$report[]=array(iconv('UTF-8','windows-1251', 'Отчет подготовлен ').date('d.m.Y H:i:s'));
		
		$columnList=array(
			iconv('UTF-8','windows-1251','№ п,п'),
			iconv('UTF-8','windows-1251','id'),
			iconv('UTF-8','windows-1251','Название точки прохода'),
			
			);
		
		$columnNum=array(0, 1);
		$report[]=$columnList;
		$report[]=$columnNum;
		foreach ($forsave as $key=>$value)
		{
			//echo Debug::vars('172', $value, Arr::get($value, 'sn')); exit;
			//$rep[0]=$key;
			$rep[0]=Arr::get($value, 'sn');
			$rep[1]=Arr::get($value, 'id_door');
			$rep[2]=Arr::get($value, 'name');
			
			
			
			$report[]=$rep;
		}
		return $report;
	}
	
	
}	

