<?php defined('SYSPATH') or die('No direct script access.');


class Controller_eximdata extends Controller_Template{
	
	public $template = 'template';
	
	public function before()
	{
			parent::before();
			$session = Session::instance();
			if(!Session::instance()->get('skud_number')) $this->redirect('errorpage?err=no SKUD select.');
	}
	
	
	public function action_index()
	{
		$id=$this->request->param('id');
		//echo Debug::vars('11', $id);
		$orgList=Model::factory('eximdata')->getChild($id);
		$countChild=Model::factory('eximdata')->countChild($id);
		$countPeopleInOrg=Model::factory('eximdata')->countPeopleInOrg();

	

	$content = View::factory('eximpdata', array(
			'orgList'=>$orgList,
			'countChild'=>$countChild,
			'countPeopleInOrg'=>$countPeopleInOrg,
			));
			$this->template->content = $content;

	}
	
	public function action_editOrg()//просмотр свойств организации и их редактирование 23.08.2022
	{
		$id=$this->request->param('id');
		$id_org=Validation::factory(array('id_org'=>$id));
		$id_org->rule('id_org', 'digit')
				->rule('id_org', 'not_empty')
				->rule('id_org', 'Model_eximdata::unique_org');
		if($id_org->check())
			{
				$nameOrg=Model::factory('eximdata')->getFileNameFromIdOrg($id);// получил название организации
				$list=Model::factory('eximdata')->export(Arr::get($id_org, 'id_org'));// получил список данных о сотрудниках для сохранения.
						
				}
			else {
			Session::instance()->set('e_mess', $id_org->errors('eximdata'));
				//$this->template->content = $content;
			$this->redirect('/eximdata');
				
			}	
		
		$content = View::factory('org/view', array(
					'nameOrg'=>$nameOrg,
					'list'=>$list,
					'id_org'=>Arr::get($id_org, 'id_org')
					));
		$this->template->content = $content;
		
		
		
		
	}
	
	public function action_executor()// фукнция для обработки GET и POST запросов 23.08.2022
	{
		//echo Debug::vars('71', $_POST); exit;
		$post=Validation::factory($_POST);
		$post->rule('timeStart', 'regex', array(':value', '/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'))
				->rule('timeEnd', 'regex', array(':value', '/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'))
				->rule('updateWorkTime', 'not_empty')
				->rule('id_org', 'not_empty')
				->rule('id_org', 'digit')
				;
				//echo Debug::vars('205', $_POST, $post->check() ); exit;
				if($post->check())
		{
				
				//echo Debug::vars('81', 'OK',  $post ); exit;
				$res=Model::Factory('eximdata')->setWorkTime(Arr::get($post, 'timeStart'), Arr::get($post, 'timeEnd'),Arr::get($post, 'id_org') );
				Session::instance()->set('ok_mess', array('Обновление времен начала и завершения рабочего дня выполнено успешно.'));
				$this->redirect('/eximdata/editOrg/'.Arr::get($post, 'id_org'));
				
				
		} else {
				
			//echo Debug::vars('86','Not valid',  $post ); exit;
			
			Session::instance()->set('e_mess', $post->errors('eximdata'));
			$this->redirect('/eximdata');
		}
		
		
		
	}
	
	
	
	public function action_export()// 19.02.2023
	{
		$id=$this->request->param('id');
			
		$file='file2.csv';	
		$id_org=Validation::factory(array('id_org'=>$id));
		$id_org->rule('id_org', 'digit')
				->rule('id_org', 'not_empty')
				->rule('id_org', 'Model_eximdata::unique_org');
		
		if($id_org->check())
			{
				$nameOrg=Model::factory('eximdata')->getFileNameFromIdOrg($id);// получил название организации как имя файла
				$file = preg_replace("([[:punct:] ])", '_', $nameOrg).'.csv';
				$list=Model::factory('eximdata')->export(Arr::get($id_org, 'id_org'));// получил список данных о сотрудниках для сохранения.
				//echo Debug::vars('39', $list); exit;		
				//сохранение промежуточного файла
				$fp = fopen($file, 'w');
				
				foreach ($list as $fields) {
					fputcsv($fp, $fields, ';', '"');
				}
				if(!fclose($fp))
				{
					Session::instance()->set('err_mess', array('ok_mess'=>'Не могу сохранить файл '.$file));
				} 					

		
		
		//$content = Model::Factory('Log')->send_file($file);
		$content = Model::Factory('eximdata')->send_file($file);
		
		$this->redirect('/eximdata');
				
			} else {
				Session::instance()->set('e_mess', $id_org->errors('eximdata'));
				//$this->template->content = $content;
				$this->redirect('/eximdata');
				
			}	
		
		exit;
	}
	
	
	
