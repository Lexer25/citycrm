<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Companies extends Controller_Template
{
	public $template = 'template';
	private $listsize;
	private $session;
	
	public function before()
	{
		parent::before();
		if (!Auth::instance()->logged_in()) $this->redirect('/');
		
		$this->session = Session::instance();
		I18n::$lang		= $this->session->get('language', 'en-us');
		$this->listsize = $this->session->get('listsize', 10);
	}
	
	/**
	5.12.2023
	при переходе на search данные из POST записываются в 
	Ожидаемый параметр q - это номер организации, по которой надо вывести информацию.
	этот параметр сохраняется в сессию (для последующего вывода на формах
	и передается в index.
	*/
	public function action_search()
	{
		$pattern = Arr::get($_POST, 'q', null);
		if ($pattern) {
			$this->session->set('search_company', $pattern);
		} else {
			$pattern = $this->session->get('search_company', '');
		}
		$this->action_index($pattern);
	}
	
	public function action_index($filter = null)
	{
		//смотрю указание на родительскую организацию для вывода списка организаций.
		$parent_org=$this->request->query('parent');
		if(is_null($parent_org)) $parent_org=Arr::get(Auth::instance()->get_user(), 'ID_ORG');// если parent_org не указан, то беру ID_ORG подчиняемой организации
		
		$isAdmin = Auth::instance()->logged_in('admin');
		
		//подсчет количества элементов в списке
		$companies = Model::factory('Company');
		
		$count_q = Model::factory('Company')->getCountUser(Arr::get(Auth::instance()->get_user(), 'ID_PEP'), $parent_org, $filter);// сколько всего организаций для авторизованного пользователя?
		
		//echo Debug::vars('test', $count_q, Arr::get(Auth::instance()->get_user(), 'ID_PEP')); exit;
		$pagination = new Pagination(array(
			'uri_segment' => 2,
			'total_items' => $count_q,
			'style' => 'classic',
			'items_per_page' => $this->listsize,
			'auto_hide' => true,
		));
		
		$list = $companies->getListAdmin(
				Arr::get(Auth::instance()->get_user(), 'ID_PEP'),
				Arr::get($_GET, 'page', 1),
				$this->listsize,
				$parent_org,
				$filter
				);
		
		$fl = $this->session->get('alert');
		$this->session->delete('alert');
		$tmp = $this->session->get('company_columns', null);
		if ($tmp)
			$company_columns = unserialize($tmp);
		else
			$company_columns = array(
				'ID_ORG'		=> true,
				'NAME'			=> true,
				'PARENT'		=> $isAdmin,
				'DIVCODE'		=> true,
				'ACCESSNAME'	=> $isAdmin,
			);
	
		
		$this->template->content = View::factory('companies/list')
			->bind('companies', $list)
			->bind('alert', $fl)
			->bind('col1', $company_columns)
			->bind('filter', $filter)
			->bind('pagination', $pagination)
			->bind('org_tree', $org_tree)
			;
	}
	
	
	/*
	18.12.2023
	Вывод списк сотрудников указанной организации
	*/
	public function action_people()
	{
		$id=$this->request->param('id');
		$company = Model::factory('company')->getCompany($id);
		if (!$company) $this->redirect('companies');
		$isAdmin = Auth::instance()->logged_in('admin');
		
		$contacts = Model::factory('Contact');
		
		$q = $contacts->getCountByOrg($id);

		$pagination = new Pagination(array(
			'uri_segment' => 2,
			'total_items' => $q,
			'style' => 'floating',
			'items_per_page' => $this->listsize,
			'auto_hide' => true,
		));
		
		$list = $contacts->getListByOrg(Arr::get($_GET, 'page', 1), $this->listsize, $id);
		$fl = null;
		$showphone = $this->session->get('showphone', 0);
		$filter = null;
		$hidesearch = true;
		
		$this->template->content = View::factory('contacts/list')
			->bind('people', $list)
			->bind('alert', $fl)
			->bind('company', $company)
			->bind('showphone', $showphone)
			->bind('hidesearch', $hidesearch)
			->bind('filter', $filter)
			->bind('pagination', $pagination);
	}
	
	/*
	10.08.2023
	Получить и вывести на экран Список категорий доступа, присвоенных организации
	*/
	public function action_acl()
	{
		$id=$this->request->param('id');
		$company = Model::factory('company')->getCompany($id);//информация об организации
		$company_acl = Model::factory('company')->company_acl($id);//список категорий доступа, уже выданных организации
		if (!$company) $this->redirect('companies');
		$isAdmin = Auth::instance()->logged_in('admin');
		
		
		$fl = null;
		$showphone = $this->session->get('showphone', 0);
		$filter = null;
		$hidesearch = true;
		
		$this->template->content = View::factory('companies/acl')
			->bind('alert', $fl)
			->bind('company', $company)
			->bind('company_acl', $company_acl)
			->bind('pagination', $pagination);
	}
	
		/*
	Обновление списка категорий доступа, выданных организации
	входные параметры:
	id - id_org организации, у которого меняют набор категорий доступа
	"aclList" => array(2) ( - новый набор категорий доступа
        213 => string(1) "1"
        1 => string(1) "1"
	
	*/
	public function action_saveACL()
	{
		
		$id=$this->request->post('id');
		$aclList=$this->request->post('aclList');
		
		if(!$aclList)
		{
			//если массив нового набора категорий доступа пуст, то очищаю таблицу ss_accessuser для этого пипла
			$resultDelAcl=Model::factory('company')->clear_company_acl($id);//удаляю все из таблицы ss_accessuser
			
		} else {
			//если массив нового набора категорий доступа НЕ пуст, то начинаю обработку этого массива
			
			foreach($aclList as $key=>$value)
			{
				$source[]=$key;//это массив вновь созданного набора категорий доступа в виде, удобном для последующего сравнения
			}
		
			//смотрим какие категории доступа уже есть у пипла
			$contact_acl = Model::factory('company')->company_acl($id);//список категорий доступа, уже имеющихся у пипла
			if(!$contact_acl)
			{
				//если категорий доступа ранее не было выдано, то надо их просто добавить
				//echo Debug::vars('284', $aclList); exit;
				foreach($aclList as $key=>$value)
					{
						//echo Debug::vars('288',$value, $resultAddAcl, $id, $value); exit;
						$resultAddAcl=Model::factory('company')->add_company_acl($id, $key );
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
							$resultDelAcl=Model::factory('company')->del_company_acl($id, $value );
						}
					}
					//поиск категорий доступа, которые необходимо добавить. Это элементы, которые есть в новом наборе, но которых нет в старом наборе
					$aclForAdd=array_diff($source, $oldACL);
					$resultAddAcl=-1;
					
					foreach($aclForAdd as $key=>$value)
					{
						//echo Debug::vars('288',$value, $resultAddAcl, $id, $value); exit;
						$resultAddAcl=Model::factory('company')->add_company_acl($id, $value );
					}
			}
		}		
		//echo Debug::vars('254',$source, $oldACL , $aclForDel, $aclForAdd, $resultDelAcl, $resultAddAcl); exit;
		$this->redirect('companies/acl/'.$id);
	}
	
	
	public function action_save()//добавление организации
	{
		//echo Debug::vars('235', $_POST); exit;
		$id		= Arr::get($_POST, 'id');
		$name	= Arr::get($_POST, 'name');
		$code	= Arr::get($_POST, 'code');
		$access	= Arr::get($_POST, 'access');
		$parent = Arr::get($_POST, 'parent');
		$group	= Arr::get($_POST, 'group');

		//$company = Model::factory('company');
		

		if ($id == 0) {
			$company = new Company();
			$company->name=$name;
			$company->parent= ($parent == '')? 1 : $parent;
			if($company->addOrg()==0) {
				$this->session->set('alert', __('company.saved'));
			}
		} else {
			$company = new Company($id);
			$company->id_org=$id;
			$company->name=$name;
			//$company->divcode=$code;
			$company->id_parent= ($parent == '')? 1 : $parent;
			
			$company->updateOrg();
			$this->session->set('alert', __('company.updated'));
		}

		
		$this->redirect('companies/edit/' . $id);
	}
	
	public function action_delete()
	{
		$id_org=$this->request->param('id');
		
		
		$delCompany = new Company($id_org);
		$alert='123 '.$id_org;
		//получить список дочерних организаций.
		$childrenList=$delCompany->getChildIdOrg();
		//echo Debug::vars('278',$id_org, $childrenList); exit;
		if($childrenList){
			//каждой организации меняю родителя на новый
			$alert.=' 281';
			foreach ($childrenList as $key=>$value){
				$alert.=' 283';
				$updCompany=new Company(Arr::get($value, 'ID_ORG'));
				$updCompany->id_parent = $delCompany->id_parent;
				$alert.=' '.$updCompany->id_org;
				
				if($updCompany->setIdParentOrg() == 0) {
					$alert.=__('remove id_org=:id_org<br>', array(':id_org'=>$updCompany->id_org));
				} else {
					
					$alert.=__('NOT remove id_org=:id_org<br>', array(':id_org'=>$updCompany->id_org));
				};
				
			}
			
		}
		//для всех контактов удаляемой организации меняю организацию на 1
		$delCompany->setNewOrgForPeople($delCompany->id_parent);
		$this->session->set('alert', $alert);
		//echo Debug::vars('293', $alert); exit;
		$this->redirect('companies');
	}
	
	public function action_edit()
	{
		$id=$this->request->param('id');
		$isAdmin = Auth::instance()->logged_in('admin');
		
		$company = Model::factory('company');
		$data = $company->getCompany($id);
		if ($id != "0" && !$data) $this->redirect('companies');
		$list = $company->getNames(null);
		
		$acls = $company->getListAccessName();
		$org_tree = Model::Factory('Company')->getOrgList();// получить список организаций.
		
		$fl = $this->session->get('alert');
		$this->session->delete('alert');
		
		
		
		$this->template->content = View::factory('companies/edit')
			->bind('company', $data)
			->bind('parents', $list)
			->bind('org_tree', $org_tree)
			//->bind('groups', $grps)
			->bind('alert', $fl)
			->bind('acl', $acls);
			
	}
	
	public function action_view()
	{
		$id=$this->request->param('id');
		$company = Model::factory('company');

		$data = $company->getCompany($id);
		if (!$data) $this->redirect('companies');
		$list = $company->getNames(null);
		$acls = Accessname::getList();
		
		$this->template->content = View::factory('companies/view')
			->bind('company', $data)
			->bind('parents', $list)
			->bind('acl', $acls);
	}
	
	public function action_groups()
	{
		if (!Auth::instance()->logged_in('admin')) $this->redirect('/');
		
		$fl = $this->session->get('alert');
		$this->session->delete('alert');
		
		$companies = Model::factory('Company');
		$list = $companies->getGroups();
		
		$this->template->content = View::factory('groups/list')
			->bind('groups', $list)
			->bind('alert', $fl);
	}
	
	public function action_groupdelete()
	{
		$id=$this->request->param('id');
		if (!Auth::instance()->logged_in('admin')) $this->redirect('/');
		
		Model::factory('Company')->deleteGroup($id);
		$this->session->set('alert', __('groups.deleted'));
		$this->redirect('companies/groups');
	}
	
	public function action_groupedit()
	{
		$id=$this->request->param('id');
		if (!Auth::instance()->logged_in('admin')) $this->redirect('/');
		if (!(preg_match("/^\d+$/", $id))) $this->redirect('companies/groups');
		$group = Model::factory('Company')->getGroup($id);
		if ($id != "0" && !$group) $this->redirect('companies/groups');
		
		$fl = $this->session->get('alert');
		$this->session->delete('alert');

		$this->template->content = View::factory('groups/edit')
			->bind('alert', $fl)
			->bind('group', $group);
	}
	
	public function action_groupsave()
	{
		if (!Auth::instance()->logged_in('admin')) $this->redirect('/');
		
		$id		= Arr::get($_POST, 'id');
		$name	= iconv('UTF-8', 'CP1251', Arr::get($_POST, 'name'));
		$desc	= iconv('UTF-8', 'CP1251', Arr::get($_POST, 'desc'));
		
		$company = Model::factory('company');
		if ($id == 0) {
			$id = $company->saveGroup($name, $desc);
			$this->session->set('alert', __('group.saved'));
		} else {
			$company->updateGroup($id, $name, $desc);
			$this->session->set('alert', __('group.updated'));
		}
		$this->redirect('companies/groupedit/' . $id);
	}
	
	public function action_grouplist()
	{
		$id=$this->request->param('id');
		if (!(preg_match("/^\d+$/", $id))) $this->redirect('companies/groups');
		$company = Model::factory('Company');
		
		if ($_POST) {
			$old = array_unique(explode('|', Arr::get($_POST, 'list0', array())));
			$new = array_unique(explode('|', Arr::get($_POST, 'list1', array())));
			
			//echo "<pre>";
			//print_r($old);
			//print_r($new);
			//echo "</pre>";
			//die;
			
			$del = array_diff($old, $new);
			foreach ($del as $d)
				if ($d != '')
					$company->removeFromGroup($d, $id); 
			
			$add = array_diff($new, $old);
			foreach ($add as $a)
				if ($a != '')
					$company->addToGroup($a, $id);
			
			$this->session->set('alert', __('group.listsaved'));
			$this->redirect('companies/grouplist/' . $id);
		}
		
		$fl = $this->session->get('alert');
		$this->session->delete('alert');

		$data = $company->getGroup($id);
		
		$list = $company->getNamesWithGroup($id);

		$this->template->content = View::factory('groups/companies')
			->bind('alert', $fl)
			->bind('list', $list)
			->bind('group', $data);
	}
	
	public function action_groupacl()
	{
		$id=$this->request->param('id');
		if (!(preg_match("/^\d+$/", $id))) $this->redirect('companies/groups');
		$company = Model::factory('Company');

		if ($_POST) {
			$data = array();
			$modes = array('o_view', 'o_edit', 'o_add', 'o_delete', 'p_edit', 'p_add', 'p_delete', 'c_edit', 'c_add', 'c_delete');
			$uids = Arr::get($_POST, 'uid');
			foreach ($uids as $uid)
				$data[$uid] = array(
					'o_view'	=> 0,
					'o_edit'	=> 0,
					'o_add'		=> 0,
					'o_delete'	=> 0,
					'p_edit'	=> 0,
					'p_add'		=> 0,
					'p_delete'	=> 0,
					'c_edit'	=> 0,
					'c_add'		=> 0,
					'c_delete'	=> 0);
				foreach ($modes as $mode) {
				$uids = Arr::get($_POST, $mode, array());
				foreach ($uids as $uid)
					$data[$uid][$mode] = 1;
			}
			foreach ($data as $uid => $acl)
				Model::factory('user')->setGroupACL($id, $uid, $acl);

			$this->session->set('alert', __('group.aclsaved'));
			$this->redirect('companies/groupacl/' . $id);
		}
		
		$data = $company->getGroup($id);
		$list = Model::factory('user')->getGroupACL($id);
				
		$fl = $this->session->get('alert');
		$this->session->delete('alert');

		$this->template->content = View::factory('groups/acls')
			->bind('alert', $fl)
			->bind('users', $list)
			->bind('group', $data);
	}
	
	public function addpeople()
	{
			$id=$this->request->param('id');
			$this->redirect('contacts/edit/0');
		
	}
	
}
