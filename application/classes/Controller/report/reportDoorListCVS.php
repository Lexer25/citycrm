<?php
//подготовка отчета csv
				$forsave=unserialize(iconv('UTF-8', 'CP1251', Arr::get($post, 'outDoorList')));
				//echo Debug::vars('470', $forsave);exit;
				$id_pep=Arr::get($post, 'id_pep');
				$file_name="report_doorList_".$id_pep.'_'.date('Y-m-d_H_i_s').".csv";
				$file_name="report_doorList_".$id_pep.".csv";
				$fp = fopen($file_name, 'w');
				$f_title=array('Список точек прохода сотрудника '.$id_pep);
				
			
					$report= Model::factory('ReportDoorList');
					$report->id_pep=Arr::get($post, 'id_pep');
					$dataForExport=$report->makeCvs($forsave);
					
					
					foreach ($dataForExport as $key=>$value)
				{
					//echo Debug::vars('29', $value); exit;
					fputcsv ($fp, $value,';');
				}
					
				
			
				fclose($fp); //Закрытие файла
				$content = Model::Factory('ReportDoorList')->send_file($file_name);