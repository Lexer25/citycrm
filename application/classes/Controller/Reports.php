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
	27.01.2024
	Сохранение отчета в файл xlsx
	*/
	public function action_savexlsx ()
	{
		//echo Debug::vars('29', $_POST); exit;
		$id_pep=Arr::get($_POST, 'id_pep');
		$forsave=unserialize(Arr::get($_POST, 'forsave'));
		
		define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
		require_once APPPATH . '/vendor/PHPExcel-1.8/Classes/PHPExcel.php';
		require_once APPPATH . '/vendor//PHPExcel-1.8/Classes/PHPExcel/Writer/Excel2007.php';
		//https://snipp.ru/php/phpexcel?ysclid=lrwbz922se302951359 
		$xls = new PHPExcel();
		$objPHPExcel = new PHPExcel();
		$xls=$objPHPExcel->setActiveSheetIndex(0);	
		// Set document properties
		
		$objPHPExcel->getProperties()->setCreator("ООО Артсек")
									 ->setLastModifiedBy("ООО Артсек")
									 ->setTitle("Учет рабочего времени титул")
									 ->setSubject("Отчет Учет рабочего времени по выбранному сотруднику")
									 ->setDescription("Отчет Учет рабочего времени по выбранному сотрунику. Отчет получен экспортом из системы Artonit City.")
									 ->setKeywords("Учет рабочего времени")
									 ->setCategory("Учет рабочего времени");

		// Установка названия отчета
		$pep=new Contact($id_pep);
		
		$xls->setCellValue('A1', __('report.title', array(':surname'=>iconv('CP1251', 'UTF-8',$pep->surname),':name'=>iconv('CP1251', 'UTF-8',$pep->name),':patronymic'=>iconv('CP1251', 'UTF-8',$pep->patronymic), ':timefrom'=>'', ':timeTo'=>'')));
		
				
		$titul=array(  'Дата',
            'День недели',
            'Пришел',
            'Ушел',
            'Отработал',
            'Начало рабочего дня',
            'Зачершение рабочего дня',
            'Обед',
            'Длительно рабочего дня',
            'Приход расчет',
            'Уход расчет',
            'Отработал',
            'Недоработал');
		
		$row=2;
		$column=65;
		
		foreach($titul  as$key=>$value)
		{		
			//echo Debug::vars('70', $value); exit;
			$xls->setCellValue(chr($column++).$row	, $value); 
		}
		
		
            $xls->setCellValue('A2', 'Дата');
            $xls->setCellValue('B2', 'День недели');
            $xls->setCellValue('C2', 'Пришел');
            $xls->setCellValue('D2', 'Ушел');
            $xls->setCellValue('E2', 'Отработал');
            $xls->setCellValue('F2', 'Начало рабочего дня');
            $xls->setCellValue('G2', 'Зачершение рабочего дня');
            $xls->setCellValue('H2', 'Обед');
            $xls->setCellValue('I2', 'Длительно рабочего дня');
            $xls->setCellValue('J2', 'Приход расчет');
            $xls->setCellValue('K2', 'Уход расчет');
            $xls->setCellValue('L2', 'Отработал');
            $xls->setCellValue('M2', 'Недоработал');
			
			
		$row=4;
		foreach($forsave  as$key=>$value)
		{		
			//echo Debug::vars('70', $value, array_keys($value)); //exit;
				$column=65;
			foreach(array_keys($value) as $key2=>$value2)
			{
				//echo Debug::vars('73', Arr::get($value, $value2), chr($column++).$row++); exit; 
				// Miscellaneous glyphs, UTF-8
				//$objPHPExcel->setActiveSheetIndex(0)
					$xls->setCellValue(chr($column++).$row	, $this->trt(Arr::get($value, $value2))); 
			}
			$row++;
		
		}
		
		//exit;
		
		// Rename worksheet
		
		$objPHPExcel->getActiveSheet()->setTitle('УРВ');


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);


		// Save Excel 2007 file
		//echo date('H:i:s') , " Write to Excel2007 format" , EOL;
		$callStartTime = microtime(true);

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		//$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
		$file_name='123321.xlsx';
		$objWriter->save($file_name);
		
		$content = Model::Factory('ReportWorkTime')->send_file($file_name);

		$this->redirect('/contacts/worktime/'.$id_pep);
	}
	
	
	public function trt($var)
	{
		return floor($var/3600).':'
								.str_pad(floor($var%3600/60),2, 0,STR_PAD_LEFT).':'
								.str_pad(($var%3600)%60,2, 0,STR_PAD_LEFT);
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
