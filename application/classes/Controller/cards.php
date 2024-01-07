<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Cards extends Controller_Template
{
	public $template = 'template';
	private $listsize;
	private $session;
	
	public function before()
	{
		parent::before();
		if (!Auth::instance()->logged_in()) $this->redirect('/');

		$this->session = Session::instance();
		I18n::$lang = $this->session->get('language', 'en-us');
		$this->listsize = $this->session->get('listsize', 10);
	}
	
	public function action_search()
	{
		
		$pattern = trim(Arr::get($_POST, 'q', null));
		if ($pattern) {
			$this->session->set('search_card', $pattern);
		} else {
			$pattern = $this->session->get('search_card', '');//параметры выборки берутся ИЛИ из POST, или из сессии.
		}
		$this->action_index($pattern);
	}
	
	/**
	$filter - текст, номер карты
	
	*/
	public function action_index($filter = null)
	{
		
		$isAdmin = Auth::instance()->logged_in('admin');
		
		$cards = Model::factory('Card');
		
		
			$q = $cards->getCountUser(Arr::get(Auth::instance()->get_user(), 'ID_ORG'), iconv('UTF-8', 'CP1251', $filter));
		
		$pagination = new Pagination(array(
			'uri_segment' => 2,
			'total_items' => $q,
			'style' => 'floating',
			'items_per_page' => $this->listsize,
			'auto_hide' => true,
		));
		
		
			$list = $cards->getListUser(Arr::get(Auth::instance()->get_user(), 'ID_ORG'), Arr::get($_GET, 'page', 1), $this->listsize, iconv('UTF-8', 'CP1251', $filter));
		$catdTypelist = $cards->getcatdTypelist();
		
		
		//echo Debug::vars('55',$isAdmin, $list ); exit;	
		$fl = $this->session->get('alert');
		$this->session->delete('alert');
		
		$this->template->content = View::factory('cards/list')
			->bind('cards', $list)
			->bind('catdTypelist', $catdTypelist)
			->bind('alert', $fl)
			->bind('filter', $filter)
			->bind('pagination', $pagination);
	}

	public function action_delete($id)
	{
		Model::factory('Card')->delete($id);
		$this->redirect('cards');
	}
	
}
