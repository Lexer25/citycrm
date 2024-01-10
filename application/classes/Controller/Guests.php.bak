<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Guests extends Controller_Template
{
	public $template = 'template';
	public $archive_mode = 1;// константа режима работы Архив
	public $guest_mode = 0;// константа режима работы Гость
	public $order_mode = 2;// константа режима работы По заявкам
	public $issue = 2;// регистрация нового гостя
	public $mode;// текущий режим работы 
	
	private $listsize;
	private $session;
	
	public function before()
	{
		parent::before();
		//echo Kohana::Debug(Auth::instance()->get_user());
		if (!Auth::instance()->logged_in()) $this->redirect('/');

		$this->session = Session::instance();
		I18n::$lang = $this->session->get('language', 'en-us');
		$this->listsize = $this->session->get('listsize', 10);
	}
	
	
	
	/*
	Загрузка фотографии для Гостя не реализована
	*/
	public function action_upload_()
	{
		// check request method
		
		if ($this->request->method() === Request::POST)
		{
			// create validation object
			$validation = Validation::factory($_FILES)
				->label('image', 'Picture')
				->rules('image', array(
					array('Upload::not_empty'),
					array('Upload::image'),
				));

			// check input data
			if ($validation->check())
			{
				echo Debug::vars('40 eys', $validation, $validation['image']);// exit;
				// process upload
				echo Upload::save($validation['image'], 'vnii_photo', 'C:\xampp\tmp'); //exit;
				//запись содержимого файла в базу данных
				$file_name='C:\xampp\tmp\vnii_photo';
				//$fp = fopen($file_name, "w");
				echo Debug::vars('46', Database::instance('fb')); //exit;
				echo Debug::vars('47',  Arr::get(
      			Arr::get(
      					Kohana::$config->load('database')->fb,
      					'connection'
      					),
      		'dsn')); //exit;
				
		$photo=file_get_contents($file_name);		
		//$db = new PDO('odbc:vnii_local');
		$db = new PDO( Arr::get(
      			Arr::get(
      					Kohana::$config->load('database')->fb,
      					'connection'
      					),
      		'dsn'));
        $stmt = $db->prepare("UPDATE people SET photo = ? 
				WHERE id_pep = 1");
        //$stmt = $db->prepare("INSERT INTO ZKSOFT_FP_TAMPLATE (IDX_FINGER,ID_DB,ID_CARD,IDX_USER,FP_TAMPLATE,FP_LENGTH) VALUES(?,?,?,?,?,?)");


        $stmt->bindParam(1, $photo);
       

        $db->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
        $db->beginTransaction();
        $stmt->execute();
        $db->commit();
        $db->setAttribute(PDO::ATTR_AUTOCOMMIT, 1);
		
		
		
				//echo Debug::vars('47', $_FILES, $_POST); exit;

				// set user message
				Session::instance()->set('message', 'Image is successfully uploaded');
			} else {
				
				//echo Debug::vars('48 err', $validation); exit;
				Session::instance()->set('errors', $validation->errors('upload'));
			}

			// set user errors
			
		}

		// redirect to home page
		$this->request->redirect('/');
	}
	
	
	/*
	Поиск по ФИО
	*/
	public function action_search()
	{
		$pattern = Arr::get($_POST, 'q', null);
		if ($pattern) {
			$this->session->set('search_contact', $pattern);
		} else {
			$pattern = $this->session->get('search_contact', '');
		}
		$this->action_index($pattern);
	}
	
	/*
	Включаю режим работы Гость.
	Присваиваю признаку 'mode' значение 'guest_mode'
	вывод списка гостей на территории
	*/
	public function action_guest()
	{
		$this->session->set('mode', 'guest_mode');
		$this->mode=$this->guest_mode;
		$this->action_index();
	}
	
	/*
	Включаю режим работы Архив гостей
	Присваиваю признаку 'mode' значение 'archive_mode'
	возможен просмотр архива гостей.
	*/
	public function action_archive()
	{
		$this->session->set('mode', 'archive_mode');
		$this->mode=$this->archive_mode;
		$this->action_index();
	}
	
	/*
		Включаю режим работы issue - Выдача карты
		Новый гость, новая карта
	*/
	public function action_issue()
	{
		$this->session->set('mode', 'issue');
		
		$this->redirect('guests/edit/0/issue');
	}
	
	
	/*
	
	Организация вывода информации на экран
	*/
	public function action_index($filter = null)
	{
		
		$contacts = Model::factory('Guest');
		
		$_filter=iconv('UTF-8', 'CP1251', $filter);
		$mode=$this->mode;
		
		
		/*
		Количество записей для построения страниц
		*/
		$mode=Session::instance()->get('mode');
		//$mode - режим работы (гость, архив)
		$q = $contacts->getCountAdmin($_filter, $mode);

		
		$pagination = new Pagination(array(
			'uri_segment' => 2,
			'total_items' => $q,
			'style' => 'floating',
			'items_per_page' => $this->listsize,
			'auto_hide' => true,
		));
		
		

			/*
			Список гостей (или действующих, или гостей, в зависимости от режима работы)
			*/
			$page = Arr::get($_GET, 'page', 1);
			$perpage = $this->listsize;
			$_filter=iconv('UTF-8', 'CP1251', $filter);
			$list = $contacts->getListAdmin($page = 1, $perpage = 10, $_filter, $mode);

		$fl = $this->session->get('alert');
		$arrAlert = $this->session->get('arrAlert');
		$this->session->delete('alert');
		$this->session->delete('arrAlert');
		
		$showphone = $this->session->get('showphone', 0);
		//echo Debug::vars('158', $list); exit;
		
		$this->template->content = View::factory('guests/list')
			->bind('people', $list)
			->bind('listCount', $q)
			->bind('alert', $fl)
			->bind('arrAlert', $arrAlert)
			->bind('showphone', $showphone)
			->bind('filter', $filter)
			->bind('pagination', $pagination);
	}

	/*
	обработка POST-запросов
	11.11.2023 сохранение информации по гостю.
	Особенность: в одной функции идет последовательное выполнение сохранение данных ФИО, получение id_pep и сохранение номера карты для этого id_pep
	Данные по гостю обновлять нельзя!
	*/
	public function action_save()
	{
		//echo Debug::vars('70', $_POST); exit;
		/* $id			= Arr::get($_POST, 'id_pep');
		$surname	= Arr::get($_POST, 'surname');
		$name		= Arr::get($_POST, 'name','');
		$patronymic	= Arr::get($_POST, 'patronymic','');
		$datebirth	= Arr::get($_POST, 'datebirth', 'NULL');
		$numdoc		= Arr::get($_POST, 'numdoc', null);
		$datedoc	= Arr::get($_POST, 'datedoc', null);
		$workstart	= Arr::get($_POST, 'workstart', '09:00:00');
		$workend	= Arr::get($_POST, 'workend', '18:00:00');
		$active		= Arr::get($_POST, 'active', 1);
		$peptype	= Arr::get($_POST, 'peptype', 0);
		$post		= Arr::get($_POST, 'post', null);
		$tabnum		= Arr::get($_POST, 'tabnum');
		$login		= Arr::get($_POST, 'login', '');
		$password	= Arr::get($_POST, 'password', '');
		//$org		= Arr::get($_POST, 'id_org');
		$org		= 2;
		$inherit	= Arr::get($_POST, 'inherit', 0);
		$note		= Arr::get($_POST, 'note', null);
		$id_org_old		= Arr::get($_POST, 'id_org_old', null);
		
		
		$carddatestart		= Arr::get($_POST, 'carddatestart', null);
		$carddateend		= Arr::get($_POST, 'carddateend', null);
		$id_cardtype		= Arr::get($_POST, 'id_cardtype', null);
		$note_card		= Arr::get($_POST, 'note_card', null);
		
		
		$isactive=1;// карта активна.
		$cardstate=0;//это поле status в таблице card. Смысла никакого не несет, но нужен для единообразного хранения данных
		$idaccess=null;// устаревший параметр.
		$useenddate=1;//проверять ли срок действия карты? 1 - проверять, 0 - не проверять. */
		
		$id			= Arr::get($_POST, 'id_pep');
		$idcard		= Arr::get($_POST, 'idcard', null);
		$todo		= Arr::get($_POST, 'todo', null);
		switch($todo){
			
			
			case 'savenew':// это добавление нового пользователя, т.к. $id (она же id_pep) равна 0.
				$key=new Keyk($idcard);
				$check=$key->check(1);
				if(is_null($check)){
					$guest=new Guest;
					$guest->name=Arr::get($_POST, 'name','');
					$guest->patronymic=Arr::get($_POST, 'patronymic','');
					$guest->surname=Arr::get($_POST, 'surname','');
					$guest->numdoc=Arr::get($_POST, 'numdoc','');
					$guest->datedoc=Arr::get($_POST, 'datedoc','');
					$guest->note=Arr::get($_POST, 'note','');
					
					$guest->note=Arr::get($_POST, 'note','');
					$guest->note=Arr::get($_POST, 'note','');
					
					if($guest->addGuest() == 0) { // если пользователь добавлен успешно, то выставляю ему набор категорий доступа по умолчанию
						
						$alert=__('guest.addOK', array(':surname'=>$guest->surname,':name'=>$guest->name,':patronymic'=>$guest->patronymic,':id_pep'=>$guest->id_pep,':tabnum'=>$guest->tabnum));
				
							// присвоение категории доступа по умолчанию для организации Гость.
							if($guest->setAclDefault()){ // если категории добавлены успешно, то записываю карту
								$alert=$alert. '<br>'. __('acl.saved');
							}
							
							$key->id_card=$idcard;
							$key->timestart=Arr::get($_POST, 'carddatestart');
							$key->timeend=Arr::get($_POST, 'carddateend');
							$key->id_pep=$guest->id_pep;
							
							//присвоедние карты RFID
							if($key->addRfid()==0) { ;//сохраняю карту RFID
									$arrAlert[]=array('actionResult'=>$guest->actionResult, 'actionDesc'=>$guest->actionDesc);
									$alert=$alert.'<br>'.  __('guest.addRfidOk', array(':id_card'=>$key->id_card));
							};
							
							//если номер документа или его дата не пусты, то сохранить документы
							//echo Debug::vars('296', $guest->numdoc=='', $guest->datedoc==''); exit;
							if($guest->addDoc() == 0) {;//добавляю данные по документу
									$arrAlert[]=array('actionResult'=>$guest->actionResult, 'actionDesc'=>$guest->actionDesc);
									$alert.='<br>'. __('guest.adddocOK', array(':numdoc'=>$guest->numdoc, ':surname'=>$guest->surname,':name'=>$guest->name,':patronymic'=>$guest->patronymic));
								}
							 
					Session::instance()->set('alert', $alert);
					} else {
						
						//не удалось добавить гостя в базу данных СКУД.
						
					}
					//Session::instance()->set('alert', __('contact.key_occuped_NO'));
					
				} else {
					//карта выдана сотруднику с id_pep=$check
					
					$anypeople=new Guest($check);
					
					//Session::instance()->set('alert', __('contact.key_occuped_'.$check));
					$arrAlert[]=array('actionResult'=>2, 'actionDesc'=>__('guest.key_occuped', array(':idcard'=>$idcard, ':id_pep'=>$anypeople->id_pep,':name'=>iconv('CP1251', 'UTF-8',$anypeople->name),':surname'=>iconv('CP1251', 'UTF-8',$anypeople->surname),':patronymic'=>iconv('CP1251', 'UTF-8',$anypeople->patronymic))));
					Session::instance()->set('arrAlert',$arrAlert);
				}
				
		$this->redirect('guests/guest');
			break;
			
			case 'forceexit':// ручная отметка о выходе
				
				//Model::factory('Guest')->forceexit($id);
				$guest=new Guest(Arr::get($_POST,'id_pep', 0));
				//echo Debug::vars('291', $guest->id_pep); exit;
				if($guest->forceexit()==0){
					// перемещаю в архив
					$guest->moveToArchive();
					$alert=__('guest.forceexitOK', array(':name'=>iconv('CP1251', 'UTF-8',$guest->name),':surname'=>iconv('CP1251', 'UTF-8',$guest->surname),':patronymic'=>iconv('CP1251', 'UTF-8',$guest->patronymic)));
					Session::instance()->set('alert',$alert);
					
				} else {
					$alert=__('guest.forceexitErr', array(':name'=>iconv('CP1251', 'UTF-8',$guest->name),':surname'=>iconv('CP1251', 'UTF-8',$guest->surname),':patronymic'=>iconv('CP1251', 'UTF-8',$guest->patronymic)));
					Session::instance()->set('alert',$alert);
					
				}
					$this->redirect('guests');
			break;
			
			case 'reissue':// выдача карты уже известному гостю
				//проверка что карта не выдана какому-нибудь гостю
				$key=new Keyk($idcard);
				$check=$key->check(1);
				if(is_null($check)){
					
					//echo Debug::vars('342', Arr::get($_POST,'id_pep', 0), Arr::get($_POST, 'idcard', null)); exit;
					$guest=new Guest(Arr::get($_POST,'id_pep', 0));
					
					$key->id_card=$idcard;
							$key->timestart=Arr::get($_POST, 'carddatestart');
							$key->timeend=Arr::get($_POST, 'carddateend');
							$key->id_pep=$guest->id_pep;
							
							//присвоедние карты RFID
							if($key->addRfid()==0) { ;//сохраняю карту RFID
									// перемещаю гостя в Гость
								$guest->moveToGuest();	
									$alert=__('guest.addRfidOk', array(':id_card'=>$key->id_card));
							};
				Session::instance()->set('alert', $alert);
				
				} else {
					//карта выдана сотруднику с id_pep=$check
					
					$anypeople=new Guest($check);
					
					//Session::instance()->set('alert', __('contact.key_occuped_'.$check));
					$alert=__('guest.key_occuped', array(':idcard'=>$idcard, ':id_pep'=>$anypeople->id_pep,':name'=>iconv('CP1251', 'UTF-8',$anypeople->name),':surname'=>iconv('CP1251', 'UTF-8',$anypeople->surname),':patronymic'=>iconv('CP1251', 'UTF-8',$anypeople->patronymic)));
					
					$arrAlert[]=array('actionResult'=>2, 'actionDesc'=>$alert);
					
					Session::instance()->set('arrAlert',$arrAlert);
				
				}
				
				
			break;
	}
			
		//$this->redirect('guests');
		$this->redirect('guests/edit/' . $id);
	}

	
	/*
	Регистрация нового гостя или редактирование уже зарегистрированного.
	входные параметры:
	id_pep 
	mode - режима редактирования (0 - активный гость, 1 - гость в архиве)
	
	*/
	

	public function action_edit()
	{
	
		$id_pep=$this->request->param('id');
		$mode=$this->request->param('mode');
		//echo Debug::vars('357', $_GET, $id_pep, $mode ); exit;
		$force_org=$this->request->query('id_org');//наличие этого параметра означает, что надо выбрать именно указанную организацию
		
		$org_tree = Model::Factory('Company')->getOrgList();// получить список организаций.
		
		$fl = $this->session->get('alert');
		$arrAlert = $this->session->get('arrAlert');
		$this->session->delete('alert');
		$this->session->delete('arrAlert');
		
		$this->template->content = View::factory('guests/edit')
			->bind('id_pep', $id_pep)
			->bind('contact', $contact)
			->bind('alert', $fl)
			->bind('arrAlert', $arrAlert)
			->bind('contact_acl', $contact_acl)
			->bind('org_tree', $org_tree)
			->bind('force_org', $force_org)
			->bind('check_acl', $check_acl)
			->bind('companies', $companies)
			->bind('cardlist', $cardlist)
			->bind('mode', $mode)
			//->bind('photo', $photo);
			;
	}

	public function action__view()
	{
		$id=$this->request->param('id');
		$contact = Model::factory('Contact')->getContact($id);
		if (!$contact) $this->redirect('guests');
		$companies = Model::factory('Company')->getNames(true);
		
		$this->template->content = View::factory('guests/view')
			->bind('contact', $contact)
			->bind('companies', $companies);
	}
	
	
	
	public function action_history()
	{
		$id=$this->request->param('id');
		$contact = Model::factory('Contact')->getContact($id);//Получаю контакт по его id
		if (!$contact) $this->redirect('guests');//если контакта нет, то перенаправление на список контактов 
		$data = History::getHistory($id);// беру историю для указанного контакта историю (контроллер History.php, метод getHistory($user))
		
		$this->template->content = View::factory('guests/history')//вызываю вью contacts/history.php
			->bind('contact', $contact)
			->bind('data', $data)
			->bind('id', $id);
	}
	
	public function action_delete()
	{
		//echo Debug::vars('372', $_GET, $_POST, $this->request->param('id')); exit;
		$id_pep=$this->request->param('id');
		$guest=new Guest($id_pep);
		if($guest->delOnIdPep() == 0) {
			
			$alert = __('guest.delOnIdPepOk', array(':surname'=>iconv('CP1251', 'UTF-8',$guest->surname),':name'=>iconv('CP1251', 'UTF-8',$guest->name),':patronymic'=>iconv('CP1251', 'UTF-8',$guest->patronymic),':id_pep'=>$guest->id_pep,':tabnum'=>$guest->tabnum));
			
		} else {
			
			$alert=__('guest.delOnIdPepErr', array(':id_pep'=>$guest->id_pep));
		}
		Session::instance()->set('alert', $alert);
		
		
		$this->redirect('guests');
	}
	
	public function action__addcard_()//удалять
	{
		$id=$this->request->param('id');
		$contact = Model::factory('Contact')->getContact($id);
		$anames = AccessName::getList();
		$card = array();
		
		$this->template->content = View::factory('guests/card')
			->bind('contact', $contact)
			->bind('anames', $anames);
	}
	
	public function action_addgrz()
	{
		$id=$this->request->param('id');
		$contact = Model::factory('Contact')->getContact($id);
		$anames = AccessName::getList();
		$card = array();
		
		$this->template->content = View::factory('guests/grz')
			->bind('contact', $contact)
			->bind('anames', $anames);
	}
	
	
	/*
	Вывод информации по карте
	*/
	public function action__card_()
	{
		$id=$this->request->param('id');
		$card = Model::factory('Card')->getCard($id);
		
		if ($id != "0" && !$card) $this->redirect('/');
		$contact = Model::factory('Contact')->getContact($card['ID_PEP']);
		$contact_acl = Model::factory('Contact')->contact_acl($card['ID_PEP']);
		
		$loads = Model::factory('Card')->getLoads($card['ID_CARD']);
		$anames = AccessName::getList();

		$fl = $this->session->get('alert');
		$this->session->delete('alert');
		
		//переключатель view
		$viewList=array(
		1=>'card',
		4=>'grz');
		
		$this->template->content = View::factory('guests/'.Arr::get($viewList, Arr::get($card, 'ID_CARDTYPE')))
			->bind('contact', $contact)//данные о контакте (ФИО)
			->bind('contact_acl', $contact_acl)//категории доступа, выданные контакту
			->bind('card', $card)// данные о карте
			->bind('loads', $loads)//данные о заргузке карты в контроллеры
			->bind('multiple', $multiple)//не используется
			->bind('anames', $anames)//Перечень всех категорий доступа. не используется
			->bind('alert', $fl)//сообщение alert
			->bind('id', $id);//номер карты
	}
	
	
	/*
	1.01.2023 Получить список идентификаторов
	*/
	public function action_cardlist()
	{
		$id=$this->request->param('id');
		$contact = Model::factory('Contact')->getContact($id);
		if (!$contact) $this->redirect('guests');
		$cards = Model::factory('Card')->getListByPeople($id);

		$fl = $this->session->get('alert');
		$this->session->delete('alert');
		
		$this->template->content = View::factory('guests/cardlist')
			->bind('contact', $contact)
			->bind('cards', $cards)
			->bind('alert', $fl)
			->bind('id', $id);
	}
	
	/*
	9.08.2023
	Вывод списка категорий доступа, выданных пиплу
	
	*/
	public function action_acl()
	{
		$id=$this->request->param('id');
		$contact = Model::factory('Contact')->getContact($id);//информация о контакте
		$contact_acl = Model::factory('Contact')->contact_acl($id);//список категорий доступа, выданных контакту
		if ($id != "0" && !$contact) $this->redirect('guests');
		$isAdmin = Auth::instance()->logged_in('admin');
		$companies = Model::factory('Company')->getNames($isAdmin ? null : Auth::instance()->get_user());
		

		$fl = $this->session->get('alert');
		$this->session->delete('alert');
		
		$this->template->content = View::factory('guests/acl')
			->bind('contact', $contact)
			->bind('alert', $fl)
			->bind('contact_acl', $contact_acl)
			->bind('companies', $companies);
	}
	
	
	/*
	17.08.2023
	Проверка категорий доступа для контакта
	*/
	public function checkACL($id, $aclList)
	{
				if(!$aclList)
		{
			//если массив нового набора категорий доступа пуст, то очищаю таблицу ss_accessuser для этого пипла
			$resultDelAcl=Model::factory('Contact')->clear_contact_acl($id);//удаляю все из таблицы ss_accessuser
			
		} else {
			//если массив нового набора категорий доступа НЕ пуст, то начинаю обработку этого массива
			
			foreach($aclList as $key=>$value)
			{
				$source[]=$key;//это массив вновь созданного набора категорий доступа в виде, удобном для последующего сравнения
			}
		
			//смотрим какие категории доступа уже есть у пипла
			$contact_acl = Model::factory('Contact')->contact_acl($id);//список категорий доступа, уже имеющихся у пипла
			if(!$contact_acl)
			{
				//если категорий доступа ранее не было выдано, то надо их просто добавить
				//echo Debug::vars('284', $aclList); exit;
				foreach($aclList as $key=>$value)
					{
						//echo Debug::vars('288',$value, $resultAddAcl, $id, $value); exit;
						$resultAddAcl=Model::factory('Contact')->add_contact_acl($id, $key );
					}
				
			} else {
				//а если категории доступа ранее выданы, то надо и убавить, и добавить
				foreach ($contact_acl as $key=>$value)
				{
					$oldACL[]=Arr::get($value, 'ID_ACCESSNAME');// это массив уже имеющихся категорий доступа в виде, удобном для последующего сравнения
				}
				
					//поиск категорий доступа, которые необходимо удалить. Это элементы, которые есть в "старом" наборе, но которых нет в в новом наборе
					
					$aclForDel=array_diff($oldACL, $source);
					$resultDelAcl=-1;
					if(!$aclForDel)
					{
						//зарегистрированных категорий доступа нет, удалять ничего не надо 
					} else {
						//зарегистрированные категории доступа имеются, удаляем их 
						foreach($aclForDel as $key=>$value)
						{
							$resultDelAcl=Model::factory('Contact')->del_contact_acl($id, $value );
						}
					}
					//поиск категорий доступа, которые необходимо добавить. Это элементы, которые есть в новом наборе, но которых нет в старом наборе
					$aclForAdd=array_diff($source, $oldACL);
					$resultAddAcl=-1;
					
					foreach($aclForAdd as $key=>$value)
					{
						//echo Debug::vars('288',$value, $resultAddAcl, $id, $value); exit;
						$resultAddAcl=Model::factory('Contact')->add_contact_acl($id, $value );
					}
			}
		}
		
	}
	
	/*
	Обновление списка категорий доступа, выданных пиплу
	входные параметры:
	id - id_pep пользователя, у которого меняют набор категорий доступа
	"aclList" => array(2) ( - новый набор категорий доступа
        213 => string(1) "1"
        1 => string(1) "1"
	
	*/
	public function action_saveACL()
	{
		//echo Debug::vars('274', $_POST); exit;
		$id=$this->request->post('id');
		$aclList=$this->request->post('aclList');
		//echo Debug::vars('274', $_POST, $id, $aclList); exit;
		$this->checkACL($id, $aclList);
		//echo Debug::vars('254',$source, $oldACL , $aclForDel, $aclForAdd, $resultDelAcl, $resultAddAcl); exit;
		$this->redirect('guests/acl/'.$id);
	}
	
	
	
	public function action__deletecard_()
	{
		$id=$this->request->param('id');
		$card = Model::factory('Card')->getCard($id);
		$people = $card['ID_PEP'];
		Model::factory('Card')->delete($id);
		
		Session::instance()->set('alert', __('cards.deleted'));
		$this->redirect('guests/cardlist/' . $people);
	}
	
	public function action_reload()
	{
		$id=$this->request->param('id');
		$card = Model::factory('Card')->getCard($id);
		
		Model::factory('Card')->reload($id);
		
		Session::instance()->set('alert', __('cards.deleted'));
		$this->redirect('guests/cardlist');
	}
	
	
	
	public function action__savecard_()
	{
		//echo Debug::vars('348', $_POST ); exit;
		$idpeople	= Arr::get($_POST, 'id');
		$idcard		= str_pad(strtoupper(Arr::get($_POST, 'idcard')), 8, "0", STR_PAD_LEFT);//это при регистрации карты
		$idcard0	= Arr::get($_POST, 'id0', null);// а это передатеся при редактировании карты
		$datestart	= Arr::get($_POST, 'carddatestart');
		$dateend	= Arr::get($_POST, 'carddateend', '');
		$useenddate	= (Arr::get($_POST, 'useenddate') !==NULL)? '1':'0';
		$cardstate	= Arr::get($_POST, 'cardstate', 0);
		$isactive	= (Arr::get($_POST, 'cardisactive') !==NULL)? '1':'0';
		$idaccess	= Arr::get($_POST, 'aname');
		$id_cardtype	= Arr::get($_POST, 'id_cardtype');
		$note	= Arr::get($_POST, 'note');
		
		
		
		if ($idcard0) {
			// update
			//echo Debug::vars('363 update' ); exit;
			Model::factory('Card')->update($idpeople, $idcard, $datestart, $dateend, $useenddate, $cardstate, $isactive, $idaccess, $note);
			Session::instance()->set('alert', __('cards.updated'));
		} else {
			//save
			
			Model::factory('Card')->save($idpeople, $idcard, $datestart, $dateend, $useenddate, $cardstate, $isactive, $idaccess, $id_cardtype, $note);
			Session::instance()->set('alert', __('cards.saved'));
			
		}
		//echo Debug::vars('373 after save '.$idcard); exit;
		$this->redirect('guests/card/' . $idcard);
	}
	
	public function action_config()
	{
		
		$org_tree = Model::Factory('Company')->getOrgList();// получить список организаций.
		
		$guestConfig=Model::factory('Guest');
		//$guestConfig->init();
		//echo Debug::vars('555', $guestConfig, $guestConfig->idOrgGuest ); exit;
		$this->template->content = View::factory('guests/config')
		->bind('alert', $fl)
		->bind('guestConfig', $guestConfig)
		->bind('org_tree', $org_tree)
			;
		
	}
	
	
	
	public function action_saveconfig()
	{
		//echo Debug::vars('527', $_POST); exit;
		$guestConfig=Model::factory('Guest');
		$guestConfig->idOrgGuest=Arr::get($_POST, 'idOrgGuest');
		$guestConfig->idOrgGuestArchive=Arr::get($_POST, 'idOrgGuestArchive');
		$guestConfig->saveconfig();
		$this->redirect('guests/config');
		
	}
	
	

	
	
	/*
	тестирование
	*/
		public function action_testAddGuest()
		{
			
			$testKey='33333333';
			$key=new Keyk();
			$tabnum='testTabNum33333333';
			$guest=new Guest();
			$guest->tabnum=$tabnum;
			$guest->delOnTabNum();
			//echo Debug::vars('219', $guest->actionResult,  $guest->actionDesc); exit;
			$arrAlert[]=array('actionResult'=>$guest->actionResult, 'actionDesc'=>$guest->actionDesc);
					$guest->name='nameTest';
					$guest->patronymic='patronymic';
					$guest->surname='surname';
					$guest->numdoc='numdoc';
					$guest->datedoc='1.02.2003';
					$guest->note='notetest';
					
					
					$guest->org=2;
					$guest->addGuest();//добавляю ФИО и заметки
					
					if($guest->actionResult == 3) $this->redirect('errorpage?err='.$guest->actionDesc);
						$arrAlert[]=array('actionResult'=>$guest->actionResult, 'actionDesc'=>$guest->actionDesc);
						
					$guest->setTabNum($tabnum);//табельный номер
						$arrAlert[]=array('actionResult'=>$guest->actionResult, 'actionDesc'=>$guest->actionDesc);
					
				
					
					$key->id_card=$testKey;
					$key->timestart='29.12.2023';
					$key->timeend='31.12.2023';
					$key->id_pep=$guest->id_pep;
					
					$key->addRfid();//сохраняю карту RFID
					
					if($key->actionResult == 3) $this->redirect('errorpage?err='.str_replace (array("\r\n", "\n", "\r"), ' ', $key->actionDesc));
					//if($key->actionResult == 3) $this->redirect('errorpage?err='.__('err_db', array('err_mess'=>$key->actionDesc)));
					
					
						$arrAlert[]=array('actionResult'=>$key->actionResult, 'actionDesc'=>$key->actionDesc);
					
					$guest->addDoc();//добавляю данные по документу
						$arrAlert[]=array('actionResult'=>$guest->actionResult, 'actionDesc'=>$guest->actionDesc);
					
					Session::instance()->set('arrAlert',$arrAlert);
					$this->redirect('guests/edit/' . $guest->id_pep);
			
		}

	
}
