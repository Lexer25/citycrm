<?php defined('SYSPATH') or die('No direct script access.');
class Controller_Doors extends Controller_Template { 

	public function before()
	{
			
			parent::before();
			$session = Session::instance();
			//echo Debug::vars('9controller', $_POST, $_GET);
			
	}
	
	
	public function action_index()
	{
		$_SESSION['menu_active']='door';
		$doorList=Model::factory('Door')->getDoorList();
		//echo Debug::vars('18',$doorList);exit;
		$content = View::factory('door/list', array(
			'doors'=>$doorList,
		
		));
        $this->template->content = $content;
	}
	 
	 
	public function action_export()
	{
	//echo Debug::vars('447', $_POST, Session::instance(), Arr::get($this->session->get('auth_user_crm', ''), 'ID_PEP'), Arr::get($this->session->get('auth_user_crm', ''), 'NAME')); exit;
	//echo Debug::vars('447', $_POST, Session::instance()); exit;
			//$dataReportTitle=
			
			
			$post=Validation::factory($_POST);
			$post->rule('id_door', 'not_empty')
					->rule('id_door', 'digit')
					;
				$reporttype='';
			if($post->check()){
				
				$huser=Arr::get(Session::instance()->get('auth_user_crm'), 'ID_PEP');
				
				
				
				if(Arr::get($post, 'savecvs')) include Kohana::find_file('classes\Controller\report','reportDoorListCVS') ;
				if(Arr::get($post, 'savexls')) include Kohana::find_file('classes\Controller\report','reportDoorListXLSX') ;
				
				if(Arr::get($post, 'savepdf')){
					//$forsave=unserialize(iconv('UTF-8', 'CP1251', Arr::get($post, 'forsave')));
					$forsave=unserialize( Arr::get($post, 'forsave'));
					//$forsave=array();
					
					$id_door=Arr::get($post, 'id_door');
					
					 $content=View::Factory('\report\contactListForDoor\contactListForDoor')
						->bind('dataForSave', $forsave)
						->bind('id_admin', $huser)
						->bind('id_door', $id_door)
						; 
					//$content=View::Factory('\report\doorlist\west_garden');	
					//if(false){ // переключатель: true - делать экспорт в pdf, false - выводит отчет на экран браузера
					if(true){ // переключатель: true - делать экспорт в pdf, false - выводит отчет на экран браузера
					
						//require_once APPPATH . 'vendor/dompdf/src/Autoloader.php';
						require_once APPPATH . 'vendor/dompdf/autoload.inc.php';
						//require_once APPPATH . 'vendor/dompdf/src/Dompdf.php';
						Dompdf\Autoloader::register();
				
				/* $dompdf = new Dompdf\src\Dompdf();
				$dompdf->set_option('isRemoteEnabled', TRUE);
				$dompdf->setPaper('A4', 'portrait');
				$dompdf->loadHtml($html, 'UTF-8');
				$dompdf->render(); */
									
						$dompdf = new Dompdf\Dompdf();
						$dompdf->setPaper("A4");				
						$dompdf->loadHtml($content, 'UTF-8');
						$dompdf->render();
						
				
			$color = array(0, 0, 0);
			$font = null;
			$size = 8;
	$text = "Стр. {PAGE_NUM} из {PAGE_COUNT}";

	$canvas = $dompdf->getCanvas();
	$pageWidth = $canvas->get_width();
	$pageHeight = $canvas->get_height();
	$width=10;


			$canvas = $dompdf->get_canvas();
			$canvas->page_text($pageWidth/2, $pageHeight - 40, $text, $font, $size, $color);


	$dompdf->stream();
						

		
						} else {

					$this->template->content = $content;
					
					
					}
				}		
					
					
					
					return;
					
			
					
			}else{
				$message=implode(",", $post->errors('reportValidation'));
				echo Debug::vars('127 Validate Err',  $message); exit;
				$this->redirect('errorpage?err=' . urlencode($message));
				
			}			
			
	}
	 
	 
	 public function action_find()
	 {
	 
	 $search=Arr::get($_GET, 'doorInfo');
	 $_SESSION['doorEventsTimeFrom']=Arr::get($_GET, 'timeFrom');
		$_SESSION['doorEventsTimeTo']=Arr::get($_GET, 'timeTo');
	 $result=Model::Factory('Door')->findIdDoor($search);
		 if(count($result)>0)
		 {
			//$this->redirect('door/doorInfo/'.$result);
			$content=View::Factory('door/select', array(
			'list' => $result,
			
			));
		 $this->template->content = $content;
		 
		 } else {
		 $content=View::Factory('door/search');
		 $this->template->content = $content;
		 }
	 }
	
	
	
