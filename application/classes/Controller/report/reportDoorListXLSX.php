<?php
//подготовка отчета xlsx
	$id_pep=Arr::get($post, 'id_pep');
		$forsave=unserialize(iconv('UTF-8', 'CP1251', Arr::get($post, 'outDoorList')));
		$colimnTitle=array(
				__('report.sn'),
				__('report.id_org'), 
				__('report.name'),
				
		
		);

		//echo Debug::vars('190', $forsave, count($colimnTitle)); exit;
		define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
		require_once APPPATH . '/vendor/PHPExcel-1.8/Classes/PHPExcel.php';
		require_once APPPATH . '/vendor//PHPExcel-1.8/Classes/PHPExcel/Writer/Excel2007.php';
		//https://snipp.ru/php/phpexcel?ysclid=lrwbz922se302951359 
	
		$objPHPExcel = new PHPExcel();//создал документ
		
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
		
		$objPHPExcel->getActiveSheet()->mergeCells("A1:".chr(65 + count($colimnTitle))."1");
		
		$xls->setCellValue('A2', __('report.datestamp', array(':timestamp'=>date('d.m.Y H:i:s'))));
		$objPHPExcel->getActiveSheet()->mergeCells("A2:C2");
		
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
		
		$objPHPExcel->getActiveSheet()->setTitle(iconv('CP1251', 'UTF-8',$pep->surname));


		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		//$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
		$file_name='УРВ_'.iconv('CP1251', 'UTF-8',$pep->surname).'_'.date('Y_m_d').'.xlsx';
		$objWriter->save($file_name);
		
		$content = Model::Factory('ReportDoorList')->send_file($file_name);