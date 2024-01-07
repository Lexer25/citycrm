<?php
 
Class GuestTest extends Unittest_TestCase
{
    
// C:\xampp\htdocs\citycrm\application\tests>c:\xampp\php\phpunit.bat GuestTest.php		

//https://habr.com/ru/articles/56289/

//https://phpunit-documentation-russian.readthedocs.io/ru/latest/organizing-tests.html

//https://habr.com/ru/companies/vk/articles/549698/#12
	
	
	public $_id_pep;
	
	public $_tabnum;


	public function __construct()
	{
      $this->_tabnum = 'tabnum_'.time();
    }
			
	
	
	
	
	public function testAddGuest()//проверка добавления гостя
	{
		//$this->_tabnum='tabnum_'.time();
		
		$guest=new Guest();
			
					$guest->name='nameTest'.time();
					$guest->patronymic='patronymic'.time();
					$guest->surname='surname'.time();
					$guest->numdoc=time();
					$guest->datedoc=date('d.m.Y', time());
					$guest->note='notetest'.time();
					$guest->org=2;
					$guest->addGuest();//добавляю ФИО и заметки
					$this->_id_pep=$guest->id_pep;//запомнил id_pep в локальную переменную
					//Log::instance()->add(Log::DEBUG, Debug::vars($this->_tabnum, $guest->id_pep, $guest->tabnum, $guest->actionResult));
					$this->assertEquals(0, $guest->actionResult);
					
					
					$guest->tabnum=$this->_tabnum;
					$guest->setTabNum();//табельный номер
				
		
				
	}



	 
	public function testCheckOnTabNum1()//проверка наличия пипла по табельному номеру. Указанный табельный номер должен быть
	{
		$guest=new Guest();
		$guest->tabnum=$this->_tabnum;
		$guest->checkOnTabNum();
		$this->assertEquals(0, $guest->actionResult);
	}



	public function testDelOnTabNum()//удаление пипла по табельному номеру
	{
		$tabnum='testTabNum33333333';
		$guest=new Guest();
		$guest->tabnum=$tabnum;
		//$guest->delOnTabNum();
		$this->assertEquals(0, $guest->actionResult);
	}



	
	public function testCheckOnTabNum2()//проверка наличия пипла по табельному номеру. указанного табельного номеры быть не должно
	{
		$tabnum='testTabNum33333333';
		$guest=new Guest();
		$guest->tabnum=$tabnum;
		$guest->checkOnTabNum();
		$this->assertEquals(1, $guest->actionResult);
	}

// проверка работы с _id_pep
		public function testAddGuest2()//проверка добавления гостя
	{
			$guest=new Guest();
			
					$guest->name='nameTest'.time();
					$guest->patronymic='patronymic'.time();
					$guest->surname='surname'.time();
					$guest->numdoc=time();
					$guest->datedoc=date('d.m.Y', time());
					$guest->note='notetest'.time();
					$guest->org=2;
					$guest->addGuest();//добавляю ФИО и заметки
					$this->_id_pep=$guest->id_pep;//запомнил id_pep в локальную переменную тестового класса
					//Log::instance()->add(Log::DEBUG, Debug::vars('90', $guest->id_pep, $guest->tabnum, $guest->actionResult));// табельный номер должен быть выдан СКУДом.
					$this->assertEquals(0, $guest->actionResult);
					
					return $result['id_pep']= $guest->id_pep;
	}


 /**
 * @depends testAddGuest2
 */ 
	
	public function testCheckOnIdPep2($ttt)//проверка наличия пипла по id_pep
	{
		$guest=new Guest();
		$guest->id_pep=$ttt;
		Log::instance()->add(Log::DEBUG, Debug::vars('117', $ttt, $this->_id_pep, $guest->id_pep));
		$guest->checkOnIdPep();
		$this->assertEquals(0, $guest->actionResult);
	}
	
	
	 /**
 * @depends testAddGuest2
 */ 
	
	public function testdelOnIdPep($ttt)//удаление пипла по id_pep
	{
		// $this->markTestSkipped();
		$guest=new Guest();
		$guest->id_pep=$ttt;
		$guest->delOnIdPep();
		$this->assertEquals(0, $guest->actionResult);
	}
	
	

	

}