	public function action_doorInfo ($id_door=false)
	{
			$id_door = $this->request->param('id');
			$_SESSION['menu_active']='door';
			if ($id_door == NULL) $this->redirect('door/find');
			$door_data=Model::Factory('Door')->getDoor($id_door);//информация о точке прохода
			$door_load_order=Model::Factory('Door')->getDoorLoadorder($id_door);//Список пользователей для загрузки в контроллер
			$door_delete_order=Model::Factory('Door') -> getDoorDeleteOrder($id_door);//Список пользователей для удаления из контроллера
			$door_events=Model::Factory('Event')->event_door($id_door);//информация о событиях точки прохода
			$key_for_door=Model::Factory('Door') -> getKeysForDoor($id_door);//карты для точки прохода, ФИО, сроки действия
			$card_type=Model::Factory('Door')->getCardType();// получить список типов карт
			$enable_card_type=Model::Factory('Door')->getEnableCardType(Arr::get($door_data, 'ID_DEVTYPE'));// получить список обслуживаемых типов карт
		
			$topbuttonbar=View::factory('door/topbuttonbar', array(
			'id_door'=> $id_door,
			'_is_active'=> 'view',
			))
		;
		
		
		$content=View::Factory('door/view', array(
			'door'	=> $door_data,
			'id_door'	=> $id_door,
			'topbuttonbar'	=> $topbuttonbar,
			'people_add'	=> $door_load_order,
			'people_del'	=> $door_delete_order,
			'events'	=> $door_events,
			'keys'=>$key_for_door,
			'card_type'=>$card_type,
			'enable_card_type'=>$enable_card_type,
			));
			
		$this->template->content = $content;
	}
	
	/**
	*список сотрудников, имеющих право ходить в точку прохода
	*/
	public function action_doorcontactlist ()
	{
			$id_door = $this->request->param('id');
			if ($id_door == NULL) $this->redirect('door/find');
			$door_data=Model::Factory('Door')->getDoor($id_door);//информация о точке прохода
			//$door_load_order=Model::Factory('Door')->getDoorLoadorder($id_door);//Список пользователей для загрузки в контроллер
			//$door_delete_order=Model::Factory('Door') -> getDoorDeleteOrder($id_door);//Список пользователей для удаления из контроллера
			//$door_events=Model::Factory('Event')->event_door($id_door);//информация о событиях точки прохода
			$key_for_door=Model::Factory('Door') -> getKeysForDoor($id_door);//карты для точки прохода, ФИО, сроки действия
			//$card_type=Model::Factory('Door')->getCardType();// получить список типов карт
			//$enable_card_type=Model::Factory('Door')->getEnableCardType(Arr::get($door_data, 'ID_DEVTYPE'));// получить список обслуживаемых типов карт
			$enable_card_list=Model::Factory('Door')->getkeyListForDoor($id_door);// получить список обслуживаемых типов карт
		
			$topbuttonbar=View::factory('door/topbuttonbar', array(
			'id_door'=> $id_door,
			'_is_active'=> 'doorcontactlist',
			))
		;
		
		//echo Debug::vars('109', $id_door, count($enable_card_list), $enable_card_list);exit;
		$content=View::Factory('door/doorcontactlist', array(
			'door'	=> $door_data,
			'id_door'	=> $id_door,
			'topbuttonbar'	=> $topbuttonbar,
		//	'people_add'	=> $door_load_order,
		//	'people_del'	=> $door_delete_order,
		//	'events'	=> $door_events,
			'keys'=>$key_for_door,
		//	'card_type'=>$card_type,
			'enable_card_list'=>$enable_card_list,
			));
			
		$this->template->content = $content;
		//echo View::factory('profiler/stats');
		
	}
	
	

}

