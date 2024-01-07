<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Queue extends Controller_Template
{
	public $template = 'template';
	private $listsize;
	private $session;
	
	public function before()
	{
		parent::before();
		//if (!Auth::instance()->logged_in()) $this->request->redirect('/');

		$this->session = Session::instance();
		I18n::$lang = $this->session->get('language', 'en-us');
		$this->listsize = $this->session->get('listsize', 10);
	}
	
	public function action_search() //search for q.
	{
	
		$pattern = Arr::get($_POST, 'q', null);
		if ($pattern) {
			$this->session->set('search_queue', $pattern);
		} else {
			$pattern = $this->session->get('search_queue', '');//
		}
		
		$this->action_index($pattern);
	}
	
	public function action_search_queue() //
	{
	
		$pattern = Arr::get($_POST, 'q', null);
		if ($pattern) {
			$this->session->set('search_queue', $pattern);
		} else {
			$pattern = $this->session->get('search_queue', '');//
		}
		
		$this->action_ListQueue($pattern);
	}
	
	public function action_search_id_dev($id_dev)//seach for select device
	{
	$queue=Model::factory('Queue');
	$q=$queue->getCountIddev($id_dev);//count records for select id
	$pagination = new Pagination(array(
			'uri_segment' => 2,
			'total_items' => $q,
			'style' => 'floating',
			'items_per_page' => $this->listsize,
			'auto_hide' => true,
		));
	
	$list = $queue->getQueueIddev(Arr::get($_GET, 'page', 1), $this->listsize, $id_dev);
		$fl = $this->session->get('alert').__('queue.count_filter').$q;
		$this->session->delete('alert');
		$this->template->content = View::factory('queue/queue')
			->bind('queue', $list)
			->bind('alert', $fl)
			->bind('filter', $filter)
			->bind('pagination', $pagination);
	
	}
	
	
	
	public function action_index($filter = null) // start page
	{
		
		$queue = Model::factory('Queue');
		
		$q = $queue->getCountQueue($filter=Null);// 
		
		$pagination = new Pagination(array(
			'uri_segment' => 2,
			'total_items' => $q,
			'style' => 'floating',
			'items_per_page' => $this->listsize,
			'auto_hide' => true,
		));
		
		$list = $queue->getQueue(Arr::get($_GET, 'page', 1), $this->listsize, $filter=NULL);
		
		$fl = $this->session->get('alert').__('queue.form.header1').$q;
		$this->session->delete('alert');
		
		$this->template->content = View::factory('queue/list_queue')
			->bind('queue', $list)
			->bind('alert', $fl)
			->bind('filter', $filter)
			->bind('pagination', $pagination);
		
		//$this->template->content = View::factory('test');
				
	}
	
	public function action_ListQueue($filter = null)// 
	{
		$isAdmin = Auth::instance()->logged_in('admin');
		$queue = Model::factory('Queue');
		$q = $queue->getCountList($filter);// 
		
		$pagination = new Pagination(array(
			'uri_segment' => 2,
			'total_items' => $q,
			'style' => 'floating',
			'items_per_page' => $this->listsize,
			'auto_hide' => true,
		));
		
		if ($isAdmin)
			$list = $queue->getList(Arr::get($_GET, 'page', 1), $this->listsize, iconv('UTF-8', 'CP1251', $filter));
		else 
			$list = $queue->getList(Arr::get($_GET, 'page', 1), $this->listsize, iconv('UTF-8', 'CP1251', $filter));
		$fl = __('queue.count_filter').$q;
		$this->session->delete('alert');
		$this->template->content = View::factory('queue/queue')
			->bind('queue', $list)
			->bind('alert', $fl)
			->bind('filter', $filter)
			->bind('pagination', $pagination);
	}

	public function action_delete($id)
	{
		Model::factory('Card')->delete($id);
		$this->request->redirect('cards');
	}
	
	public function action_load_device()
	{
		
		$pattern_start_load = Arr::get($_POST, 'start_load', null);
		$pattern_stop_load = Arr::get($_POST, 'stop_load', null);
		
		//Kohana::$log->add(Kohana::ERROR, 'aaa'.$pattern_start_load);
		if(isset($pattern_start_load)){
			$list_id=Arr::get($_POST, 'select_id_device', null);
			if(isset($list_id))
			{
				Model::factory('Queue')->start_load_controller($list_id);
			}
		}
		if(isset($pattern_stop_load)){
			$list_id=Arr::get($_POST, 'select_id_device', null);
			if(isset($list_id))
			{	
			Model::factory('Queue')->stop_load_controller($list_id);
			
			}
		}
			$this->request->redirect('queue');
	}
	
	public function action_load_cards()
	{
		$pattern_start_load_cards = Arr::get($_POST, 'start_load_cards', null);
		$pattern_stop_load_cards = Arr::get($_POST, 'stop_load_cards', null);
		
		if(isset($pattern_start_load_cards)){
			$list_id=Arr::get($_POST, 'select_cards', null);
			if(isset($list_id))
			{
				Model::factory('Queue')->start_load_cards($list_id);
			}
		}
		if(isset($pattern_stop_load_cards)){
			$list_id=Arr::get($_POST, 'select_cards', null);
			if(isset($list_id))
			{
				Model::factory('Queue')->stop_load_cards($list_id);
			}
		}
		$this->request->redirect('queue/ListQueue');
	}
	
	
	
}
