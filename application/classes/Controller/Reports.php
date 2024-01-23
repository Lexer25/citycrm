<?php defined('SYSPATH') or die('No direct script access.');
/*
21.01.2024
отчеты разные

*/
class Controller_Reports extends Controller_Template
{
	public $template = 'template';

	private $session;
	
	public function before()
	{
		parent::before();
		if (!Auth::instance()->logged_in()) $this->redirect('/');

		$this->session = Session::instance();
		I18n::$lang = $this->session->get('language', 'en-us');
		$this->listsize = $this->session->get('listsize', 10);
	}
	
	
	/*
	21.01.2024
	Учет рабочего времени для одного человека
	
	*/
	public function action_wtOncePep()
	{

		
		$id_pep=Arr::get($_POST, 'id_pep');
		$report=Model::factory('ReportWorkTime');
		//$report->init_org($id_org);
		$report->init_pep($id_pep);
		$report->timestart=Arr::get($_POST, 'reportdatestart');
		$report->timeend=Arr::get($_POST, 'reportdateend');
		$report->workTimeOrder=array(
			array(8*3600, 17*3600, 45*60),//вскр
			array(8*3600, 17*3600, 45*60),//пнд
			array(8*3600, 17*3600, 45*60),//вт
			array(8*3600, 17*3600, 45*60),//ср
			array(8*3600, 17*3600, 45*60),//чт
			array(8*3600, 16*3600+45*60, 45*60),//птн
			array(8*3600, 17*3600, 45*60),//сб
			
			); // начало рабочего дня по дням недели 0 - воскресентье
	
		
		if($report->getReportWT() == 0){
			//echo Debug::vars('32', $id_pep, $report->result); exit;
			$this->template->content = View::factory('report/wt')
				->bind('id_pep', $id_pep)
				->bind('report', $report)
				->bind('duration', $duration)
				->bind('workTimeOrder', $workTimeOrder)
				->bind('alert', $fl);
				
						
		//$html = View::factory('dashboard');	
	//echo Debug::vars('74', $html); exit;
	// Create class instance PDF
	//$pdf = PDF::factory($this->template->content);
	
	
	// Render and save PDF
	//$pdf = $pdf->render()->save('your/path/upload', 'filename'); 
	
	
				
				return;
		} else {
			
			echo Debug::vars('48 Ошибка подготовки отчета.'); exit;
		}
		
		
	}
	
}
