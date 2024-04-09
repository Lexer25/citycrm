<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Cards extends Controller_Template
{
	public $template = 'template';
	private $listsize;
	private $session;
	private $id_type;
	
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
	
	public function action_rfid()
	{
		$this->session->set('identifier', '1');
		$this->action_index();
		
	}
	
	public function action_grz()
	{
		$this->session->set('identifier', '4');
		$this->action_index();
		
	}
	
	public function action_uhf()
	{
		$this->session->set('identifier', '3');
		$this->action_index();
		
	}
	
	
	
	public function action_search()
	{
		
		//echo Debug::vars('22', $_POST); exit;
		$pattern = trim(Arr::get($_POST, 'q', null));
		$temp=$pattern;
		$post=Validation::factory($_POST);
		//преобразование формата RFID при поиске в формат хранения в базе данных
		/* if($rf=Kohana::$config->load('system')->get('regFormatRfid') == 0){ // если входной формат 0 (HEX)
		$post->rule('q', 'not_empty')
					->rule('q', 'regex', array(':value', '/^[A-F0-9]+$/'));
		} */

		if($rf=Kohana::$config->load('system')->get('regFormatRfid') == 2){ // если входной формат 2 (DEC)
			$post->rule('q', 'not_empty')
					->rule('q', 'digit');
		}

		if($post->check()){

				//5.04.2024 преобразование входного формата номера карты к формату хранения в базе данных
				$temp4=new Keyk();
				
				if($temp4->screenToBase($pattern) == 0) $pattern=$temp4->id_card;
						
				$this->session->set('search_card', $temp4->id_card);
				
		//echo Debug::vars('46', $temp, $pattern, $temp4); exit;
			$this->action_index($pattern);
		} else {
			
			//echo Debug::vars('50', $temp, $pattern); exit;
			$this->action_index();
		}
	}
	
	/**
	$filter - текст, номер карты
	
	*/
	public function action_index($filter = null)
	{
		
		//echo Debug::vars('46', $filter); exit;
		$this->id_type = $this->session->get('identifier', 1);
		
		$cards = Model::factory('Card');
	
		$q = $cards->getCountUser(Arr::get(Auth::instance()->get_user(), 'ID_ORG'), iconv('UTF-8', 'CP1251', $filter), $this->id_type);
		//echo Debug::vars('179',$filter); exit;
		$pagination = new Pagination(array(
			'uri_segment' => 2,
			'total_items' => $q,
			'style' => 'floating',
			'items_per_page' => $this->listsize,
			'auto_hide' => true,
		));
		
		
		$list = $cards->getListUser(Arr::get(Auth::instance()->get_user(), 'ID_ORG'), Arr::get($_GET, 'page', 1), $this->listsize, iconv('UTF-8', 'CP1251', $filter), $this->id_type);
		$catdTypelist = $cards->getcatdTypelist();
		
		
		//echo Debug::vars('55',$isAdmin, $list ); exit;	
		$fl = $this->session->get('alert');
		$this->session->delete('alert');
		
		//для правильного отображения номера RFID в разделе поиска привожу его к формату DEC
		
		$this->template->content = View::factory('cards/list')
			->bind('cards', $list)
			->bind('catdTypelist', $catdTypelist)
			->bind('alert', $fl)
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
		
		$this->template->content = View::factory('cards/card')
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
			;
		if($validation->check()){
			//обновление данных карты
			$key=new Keyk(Arr::get($validation, 'idcard'));
			$key->timestart=Arr::get($validation, 'carddatestart');
			$key->timeend=Arr::get($validation, 'carddateend');
			$key->is_active=0;
			if(Arr::get($validation, 'cardisactive') == 'on') $key->is_active=1;
			
			if($key->update()==0){
				
				$alert=__('card.updateOk', array(':idcard'=>Arr::get($validation, 'idcard')));
			} else {
				$alert=__('card.updateErr', array(':idcard'=>Arr::get($validation, 'idcard')));
			}
			
		} else {
			
			//отказ в обновлении карты
			$alert=__('card.errDataForUpdate', array(':mess'=>$validation->errors('upload')));
		}
		$fl = $this->session->set('alert', $alert);
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
