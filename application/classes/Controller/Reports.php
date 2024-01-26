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
	25.01.2024
	Сохранение отчета в файл csv
	*/
	public function action_savecsv ()
	{
		//echo Debug::vars('29', $_POST); exit;
		$id_pep=Arr::get($_POST, 'id_pep');
		$forsave=unserialize(Arr::get($_POST, 'forsave'));
		
		$file_name="report_wt".$id_pep.'_'.date('Y-m-d_H_i_s').".csv";
		$file_name="report_wt".$id_pep.".csv";
		$fp = fopen($file_name, 'w');
		$f_title=array('Отчет рабочего времени сотрудника '.$id_pep);
		$listColumn=array(
			'Дата',
			'День недели',
			'Пришел',
			'Ушел',
			);
		//$content1 = View::factory('report/wt_cvs', array('forsave'=>$forsave));
			$report= Model::factory('ReportWorkTime');
			$report->id_pep=$id_pep;
			$dataForExport=$report->makeCvs($forsave);
			//echo Debug::vars('29', $listColumn, $dataForExport); exit;
			//echo Debug::vars('29', $forsave); exit;
				//fputcsv($fp, $f_title,';');
			//	fputcsv ($fp, $listColumn,';');
			
			foreach ($dataForExport as $key=>$value)
		{
			//echo Debug::vars('29', $value); exit;
			fputcsv ($fp, $value,';');
		}
			
		
	
		fclose($fp); //Закрытие файла
		$content = Model::Factory('ReportWorkTime')->send_file($file_name);
		
		//echo Debug::vars('29', $file_name); exit;
		$this->redirect('/report');
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
			$content = View::factory('report/wt')
				->bind('id_pep', $id_pep)
				->bind('report', $report)
				->bind('duration', $duration)
				->bind('workTimeOrder', $workTimeOrder)
				->bind('alert', $fl);
				
				
				$this->template->content = $content;
				
						
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