	public function action_insertData()//19.02.2023
	{
		//echo Debug::vars('149 upload', $_POST); //exit;
		$post=Validation::factory($_POST);
		$post->rule('header', 'not_empty')
				->rule('header', 'is_array')
				->rule('header', 'Model_eximdata::surnameSet')
			->rule('id_str', 'not_empty')
				->rule('id_str', 'is_array')
				
			->rule('people', 'not_empty')
				->rule('people', 'is_array')
			->rule('id_org', 'not_empty')
				->rule('id_org', 'digit')
				;
		if($post->check()) //если все даные есть, то продолжаю работу
			{
				//echo Debug::vars('161 Valid OK', Arr::get($post, 'id_str')); exit;	
				// формирую новый массив, в котором ключи заменяются с чисел на название полей базы данных.
				
				foreach(Arr::get($post, 'id_str') as $key=>$value)// id_str - массив с указанием пиплов, которых следует эмпортировать
				{
					//echo Debug::vars('164', Arr::get(Arr::get($post, 'people'), $key)); exit;
					$interim_array[] =array_combine( Arr::get($post, 'header'), Arr::get(Arr::get($post, 'people'), $key));	
				}
				//echo Debug::vars('165', $interim_array) ; exit;
			} else { // если данные не все, то перехода на страницу импорта
				//echo Debug::vars('193 Valid ERR', Arr::get($post, 'id_str'), $post->errors('eximdata')); exit;
				Session::instance()->set('e_mess', $post->errors('eximdata'));
					$this->redirect('/eximdata');

			}				
			//полученные данные удовлетворяют всем требованиям. Начинаю готовить их к вставке.
			//присваивают данных правильные названия ключей для последующей валидации
				$list_error=array();
				foreach($interim_array as $key2=>$value2)// импорт надо делать всего блока сразу. Если хоть в одной записи есть проблемы, то надо отказываться от импорта, иначе потом придется вручную искать и удалять вставленные данные.
				{
					$data=Validation::factory($value2);
					
					//echo Debug::vars('105', $value);
					
					$data=Validation::factory($value2);
					$data->rule('surname', 'max_length', array(':value', 50))
						->rule('name', 'max_length', array(':value', 50))
						->rule('patronic_name', 'max_length', array(':value', 50))
						->rule('note', 'max_length', array(':value', 250))
						->rule('card_type_1', 'regex', array(':value', '/^[A-F\d]{10}+$/')) // https://regex101.com/
						->rule('card_type_1', 'Model_eximdata::unique_card') 
						
						;
					
					if($data->check())
					{
						//echo Debug::vars('199', 'Valid_is_OK', $id_org); exit;
						// валидация данных прошла успешно. Можно продолжать работу.
						
					} else {
						
						//echo Debug::vars('204', 'Valid_is_ERR', $value, $data->errors('eximdata'), Arr::get($data->errors('eximdata'), 0)); exit;
						
						foreach($data->errors('eximdata') as $key=>$value)
						{
							//echo Debug::vars('206', $data->errors('eximdata'), $value); exit;

							$list_error[]=$value;
						}
						//валидация данных прошла неуспешно. Подготовлен массив с ошибками $list_error
						
					}
				}
				
				if($list_error)
				{
					Session::instance()->set('e_mess', $list_error);
					$this->redirect('/eximdata');
				}
				
					foreach ($interim_array as $key=>$value)// вставка данных в базу данных СКУД
					{
						
						$new_id_pep=Model::factory('eximdata')->getNewIdPep();
						Model::factory('eximdata')->insertFIO($value, $new_id_pep, Arr::get($post, 'id_org'));// добавление в СКУД ФИО, Note для указанного id_pep
						Model::factory('eximdata')->addCard($value, $new_id_pep);// присвоение номера карты указанном пользователю
						//echo Debug::vars('222', $interim_array, $value); exit;
					}
					
				
				
				$this->redirect('/eximdata');
			
				
	}
				
	
	public function action_upload()// 19.02.2023
	{
			//echo Debug::vars('64 upload', $_GET, $_POST, $_FILES); exit;
			$id_org=Arr::get($_POST, 'id_org1');
			
			
			// create validation object
			$validation = Validation::factory($_FILES)
				->rules('csv', array(
					array('Upload::valid'),
					array('Upload::type', array(':value', array('csv', 'txt'))),
					array('Upload::size', array(':value', '10K')),
					array('Upload::not_empty'),
					
				));

			if ($validation->check())
			{
				Upload::save($validation['csv'], 'file.csv', 'c:\\fff\\');//сохраняю файл в указанную папку
				//Session::instance()->set('ok_mess', array('ok_mess'=>'CSV is successfully uploaded'));
			} else {
				// set user errors
				Session::instance()->set('e_mess', $validation->errors('eximdata'));
				$this->redirect('/eximdata');// если не удалось сохранить файл, то будет выведено сообщение об ошибке
				
			}
			//чтение данных из файла и преобразование их в массив			
			if (($fp = fopen("c:\\fff\\file.csv", "r")) !== FALSE) {
				while (($data = fgetcsv($fp, 0, ";")) !== FALSE) {
					$list[] = $data;
					
				}
				fclose($fp);
			
			}
			//echo Debug::vars('264 upload', $list); exit;
			//валидация полученных данных
			
			
			// вывод файла на экран по колонкам чтобы определить назначение
			$repeat_card_list=array();
			$nameOrg=Model::factory('eximdata')->getFileNameFromIdOrg($id_org);// получил название организации
			//echo Debug::vars('283',$id_org, $nameOrg); exit;
			 $content = View::factory('importdata_tuning', array(
			'list'=>$list,
			'id_org'=>$id_org,
			'nameOrg'=>$nameOrg,
			//'repeat_card_list'=>$repeat_card_list,
			
			));
			$this->template->content = $content;
			
	}
	
}

