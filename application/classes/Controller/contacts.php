<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Contacts extends Controller_Template
{
	public $template = 'template';
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
	
	
	
	
	public function action_upload()
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
При поиске параметр фильтра сохраняется в сессию, в ключ search_contact
Затем вызывается index с указанным параметров для поиска.
Поиск - это один указанный id_pep
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
	
	/**
	$filter - текст для поиска фамилии
	*/
	public function action_index($filter = null)
	{
		
		$isAdmin = Auth::instance()->logged_in('admin');
		
		$contacts = Model::factory('Contact');
		
		
		//определяю режим показа: 
		// is_active = 0, что означает работу с удаленными сотрудниками.
		
		if(Session::instance()->get('viewDeletePeopleOnly') == 1) {
			$contacts ->peopleIsActive=0;
			//Session::instance()->set('viewDeletePeopleOnly', 0);
		} else {
			$contacts ->peopleIsActive=1;
		}
		
		//количество пиплов, доступные текущему авторизованному пользователю
		$q = $contacts->getCountUser(Arr::get(Auth::instance()->get_user(), 'ID_ORG'), iconv('UTF-8', 'CP1251', $filter));
		
		$pagination = new Pagination(array(
			'uri_segment' => 2,
			'total_items' => $q,
			'style' => 'floating',
			'items_per_page' => $this->listsize,
			'auto_hide' => true,
		));
		
		$list = $contacts->getListUser(Arr::get(Auth::instance()->get_user(), 'ID_ORG'), Arr::get($_GET, 'page', 1), $this->listsize, iconv('UTF-8', 'CP1251', $filter));
		
		$fl = $this->session->get('alert');
		$this->session->delete('alert');
		
		$showphone = $this->session->get('showphone', 0);
		
		$this->template->content = View::factory('contacts/list')
			->bind('total_items', $q)
			->bind('people', $list)
			->bind('alert', $fl)
			->bind('showphone', $showphone)
			->bind('filter', $filter)
			->bind('pagination', $pagination);
	}

	public function action_save()
	{
		//echo Debug::vars('70', $_POST); exit;
		$id			= Arr::get($_POST, 'id_pep');
		$surname	= Arr::get($_POST, 'surname');
		$name		= Arr::get($_POST, 'name','');
		$patronymic	= Arr::get($_POST, 'patronymic','');
		$datebirth	= Arr::get($_POST, 'datebirth', 'NULL');
		$numdoc		= Arr::get($_POST, 'numdoc', '');
		$datedoc	= Arr::get($_POST, 'datedoc', '');
		$workstart	= Arr::get($_POST, 'workstart', '09:00:00');
		$workend	= Arr::get($_POST, 'workend', '18:00:00');
		$active		= Arr::get($_POST, 'active', 1);
		$peptype	= Arr::get($_POST, 'peptype', 0);
		$post		= Arr::get($_POST, 'post', null);
		$tabnum		= Arr::get($_POST, 'tabnum');
		$login		= Arr::get($_POST, 'login', '');
		$password	= Arr::get($_POST, 'password', '');
		$org		= Arr::get($_POST, 'id_org');
		$inherit	= Arr::get($_POST, 'inherit', 0);
		$note		= Arr::get($_POST, 'note', null);
		$id_org_old		= Arr::get($_POST, 'id_org_old', null);

		$contact = Model::factory('Contact');

		if ($id == 0) { // это добавление нового пользователя, т.к. $id (она же id_pep) равна 0.
			//echo Debug::vars('91', $surname, $name, $patronymic, $datebirth, $numdoc, $datedoc, $workstart, $workend, $active, $peptype, $post, $tabnum, $org, $login, $password); exit;
			$id = $contact->save($surname, $name, $patronymic, $datebirth, $numdoc, $datedoc, $workstart, $workend, $active, $peptype, $post, $tabnum, $org, $login, $password, $note);
			
			if($inherit == 1) $contact->setInheritAcl($id);
			Session::instance()->set('alert', __('contact.saved'));
		} else {
			$contact->update($id, $surname, $name, $patronymic, $datebirth, $numdoc, $datedoc, $workstart, $workend, $active, $peptype, $post, $tabnum, $org, $login, $password, $note);
			echo Debug::vars('101 ', $id, $id_org_old, $org); //exit;
			if(($id_org_old != $org) and ($inherit ==1)) // если есть изменения в организации, и включено наследование, то надо поменять и набор категорий доступа
			{
				echo Debug::vars('104 набор категорий доступа ИЗМЕНИЛСЯ.'); //exit;	
				$aclList= Model::factory('company')->company_acl($org);//список категорий доступа, уже выданных организации;//передаю новый набор категорий доступа
				
				foreach($aclList as $key=>$value)
				{
					
					$res[Arr::get($value, 'ID_ACCESSNAME')]=1;
				}
							
				//echo Debug::vars('114 ', $aclList, $res); exit;
				$this->checkACL($id, $res);
				//echo Debug::vars('106', $aclList, $res); exit;
		
			} else {
				//echo Debug::vars('120 набор категорий доступа не менялся.'); exit;
				Session::instance()->set('alert', __('120 набор категорий доступа не менялся'));
			}
			Session::instance()->set('alert', __('contact.updated'));
		}
		//$this->redirect('contacts');
		$this->redirect('contacts/edit/' . $id);
	}

	
	
	public function action_edit()
	{
		$id=$this->request->param('id');
		$force_org=$this->request->query('id_org');//наличие этого параметра означает, что надо выбрать именно указанную организацию
		$contact = Model::factory('Contact')->getContact($id);//персональные данные контакта
		//$photo = Model::factory('Contact')->getPicture($id);//персональные данные контакта
		$contact_acl = Model::factory('Contact')->contact_acl($id);//список категорий доступа, выданных контакту
		$check_acl = Model::factory('Contact')->check_acl($id);//получить список категорий доступа родительской организации для сверки с текущим списком категорий для контакта: совпадает или нет? 0 -совпадает, 1 - не совпадает.
		if ($id != "0" && !$contact) $this->redirect('contacts');
		$isAdmin = Auth::instance()->logged_in('admin');
		$companies = Model::factory('Company')->getNames($isAdmin ? null : Auth::instance()->get_user());
		$org_tree = Model::Factory('Company')->getOrgList();// получить список организаций.
		
		$fl = $this->session->get('alert');
		$this->session->delete('alert');
		
		$this->template->content = View::factory('contacts/edit')
			->bind('contact', $contact)
			->bind('alert', $fl)
			->bind('contact_acl', $contact_acl)
			->bind('org_tree', $org_tree)
			->bind('force_org', $force_org)
			->bind('check_acl', $check_acl)
			->bind('companies', $companies)
			//->bind('photo', $photo);
			;
	}

	public function action__view()
	{
		$id=$this->request->param('id');
		$contact = Model::factory('Contact')->getContact($id);
		if (!$contact) $this->redirect('contacts');
		$companies = Model::factory('Company')->getNames(true);
		
		$this->template->content = View::factory('contacts/view')
			->bind('contact', $contact)
			->bind('companies', $companies);
	}
	

	
	public function action_history()
	{
		$id=$this->request->param('id');
		$contact = Model::factory('Contact')->getContact($id);//Получаю контакт по его id
		if (!$contact) $this->redirect('contacts');//если контакта нет, то перенаправление на список контактов 
		$data = History::getHistory($id);// беру историю для указанного контакта историю (контроллер History.php, метод getHistory($user))
		
		$this->template->content = View::factory('contacts/history')//вызываю вью contacts/history.php
			->bind('contact', $contact)
			->bind('data', $data)
			->bind('id', $id);
	}
	
	/*
	Удаление сотрудника 
	
	*/
	
	public function action_delete()
	{
		/* $id=$this->request->param('id');
		Model::factory('Contact')->delete($id);
		Session::instance()->set('alert', __('contact.deleted'));
		$this->redirect('contacts');
		 */
		$id_pep=$this->request->param('id');
		$key=new Keyk();
		$contact=new Contact($id_pep);
		$alert='';
		/* удаляю карту сотрудника*/
		if($key->delCardForPeople($id_pep) == 0){
			$alert=__('cards.deletedOk', array(':name'=>iconv('CP1251', 'UTF-8',$contact->name),':surname'=>iconv('CP1251', 'UTF-8',$contact->surname),':patronymic'=>iconv('CP1251', 'UTF-8',$contact->patronymic)));
			
		} else {
			
			// карта не удалена, но такого быть не может
		
		}
		
		/* делаю сотрудника неактивным*/
		if($contact->setNotActiveOnIdPep()){
			$alert.='<br>'.__('contact.setNotActiveOK', array(':name'=>iconv('CP1251', 'UTF-8',$contact->name),':surname'=>iconv('CP1251', 'UTF-8',$contact->surname),':patronymic'=>iconv('CP1251', 'UTF-8',$contact->patronymic)));
				
		} else {
			$alert.='<br>'.__('contact.setNotActiveErr', array(':name'=>iconv('CP1251', 'UTF-8',$contact->name),':surname'=>iconv('CP1251', 'UTF-8',$contact->surname),':patronymic'=>iconv('CP1251', 'UTF-8',$contact->patronymic)));
	
		}
		
		Session::instance()->set('alert',$alert);
		$this->redirect('contacts');
	}
	
	/*
	7.01.2024
	Сделать неактивного пипла активным
	
	*/
	public function action_restore()
	{
		$id_pep=$this->request->param('id');
		/* Model::factory('Contact')->restore($id);
		Session::instance()->set('alert', __('contact.restore'));
		$this->redirect('contacts'); */
		$contact=new Contact($id_pep);
		
		/* делаю сотрудника АКТИВНЫМ*/
		if($contact->setIsActiveOnIdPep()==0){
			$alert=__('contact.setIsActiveOK', array(':name'=>iconv('CP1251', 'UTF-8',$contact->name),':surname'=>iconv('CP1251', 'UTF-8',$contact->surname),':patronymic'=>iconv('CP1251', 'UTF-8',$contact->patronymic)));
				
		} else {
			$alert=__('contact.setIsActiveErr', array(':name'=>iconv('CP1251', 'UTF-8',$contact->name),':surname'=>iconv('CP1251', 'UTF-8',$contact->surname),':patronymic'=>iconv('CP1251', 'UTF-8',$contact->patronymic)));
	
		}
		
		
		
		Session::instance()->set('alert',$alert);
		$this->redirect('contacts');
	}
	
	
	
	
	public function action___addcard()
	{
		$id=$this->request->param('id');
		$contact = Model::factory('Contact')->getContact($id);
		$anames = AccessName::getList();
		$card = array();
		
		$this->template->content = View::factory('contacts/card')
			->bind('contact', $contact)
			->bind('anames', $anames);
	}
	
	public function action___addgrz()
	{
		$id=$this->request->param('id');
		$contact = Model::factory('Contact')->getContact($id);
		$anames = AccessName::getList();
		$card = array();
		
		$this->template->content = View::factory('contacts/grz')
			->bind('contact', $contact)
			->bind('anames', $anames);
	}
	
	/*
	19.12.2023 Установка метки is_active = 0, что означает работу с удаленными сотрудниками.
	is_active = 0, что означает работу с удаленными сотрудниками.
	is_active = 1, что означает работу с неудаленными сотрудниками.
	*/
	public function action_deletedList()
	{
		$this->session->set('viewDeletePeopleOnly', '1');
		$this->redirect('contacts');
	}
	
	
	/*
	19.12.2023 Установка метки is_active = 0, что означает работу с удаленными сотрудниками.
	is_active = 0, что означает работу с удаленными сотрудниками.
	is_active = 1, что означает работу с неудаленными сотрудниками.
	*/
	public function action_activeOnlyList()
	{
		$this->session->set('viewDeletePeopleOnly', '0');
		$this->redirect('contacts');
	}
	
	
	/*
	Вывод информации по карте
	*/
	public function action_card()
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
		// имя файла для редактирования вызывается из настроек viewList. Это либо card для идентификаторов RFID, либо grz для номерного знака
		$this->template->content = View::factory('contacts/'.Arr::get($viewList, Arr::get($card, 'ID_CARDTYPE')))
			->bind('contact', $contact)//данные о контакте (ФИО)
			->bind('contact_acl', $contact_acl)//категории доступа, выданные контакту
			->bind('card', $card)// данные о карте
			->bind('loads', $loads)//данные о заргузке карты в контроллеры
			->bind('multiple', $multiple)//не используется
			->bind('anames', $anames)//Перечень всех категорий доступа. не используется
			->bind('alert', $fl)//сообщение alert
			->bind('id', $id);//номер карты
	}
	
	public function action_cardlist()
	{
		$id=$this->request->param('id');
		$contact = Model::factory('Contact')->getContact($id);
		if (!$contact) $this->redirect('contacts');
		$cards = Model::factory('Card')->getListByPeople($id);

		$fl = $this->session->get('alert');
		$this->session->delete('alert');
		
		$this->template->content = View::factory('contacts/cardlist')
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
		if ($id != "0" && !$contact) $this->redirect('contacts');
		$isAdmin = Auth::instance()->logged_in('admin');
		$companies = Model::factory('Company')->getNames($isAdmin ? null : Auth::instance()->get_user());
		

		$fl = $this->session->get('alert');
		$this->session->delete('alert');
		
		$this->template->content = View::factory('contacts/acl')
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
	public function action___saveACL()
	{
		//echo Debug::vars('274', $_POST); exit;
		$id=$this->request->post('id');
		$aclList=$this->request->post('aclList');
		//echo Debug::vars('274', $_POST, $id, $aclList); exit;
		$this->checkACL($id, $aclList);
		//echo Debug::vars('254',$source, $oldACL , $aclForDel, $aclForAdd, $resultDelAcl, $resultAddAcl); exit;
		$this->redirect('contacts/acl/'.$id);
	}
	
	
	
	public function action___deletecard()
	{
		$id=$this->request->param('id');
		$card = Model::factory('Card')->getCard($id);
		$people = $card['ID_PEP'];
		Model::factory('Card')->delete($id);
		
		Session::instance()->set('alert', __('cards.deleted'));
		$this->redirect('contacts/cardlist/' . $people);
	}
	
	public function action___reload()
	{
		$id=$this->request->param('id');
		$card = Model::factory('Card')->getCard($id);
		
		Model::factory('Card')->reload($id);
		
		Session::instance()->set('alert', __('cards.deleted'));
		$this->redirect('contacts/cardlist');
	}
	
	
	
	public function action___savecard()
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
		$this->redirect('contacts/card/' . $idcard);
	}
	
	

	
}
