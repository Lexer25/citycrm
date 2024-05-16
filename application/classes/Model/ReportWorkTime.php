<?php defined('SYSPATH') OR die('No direct access allowed.');
//модель отчета рабочего времени.
class Model_ReportWorkTime extends Model
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
	Получение списка дат, когда сотрудник был на работе.
	*/
	public function getWorkDayList()
	{
		$sql='select distinct cast(e.datetime as date) from events e
			where e.datetime>\''.$this->timestart.'\'
			and e.datetime<\''.$this->timeend.'\'
			and e.ess1='.$this->id_pep.'
			and e.id_eventtype in (47, 48, 50, 65)
			group by   e.datetime';
		//echo Debug::vars('41', $sql); exit;	
		try {
			$this->dayList = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
			return 0;
				
		} catch (Exception $e) {
			return 3;
			}	
		
	}
	
	/*
	Поиск первой и поледней метки времени посещения в течении указанной дат
	$day - дата
	return - первая и последняя отметка для указанного сотрудника
	*/
	public function getWorkTimeInDay($day)
	{
		$sql='select min(e.datetime), max(e.datetime) from events e
			where e.datetime>\''.date('d.m.Y H:i:s', strtotime($day)).'\'
			and e.datetime<\''.date('d.m.Y  H:i:s', strtotime($day. ' +1 day')).'\'
			and e.ess1='.$this->id_pep.'
			and e.id_eventtype in (47, 48, 50, 65)';
		
		try {
			$query = Arr::flatten(DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array());
			return $query;
				
		} catch (Exception $e) {
			Log::instance()->add(Log::DEBUG, $e->getMessage());
			}	
		
	}
	
	/*
	23.01.204 
	вспомогательная функция, которая считает количество секунд от начала суток указанной даты
	*/
	
	public function secondFromMidNight($date)
	{
		
		return strtotime($date) - strtotime(date('d.m.Y', strtotime($date)));
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
		$report[]=array(iconv('UTF-8','windows-1251', 'Отчет рабочего времени сотрудника ').$this->id_pep);
		
		$columnList=array(
			iconv('UTF-8','windows-1251','Дата'),
			iconv('UTF-8','windows-1251','День недели'),
			iconv('UTF-8','windows-1251','Пришел'),
			iconv('UTF-8','windows-1251','Ушел'),
			iconv('UTF-8','windows-1251','Отработал'),
			iconv('UTF-8','windows-1251','Начало рабочего дня'),
			iconv('UTF-8','windows-1251','Зачершение рабочего дня'),
			iconv('UTF-8','windows-1251','Обед'),
			iconv('UTF-8','windows-1251','Длительно рабочего дня'),
			iconv('UTF-8','windows-1251','Приход расчет'),
			iconv('UTF-8','windows-1251','Уход расчет'),
			iconv('UTF-8','windows-1251','Отработал'),
			iconv('UTF-8','windows-1251','Недоработал'),
			);
		
		$columnNum=array(0, 1,2,3,4,5,6,7,8,9,10, 11,12);
		$report[]=$columnList;
		$report[]=$columnNum;
		foreach ($forsave as $key=>$value)
		{
			echo Debug::vars($key, $value);
						
			$rep[0]=Arr::get($value, 'date'); //"date" => string(10) "2023-12-01"
			//$rep[1]=Arr::get($value,'currentDay');//"currentDay" => string(1) "5"
			$rep[2]=$this->trt(Arr::get($value,'time_in'));//пришел
			$rep[3]=$this->trt(Arr::get($value,'time_out'));//ушел
			$rep[4]=$this->trt(Arr::get($value,'time_out') - Arr::get($value,'time_in'));//был на работе
			$rep[5]=$this->trt(Arr::get($value,'timeStartNormative'));//начало рабочего дня
			$rep[6]=$this->trt(Arr::get($value,'timeEndNormative'));//конец рабочего дня
			$rep[7]=$this->trt(Arr::get($value,'timeDinnerNormative'));//начало обеденного перерыва
			$rep[8]=$this->trt(Arr::get($value,'timeLongWorkDayNormative'));//длительность рабочего перерыва
			$rep[9]=$this->trt(Arr::get($value,'time_startCount'));//начало отсчета?
			$rep[10]=$this->trt(Arr::get($value,'time_out'));//время ухода
			
			// расчет фактического рабочего времени работы $var3
			
			if(Arr::get($value,'timeStartNormative') < Arr::get($value,'time_in')) $var3 = Arr::get($value,'time_out') - Arr::get($value,'time_in');//Отработал в табель с момента прихода
			if(Arr::get($value,'timeStartNormative') >= Arr::get($value,'time_in')) $var3 = Arr::get($value,'time_out') - Arr::get($value,'timeStartNormative');//Отработал в табель с начала рабочего дня
			$rep[11]=$this->trt($var3);
			
			
			//выявление и расчет недоработки
			$rep[12]=0; // время недоработки
			$var2=Arr::get($value,'timeEndNormative') - Arr::get($value,'timeStartNormative'); // это расчетная длительность рабочего дня.
			
			if($var2>$var3) $rep[12]=$this->trt($var2-$var3); //время недоработки
			//if()
			//echo Debug::vars($value, $rep); exit;
			$report[]=$rep;
		}
		return $report;
	}
	
	/*
	28.01.2024 преобразование секунд в часы, минуты и секунды
	
	*/
	
	public function trt($var)
	{
		return floor($var/3600).':'
								.str_pad(floor($var%3600/60),2, 0,STR_PAD_LEFT).':'
								.str_pad(($var%3600)%60,2, 0,STR_PAD_LEFT);
	}
}
	

