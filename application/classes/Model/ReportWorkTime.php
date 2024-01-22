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
	
	
	public function init_org($id_org)
	{
		$this->id_org=$id_org;
		
		
	}
	
	public function init_pep($id_pep)
	{
		$this->id_pep=$id_pep;
		
	}
	
	
	
	public function getReportWT()//учет рабочего времени для контактов указанной организации.
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
	

