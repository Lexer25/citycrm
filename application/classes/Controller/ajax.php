<?php defined('SYSPATH') or die('No direct script access.');
  
class Controller_Ajax extends Controller {
  
	public function action_togglecard($id)
	{
		$card = Model::factory('Card')->getCard($id);
		$oldv = $card['ACTIVE'];
		$newv = $oldv == '1' ? '0' : '1';
		Model::factory('Card')->toggleCard($id, $newv);
		
		$sel = $newv;
		$this->request->headers ['content-type'] = 'application/json';
		$this->request->response = $sel; //json_encode($sel);
		echo $this->request->response;
	}
}