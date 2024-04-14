<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Cards extends Controller_Template
{
	public $template = 'template';
	private $listsize;
	private $session;
	private $id_type;
	public $arrAlert;
	
	public function before()
	{
		parent::before();
		if (!Auth::instance()->logged_in()) $this->redirect('/');

		$this->session = Session::instance();
		I18n::$lang = $this->session->get('language', 'en-us');
		$this->listsize = $this->session->get('listsize', 10);
		$this->id_type = $this->session->get('identifier', 1);
		
	}
	
	/*
	9.04.2024 диспетчер режима отображения данных.
	Тип отображаемых данных хранится в параметрах сессии под именем identifier
	*/
	
	public function action_select()
	{
		//echo Debug::vars('29', $this->request->param('id')); exit;
		 $post=Validation::factory(array('key'=>trim($this->request->param('id'))));
		 $post->rule('key', 'not_empty')
				->rule('key', 'alpha_numeric');
		if($post->check())
		{
			switch(Arr::get($post, 'key')){
				
				case 'rfid':
					$this->session->set('identifier', '1');
				break;
				
				case 'grz':
					$this->session->set('identifier', '4');
				break;
				case 'uhf':
					$this->session->set('identifier', '5');
				break;
				default:
					
				break;
				
				
			}
		
		//$this->session->set('identifier', '1');
		
		
		}
	$this->action_index();
	}

	
	
	
	public function action_search()
	{
		/*
		порядок проверки входящих значений определяется типов идентификатора
		тип идентификатора указан в сессии $this->session->get('identifier')
		*/
		//echo Debug::vars('22', $_POST, $this->session->get('identifier')); exit;
		$pattern = trim(Arr::get($_POST, 'q', null));// убрал лишние знаки вокруг строки поиска
		$this->session->set('search_card', $pattern);//параметры поиска мы сохраняем, чтобы повторно вывести в строке поиска.
		$temp=$pattern;
		$post=Validation::factory($_POST);
		
		switch($this->session->get('identifier')){ //определяю тип идентификатора для поиска.
			case 1:// RFID
				if($rf=Kohana::$config->load('system')->get('regFormatRfid') == 2){ // если входной формат 2 (DEC), то проверяю что это число
					$post->rule('q', 'not_empty')
						->rule('q', 'digit')
						->rule('q', 'range', array(':value', 100, pow(2,32)))
						;
				}
				if($post->check()){

				//5.04.2024 преобразование входного формата номера карты к формату хранения в базе данных
					$temp=new Keyk();
					if($temp->screenToBase($pattern) == 0){ // если номер карты преобразован успешно, то продолжаю поиск
							$pattern=$temp->id_card;
							$temp->id_card=$pattern;
							$temp->id_cardtype=1;
							$var2=$temp->search();
					}
					//echo Debug::vars('91', $_POST, $var2); exit;
					//$this->session->set('search_card', $temp->id_card); //в поисковой строке будет этот формат
					//echo Debug::vars('46', $temp, $pattern, $temp4); exit;
					$this->action_index($var2);
				} else {
				echo Debug::vars('100', $_POST, $var2); exit;
					//$alert=__('card.errDataForSearchRFID', array(':mess'=>$post->errors('upload')));
					$this->action_index();	
				}
			
			break;
			case 4://поиск ГРЗ	
				$post->rule('q', 'not_empty')
					->rule('q', 'regex', array(':value', '/^[A-F0-9]+$/'));
					if($post->check()){
						$temp=new Keyk();
						$temp->id_card=Arr::get($post, 'q');
						$temp->id_cardtype=4;
						$var2=$temp->search();
						echo Debug::vars('99', $_POST, $this->session->get('identifier'), $pattern, $var2); exit;
					//$this->session->set('search_card', $pattern);	//в поисковой строке будет ГРЗ без изменения
					$this->action_index($var2);	
						
				} else {
					//$alert=__('card.errDataForSearchGrz', array(':mess'=>$post->errors('upload')));
					$this->action_index();	
				}
			
			
			break;
			
			default:
				$this->action_index();	
			break;
			
		} 
	
		
	}
	
	/**
	$filter - массив с номерами идентификаторов, которые надо вывести на экран
	
	*/
	public function action_index($filter = null)
	{
		
		//echo Debug::vars('46', $filter); exit;
		$this->id_type = $this->session->get('identifier', 1);
		
		
		
		$cards = Model::factory('Card');
		if(is_null($filter)){// если списка нет, то выбираю все, что разрешено авторизованному пользователю
		//$q = $cards->getCountUser(Arr::get(Auth::instance()->get_user(), 'ID_ORG'), iconv('UTF-8', 'CP1251', $filter), $this->id_type);
		$q = $cards->getCountUser(Arr::get(Auth::instance()->get_user(), 'ID_ORG'), iconv('UTF-8', 'CP1251', $filter), $this->id_type);
		//echo Debug::vars('179',$filter); exit;
		
		$list = $cards->getListUser(Arr::get(Auth::instance()->get_user(), 'ID_ORG'), Arr::get($_GET, 'page', 1), $this->listsize, iconv('UTF-8', 'CP1251', $filter), $this->id_type);
		
		
		} else {
			$q=count($filter);
			$list=$filter;
		}
		//echo Debug::vars('154', $list); exit;
		$pagination = new Pagination(array(
			'uri_segment' => 2,
			'total_items' => $q,
			'style' => 'floating',
			'items_per_page' => $this->listsize,
			'auto_hide' => true,
		));
		

		$catdTypelist = $cards->getcatdTypelist();
		
		
		//echo Debug::vars('55',$isAdmin, $list ); exit;	
		$fl = $this->session->get('alert');
		$this->session->delete('alert');
		
		$arrAlert = $this->session->get('arrAlert');
		$this->session->delete('arrAlert');
		
		$filter=$this->session->get('search_card');
		//для правильного отображения номера RFID в разделе поиска привожу его к формату DEC
		
				
		$this->template->content = View::factory('cards/list')
			->bind('cards', $list)
			->bind('catdTypelist', $catdTypelist)
			->bind('alert', $fl)
			->bind('arrAlert', $arrAlert)
			->bind('filter', $filter)
			->bind('pagination', $pagination);
			//echo View::factory('profiler/stats');
	}

	public function action_delete()
	{
		//echo Debug::vars('72', $this->request->param('id')); exit;
		$key=new Keyk($this->request->param('id'));
		if($key->delCard()){
				$alert=__('cards.deletedOk', array(':id_card'=>$this->request->param('id')));
		} else {
			$alert=__('cards.deletedErr', array(':id_card'=>$this->request->param('id')));
		}
		$this->redirect('cards');
	}
	
	
	/*
	11.02.2024 
	Просмотр свойств идентификатора
	*/
	public function action_edit()
	{
		$id=$this->request->param('id');
		
		$mode='edit';
		$key=new Keyk($id);
		
		$loads = Model::factory('Card')->getLoads($id);
		
		$fl = $this->session->get('alert');
		$this->session->delete('alert');
		
		$arrAlert = $this->session->get('arrAlert');
		$this->session->delete('arrAlert');
		
		$this->template->content = View::factory('cards/card')
			->bind('key', $key)
			->bind('catdTypelist', $catdTypelist)
			->bind('alert', $fl)
			->bind('arrAlert', $arrAlert)
			->bind('mode', $mode)
			->bind('filter', $filter)
			->bind('loads', $loads)//данные о заргузке карты в контроллеры
			->bind('pagination', $pagination);
	}
	
	
	/*
	11.02.2024 
	Просмотр загрузки идентификатора в контроллеры
	*/
	public function action_load()
	{
		$id=$this->request->param('id');
		
		$mode='edit';
		$key=new Keyk($id);
		
		$loads = Model::factory('Card')->getLoads($id);
		
		$fl = $this->session->get('alert');
		$this->session->delete('alert');
		
		$this->template->content = View::factory('cards/load')
			->bind('key', $key)
			->bind('catdTypelist', $catdTypelist)
			->bind('alert', $fl)
			->bind('mode', $mode)
			->bind('filter', $filter)
			->bind('loads', $loads)//данные о заргузке карты в контроллеры
			->bind('pagination', $pagination);
	}
	
	
/*
	11.02.2024 
		Просмотр истории идентификатора
	*/
	public function action_history()
	{
		$id=$this->request->param('id');
		
		$mode='edit';
		$key=new Keyk($id);
		
		$loads = Model::factory('Card')->getLoads($id);
		
		$fl = $this->session->get('alert');
		$this->session->delete('alert');
		
		$this->template->content = View::factory('cards/history')
			->bind('key', $key)
			->bind('catdTypelist', $catdTypelist)
			->bind('alert', $fl)
			->bind('mode', $mode)
			->bind('filter', $filter)
			->bind('loads', $loads)//данные о заргузке карты в контроллеры
			->bind('pagination', $pagination);
	}
	
	
	
	
	
	/*
	11.02.2024 
	Просмотр свойств идентификатора
	*/
	public function action_savecard()
	{
		//echo Debug::vars('106', $_POST); exit;
		$validation=Validation::factory($_POST);
		
		$validation->rule('idcard','not_empty') 
					->rule('carddatestart','not_empty')
					->rule('carddateend','not_empty')
					//->rule('cardisactive','not_empty')
					//->rule('id_cardtype','not_empty')
					->rule('id','not_empty')
					->rule('note','max_length', array(':value', 50))
			;
		if($validation->check()){
			//обновление данных карты
			$key=new Keyk(Arr::get($validation, 'idcard'));
			$key->timestart=Arr::get($validation, 'carddatestart');
			$key->timeend=Arr::get($validation, 'carddateend');
			$key->note=Arr::get($validation, 'note');
			$key->is_active=0;
			if(Arr::get($validation, 'cardisactive') == 'on') $key->is_active=1;
			
			if($key->update()==0){
				
				$alert=__('card.updateOk', array(':idcard'=>Arr::get($validation, 'idcard')));
				$arrAlert[]=array('actionResult'=>2, 'actionDesc'=>$alert);
			} else {
				$alert=__('card.updateErr', array(':idcard'=>Arr::get($validation, 'idcard')));
				$arrAlert[]=array('actionResult'=>2, 'actionDesc'=>$alert);
			}
			
		} else {
			
			//отказ в обновлении карты
			$alert=__('card.errDataForUpdate', array(':mess'=>implode(",", $validation->errors('upload'))));
			$arrAlert[]=array('actionResult'=>2, 'actionDesc'=>$alert);
			//echo Debug::vars('316 vakid err', $arrAlert); exit;
		}
		Session::instance()->set('arrAlert',$arrAlert);	
		$this->redirect('cards/edit/'.Arr::get($validation, 'idcard'));
	}
	
	
	/*
	11.01.2024
	Повторная загрузку карты контроллеры
	*/
	public function action_reload()
	{
		$id=$this->request->param('id');
		$card = Model::factory('Card')->getCard($id);
		
		Model::factory('Card')->reload($id);
		
		Session::instance()->set('alert', __('card.reloadOk', array(':id_card'=>$id)));
		$this->redirect('cards/load/'.$id);
	}
	
}
