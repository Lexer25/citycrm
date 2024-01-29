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
	28.01.2024
	Сохранение отчета в pdf
	*/
	
	public function action_savepdf ()
	{
		$id_pep=Arr::get($_POST, 'id_pep');
		$forsave=unserialize(Arr::get($_POST, 'forsave'));
		
		define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
		require_once APPPATH . '/vendor/PHPExcel-1.8/Classes/PHPExcel.php';
		require_once APPPATH . '/vendor/autoload.php';

		$modePdfList=array(
			'tcpdf',
			'mpdf',
			'dompdf'
		);
		
		$mode=1;
		$modePDF=Arr::get($modePdfList, $mode);
			
			switch($modePDF){
				case('tcpdf'):
					/*
					Работа с TCPDF
					
					*/
					
					$rendererName = PHPExcel_Settings::PDF_RENDERER_TCPDF;
					$rendererLibrary = 'tcpdf';
					$rendererLibraryPath = APPPATH . '/vendor/TCPDF/';
				break;
				
				case('mpdf'):		
					/*
					Работа с MPDF
					Заработало после того, как в файле 
					C:\xampp\htdocs\citycrm\application\vendor\PHPExcel-1.8\Classes\PHPExcel\Writer\PDF\mPDF.php
					я заменил строку 94
					$pdf = new mpdf();
					на
					$pdf = new mpdf\mpdf();
					*/
					
					$rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
					$rendererLibrary = 'mPDF';
					$rendererLibraryPath = APPPATH . '/vendor/mpdf/mpdf/src/';
			
				break;
				case('dompdf'):
				default:
					
					//работа с dompdf. В целом работает.
					 
					$rendererName = PHPExcel_Settings::PDF_RENDERER_DOMPDF;
					$rendererLibrary = 'domPDF';
					$rendererLibraryPath = APPPATH . '/vendor/dompdf/';
					
				break;
			}
			// Create new PHPExcel object
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

		$pep=new Contact($id_pep);
		$xls->setCellValue('A1', 'Отчет подготовлен '. date('d.m.Y.'));
		$xls->setCellValue('A2', __('report.title', array(':surname'=>iconv('CP1251', 'UTF-8',$pep->surname),':name'=>iconv('CP1251', 'UTF-8',$pep->name),':patronymic'=>iconv('CP1251', 'UTF-8',$pep->patronymic), ':timefrom'=>$forsave->timestart, ':timeTo'=>$forsave->timeend)));
	
		
//объединнеие ячеек названия отчета на листе
		
		$objPHPExcel->getActiveSheet()->mergeCells("A1:H1");
		$objPHPExcel->getActiveSheet()->mergeCells("A2:H2");
		
			//печать заголовок колонок
		$row=3;// начиная со второй строки
		$ccol=1;//автонумерация колонок
		$column_chr=65;// char кода англ буквы A для позиционирования отчета

		foreach($forsave->colimnTitle  as$key=>$value)
		{		
			//echo Debug::vars('70', $value); exit;
			$xls->setCellValue(chr($column_chr).$row	, $value); 
			$xls->getStyle(chr($column_chr).$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			$xls->setCellValue(chr($column_chr).($row+1)	, $ccol++); 
			$xls->getStyle(chr($column_chr).($row+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			//установка автоширины колонок
		
			$xls->getColumnDimension(chr($column_chr))->setAutoSize(true);
			$column_chr++;
		
		}
		
		$org=new Company($pep->id_org);
        //заполнение данных
			
		$row=5;
		foreach($forsave->result  as $key=>$value)
		{		
			$column_chr=65;//char английской буквы A
			
			
					$xls->setCellValue(chr($column_chr++).$row	, Arr::get($value, 'date' )); 
					$xls->setCellValue(chr($column_chr++).$row	, iconv('CP1251', 'UTF-8', $org->name)); 
					$xls->setCellValue(chr($column_chr++).$row	, __('report.fio', array(':surname'=>iconv('CP1251', 'UTF-8',$pep->surname),':name'=>iconv('CP1251', 'UTF-8',$pep->name),':patronymic'=>iconv('CP1251', 'UTF-8',$pep->patronymic), ':timefrom'=>'', ':timeTo'=>''))); 
					$xls->setCellValue(chr($column_chr++).$row	, $this->trt(Arr::get($value, 'time_in'))); 
					$xls->setCellValue(chr($column_chr++).$row	, $this->trt(Arr::get($value, 'lateness'))); 
					$xls->setCellValue(chr($column_chr++).$row	, $this->trt(Arr::get($value, 'time_out'))); 
					$xls->setCellValue(chr($column_chr++).$row	, $this->trt(Arr::get($value, 'deviation'))); 
					$xls->setCellValue(chr($column_chr++).$row	, $this->trt(Arr::get($value, 'time_work'))); 
			
			$row++;
		
		}

		// рисую границы ячеек
		$border = array(
			'borders'=>array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array('rgb' => '000000')
				)
			)
		);
		
		$xls->getStyle("A3:H12")->applyFromArray($border);
		
		//выделяю заголовок жирной рамкой
		$border = array(
			'borders'=>array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THICK,
					'color' => array('rgb' => '000000')
				),
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array('rgb' => '000000')
				)
			)
		);
 
		$xls->getStyle("A3:H5")->applyFromArray($border);
		
		// Rename worksheet
		
			$objPHPExcel->getActiveSheet()->setTitle(iconv('CP1251', 'UTF-8',$pep->surname));


		//echo Debug::vars('86', '$rendererName='.$rendererName,'$rendererLibraryPath='.$rendererLibraryPath ); //exit;
			if (!PHPExcel_Settings::setPdfRenderer(
					$rendererName,
					$rendererLibraryPath
				)) {
				die(
					'NOTICE: Please set the $rendererName and $rendererLibraryPath values' .
					'<br />' .
					'at the top of this script as appropriate for your directory structure'
				);
			}


			// Redirect output to a client’s web browser (PDF)
			header('Content-Type: application/pdf');
			header('Content-Disposition: attachment;filename="УРВ_'.iconv('CP1251', 'UTF-8',$pep->surname).'_'.date('Y_m_d'));
			header('Cache-Control: max-age=0');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
			
			$objWriter->save('php://output');
			exit;
		
		
		
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
		//echo Debug::vars('190', $forsave, count($forsave->colimnTitle)); exit;
		define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
		require_once APPPATH . '/vendor/PHPExcel-1.8/Classes/PHPExcel.php';
		require_once APPPATH . '/vendor//PHPExcel-1.8/Classes/PHPExcel/Writer/Excel2007.php';
		//https://snipp.ru/php/phpexcel?ysclid=lrwbz922se302951359 
	
		$objPHPExcel = new PHPExcel();//создал документ
		
		// Set document properties
		
		$objPHPExcel->getProperties()->setCreator("ООО Артсек")
									 ->setLastModifiedBy("ООО Артсек")
									 ->setTitle("Учет рабочего времени титул")
									 ->setSubject("Отчет Учет рабочего времени по выбранному сотруднику")
									 ->setDescription("Отчет Учет рабочего времени по выбранному сотрунику. Отчет получен экспортом из системы Artonit City.")
									 ->setKeywords("Учет рабочего времени")
									 ->setCategory("Учет рабочего времени");

		$xls=$objPHPExcel->setActiveSheetIndex(0);	//создал новый лист
	
		
		// Установка названия отчета
		$pep=new Contact($id_pep);// создал пипла для получения ФИО
		
		$xls->setCellValue('A1', __('report.title', array(':surname'=>iconv('CP1251', 'UTF-8',$pep->surname),':name'=>iconv('CP1251', 'UTF-8',$pep->name),':patronymic'=>iconv('CP1251', 'UTF-8',$pep->patronymic), ':timefrom'=>$forsave->timestart, ':timeTo'=>$forsave->timeend)));
		
		//объединнеие ячеек названия отчета на листе
		
		$objPHPExcel->getActiveSheet()->mergeCells("A1:".chr(65 + count($forsave->colimnTitle))."1");
		
		
		
		//печать заголовок колонок
		
		$row=2;// начиная со второй строки
		$ccol=1;//автонумерация колонок
		$column_chr=65;// char кода англ буквы A для позиционирования отчета
		
		foreach($forsave->colimnTitle as $key=>$value)
		{		
			//echo Debug::vars('70', $value); exit;
			$xls->setCellValue(chr($column_chr).$row	, $value); 
			$xls->getStyle(chr($column_chr).$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			$xls->setCellValue(chr($column_chr).($row+1)	, $ccol++); 
			$xls->getStyle(chr($column_chr).($row+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			//установка автоширины колонок
		
			$xls->getColumnDimension(chr($column_chr))->setAutoSize(true);
			$column_chr++;
		}
		
		//информация по сотруднику
		
		
		$org=new Company($pep->id_org);
        //заполнение данных
			
		$row=4;
		foreach($forsave->result  as $key=>$value)
		{		
			$column_chr=65;//char английской буквы A
			
			
					$xls->setCellValue(chr($column_chr++).$row	, Arr::get($value, 'date' )); 
					$xls->setCellValue(chr($column_chr++).$row	, iconv('CP1251', 'UTF-8', $org->name)); 
					$xls->setCellValue(chr($column_chr++).$row	, __('report.fio', array(':surname'=>iconv('CP1251', 'UTF-8',$pep->surname),':name'=>iconv('CP1251', 'UTF-8',$pep->name),':patronymic'=>iconv('CP1251', 'UTF-8',$pep->patronymic), ':timefrom'=>'', ':timeTo'=>''))); 
					$xls->setCellValue(chr($column_chr++).$row	, $this->trt(Arr::get($value, 'time_in'))); 
					$xls->setCellValue(chr($column_chr++).$row	, $this->trt(Arr::get($value, 'lateness'))); 
					$xls->setCellValue(chr($column_chr++).$row	, $this->trt(Arr::get($value, 'time_out'))); 
					$xls->setCellValue(chr($column_chr++).$row	, $this->trt(Arr::get($value, 'deviation'))); 
					$xls->setCellValue(chr($column_chr++).$row	, $this->trt(Arr::get($value, 'time_work'))); 
			
			$row++;
		
		}
		
		$objPHPExcel->getActiveSheet()->setTitle(iconv('CP1251', 'UTF-8',$pep->surname));


		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		//$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
		$file_name='УРВ_'.iconv('CP1251', 'UTF-8',$pep->surname).'_'.date('Y_m_d').'.xlsx';
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
	
		$report->colimnTitle=array(
				__('report.date'),
				__('report.org'), 
				__('report.pepname'),
				__('report.time_in'),
				__('report.lateness'),
				__('report.time_out'),
				__('report.deviation'),
				__('report.time_work')
		
		);
		if($report->getReportWT() == 0){
			
			$content = View::factory('report/wt_as_desktop')
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
