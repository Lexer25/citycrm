<?php
//подготовка отчета xlsx
	$id_pep=Arr::get($post, 'id_pep');
		$forsave=unserialize(iconv('UTF-8', 'CP1251', Arr::get($post, 'outDoorList')));
		$colimnTitle=array(
				__('report.sn'),
				__('report.id_org'), 
				__('report.name'),
				
		
		);
		
		$modePdfList=array(
			'tcpdf',
			'mpdf',
			'dompdf'
		);
		
		define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
		require_once APPPATH . '/vendor/PHPExcel-1.8/Classes/PHPExcel.php';
		require_once APPPATH . '/vendor/autoload.php';
		
		$mode=0;
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
			
			
		
		
		
	$xls = new PHPExcel();
		$objPHPExcel = new PHPExcel();
		$xls=$objPHPExcel->setActiveSheetIndex(0);
			
		
		// Set document properties
		
		$objPHPExcel->getProperties()->setCreator("ООО Артсек")
									 ->setLastModifiedBy("ООО Артсек")
									 ->setTitle("Список точек доступа")
									 ->setSubject("Отчет Список точек доступа")
									 ->setDescription("Отчет Список точек доступа. Отчет получен экспортом из системы Artonit City.")
									 ->setKeywords("Список точек доступа")
									 ->setCategory("Список точек доступа");

		$xls=$objPHPExcel->setActiveSheetIndex(0);	//создал новый лист
	
		
		// Установка названия отчета
		$pep=new Contact($id_pep);// создал пипла для получения ФИО
		
		$xls->setCellValue('A1', __('report.doorlist.title', array(':surname'=>iconv('CP1251', 'UTF-8',$pep->surname),':name'=>iconv('CP1251', 'UTF-8',$pep->name),':patronymic'=>iconv('CP1251', 'UTF-8',$pep->patronymic))));
		
		//объединнеие ячеек названия отчета на листе
		
		//$objPHPExcel->getActiveSheet()->mergeCells("A1:F1");
		$xls->mergeCells("A1:G1");
		
		
		$xls->setCellValue('A2', __('report.datestamp', array(':timestamp'=>date('d.m.Y H:i:s'))));
		//$objPHPExcel->getActiveSheet()->mergeCells("A2:C2");
		$xls->mergeCells("A2:C2");
		
		//печать заголовок колонок
		
		$row=3;// начиная со второй строки
		$ccol=1;//автонумерация колонок
		$column_chr=65;// char кода англ буквы A для позиционирования отчета
		
		foreach($colimnTitle as $key=>$value)
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
			
		$row=4;
		foreach($forsave  as $key=>$value)
		{		
			$column_chr=65;//char английской буквы A
			
			
					$xls->setCellValue(chr($column_chr++).$row	, Arr::get($value, 'sn' )); 
					$xls->setCellValue(chr($column_chr++).$row	, Arr::get($value, 'id_door' )); 
					$xls->setCellValue(chr($column_chr++).$row	, iconv('CP1251', 'UTF-8', Arr::get($value, 'name' ))); 
					
			
			$row++;
		
		}
			// рисую границы ячеек
		$border = array(
			'borders'=>array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_NONE,
					'color' => array('rgb' => '000000')
				)
			)
		);
		
		$xls->getStyle("A1:I23")->applyFromArray($border);
		
		//выделяю заголовок жирной рамкой
		$border = array(
			'borders'=>array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array('rgb' => '000000')
				),
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array('rgb' => '000000')
				)
			)
		);
 
		$xls->getStyle("A3:C23")->applyFromArray($border);
		
		// Rename worksheet
		
		$objPHPExcel->getActiveSheet()->setTitle(iconv('CP1251', 'UTF-8',$pep->surname));


		
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