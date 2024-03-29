<?php defined('SYSPATH') or die('No direct script access.');

    class Controller_Login
        extends Controller {
			
	public function before()
	{
			include Kohana::find_file('classes/controller','check_db_connect');
	}
	

        public function action_index() {
			
		
            if (Auth::instance()->logged_in()) {
                $this->redirect('/');
            }
	
            if (Arr::get($_POST, 'hidden') == 'form_sent') {
				
                if (Auth::instance()->login(Arr::get($_POST, 'username'), Arr::get($_POST, 'password'), Arr::get($_POST, 'remember'))
                        //->login(Arr::get($_POST, 'username', 'admin'), Arr::get($_POST, 'password', '333'), Arr::get($_POST, 'remember'))
                        //->force_login(Arr::get($_POST, 'username'), Arr::get($_POST, 'password'), Arr::get($_POST, 'remember'))
                        
                ) {
                    $user = Auth::instance()
                                ->get_user();
                
                   Session::instance()
						//->set('username', iconv('CP1251', 'UTF-8', Arr::get($user, 'SURNAME')))
						->set('language', 'ru-ru')
						->set('listsize', 25)
						->set('org_control', Arr::get($user, 'ID_PEP'));
						;

                    $this->redirect('/');
                }
				//echo Debug::vars('32', Auth::instance()); exit;
            }
			
            //$this->request->response = View::factory('login');
            $this->response->body(View::factory('login'));
           
        }

    }

