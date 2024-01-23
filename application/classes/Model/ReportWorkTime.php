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
	*/
	public function getWorkTimeInDay($day)
	{
		$sql='select min(e.datetime), max(e.datetime) from events e
			where e.datetime>\''.date('d.m.Y H:i:s', strtotime($day)).'\'
			and e.datetime<\''.date('d.m.Y  H:i:s', strtotime($day. ' +1 day')).'\'
			and e.ess1='.$this->id_pep.'
			and e.id_eventtype in (47, 48, 50, 65)';
		//echo Debug::vars('64', $sql); exit;

		
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
			
			$result['timeStartNormative']=Arr::get(Arr::get($this->workTimeOrder,$result['currentDay']), 0);//начало рабочего дня по нормативу
			$result['timeEndNormative']=Arr::get(Arr::get($this->workTimeOrder,$result['currentDay']), 1);//завершение рабочего дня по нормативу
			$result['timeDinnerNormative']=Arr::get(Arr::get($this->workTimeOrder,$result['currentDay']), 2);// длительность обеда по нормативу
			$result['timeLongWorkDayNormative']=$result['timeEndNormative']-$result['timeStartNormative'];// нормативная длительность рабочего дня (включая обед)
		
			$result['time_startCount']= ($result['time_in']> $result['timeStartNormative'])? $result['time_in'] : $result['timeStartNormative'];//время начала пребывания на работе для расчета
			$result['time_endtCount']= $result['time_out'];//время окончания пребывания на работе  рабочего дня для расчета
		
		//echo Debug::vars('92',   $key, $value,  $result); exit;
		$this->result[]=$result;
		}
		
		return 0;
	}
	
	
	
	
	
	
	
	public function getReportWT2()//учет рабочего времени для контактов указанной организации.
	{
			
		$sql="select pwt.*,     (time_work - time'0:0') sec_work, (time_delay - time'0:0') sec_delay, (time_before - time'0:0') sec_before from Report_WorkTime_Order(1,1,'$this->timestart','$this->timeend',1,1,0,0,0) pwt 
			where ( 
				(	id_pep is null) 
					or ((id_pep in ($this->id_pep)
				)
				and (
					(time_in is not null)
					or (time_out is not null)
					or (TIME_WORK is not null))
					) 
				)";
			
		//echo Debug::vars('47', $sql); exit;	

		try {
			$this->result = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
			return 0;
				
		} catch (Exception $e) {
			return 3;
			}	
		
	
	
	}
	
}
	

