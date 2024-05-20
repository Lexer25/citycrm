<?php defined('SYSPATH') or die('No direct script access.');
class Controller_Devices extends Controller_Template { 

public $template = 'template';
	private $listsize;
	private $session;

	public function before()
	{
			
			parent::before();
			$session = Session::instance();
				$this->session = Session::instance();
		I18n::$lang		= $this->session->get('language', 'en-us');
		$this->listsize = $this->session->get('listsize', 10);
		//echo Debug::vars('16', $_SESSION);
	}
	
	/*
	15.08.2023 выодит список контроллеров
	*/
	public function action_index($filter = null)
	{
		
		$q=192;
		$pagination = new Pagination(array(
			'uri_segment' => 2,
			'total_items' => $q,
			'style' => 'classic',
			'items_per_page' => $this->listsize,
			'auto_hide' => true,
		));
		
		//$org_tree = Model::Factory('Company')->getOrgList();// я получил список организаций.
		$org_tree = Model::Factory('Device')->getdeviceListForTree();// я получил список контроллеров и точек прохода.
		//echo Debug::vars('63', $org_tree); exit;
		$org_tree=Model::Factory('treeorg')->make_tree($org_tree, 1);//формирую иерархический список
		//echo Debug::vars('38', $org_tree);exit;
		$fl = $this->session->get('alert');
		$this->session->delete('alert');
		
		
		$list = Model::Factory('device')->getdeviceList(
				Arr::get($_GET, 'page', 1),
				$this->listsize,
				$filter
				);
		
        //$this->template->content = $content;
		$this->template->content = View::factory('device/list')
			->bind('devices', $list)
			->bind('alert', $fl)
			//->bind('col1', $company_columns)
			->bind('filter', $filter)
			->bind('pagination', $pagination)
			->bind('org_tree', $org_tree)
			;
	}
	 
	/*
	15.08.2023
	поиск контроллера по имени
	*/
	public function action_search()
	 {
	 //echo Debug::vars('61', $_POST); exit;
	 $pattern = Arr::get($_POST, 'q', null);
	 
		if ($pattern) {
			$this->session->set('search_device', $pattern);
		} else {
			$pattern = $this->session->get('search_device', '');
		}
		$this->action_index($pattern);
	 }
	
	/*
	15.08.2023
	
	информация об устройстве (контроллере)
	*/
	
	public function action_edit($id_dev=false)
	{
			$id_dev = $this->request->param('id');
			$_SESSION['menu_active']='device';
			//echo Debug::vars('44', $_POST, $_GET, $id_dev);
			if ($id_dev == NULL) $this->redirect('device/find');
			$device_info=Model::Factory('Device')->get_device_info($id_dev);//данные об устройстве
			
			
		$content=View::Factory('device/view', array(
			'device'	=> $device_info,
			//'contact'	=> $device_data,
			//'doors'	=> $device_door,
			//'events'	=> $device_event,
			
			));
			
		$this->template->content = $content;
	}
	
	
	/*
	сохранение информации об устройств
	
	*/
	public function action_save()
	{
		//echo Debug::vars('110', $_POST); exit;
		
		/*
		 "id_dev" => string(3) "551"
    "name" => string(8) "VP4 K3\1"
    "ip" => string(0) ""
    "port" => string(0) ""
    "devtype" => string(1) "1"
    "is_active" => string(1) "1"
    "save_device_data" => string(18) "Сохранить"
		*/
		$id			= Arr::get($_POST, 'id_dev');
		$name		= Arr::get($_POST, 'name','');
		$ip	= Arr::get($_POST, 'ip','');
		$port	= Arr::get($_POST, 'port');
		$devtype	= Arr::get($_POST, 'devtype');
		$is_active	= Arr::get($_POST, 'is_active', 1);
		$id_server	= Arr::get($_POST, 'id_server');
		

		$device = Model::factory('Device');

		if ($id == 0) { // это добавление нового устройства, т.к. $id (она же id_dev) равна 0.
			
			$id = $device->save($id, $name, $ip, $port, $devtype, $is_active, $id_server);
			
			if($inherit == 1) $contact->setInheritAcl($id);
			Session::instance()->set('alert', __('contact.saved'));
		} else {
			$device->update($id, $name, $ip, $port, $devtype, $is_active, $id_server);
			Session::instance()->set('alert', __('contact.updated'));
		}
		//$this->redirect('contacts');
		$this->redirect('devices/edit/' . $id);
	}
	

}
