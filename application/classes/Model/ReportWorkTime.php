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
	
	public function getReportWT()
	{
		$this->getWorkDayList();
		$result=array();
		foreach($this->dayList as $key=>$value)
		{
			//echo Debug::vars('82', $this->getWorkTimeInDay(Arr::get($value, 'CAST'))); exit;
			$result[]= $this->getWorkTimeInDay(Arr::get($value, 'CAST'));
			
			
		}
		$this->result=$result;
		//echo Debug::vars('61', $result); exit;
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
	

