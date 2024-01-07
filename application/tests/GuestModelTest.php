<?php


 
Class GuestModelTest extends Unittest_TestCase
{
    
// C:\xampp\htdocs\citycrm\application\tests>c:\xampp\php\phpunit.bat GuestTest.php		

//https://habr.com/ru/articles/56289/

//https://phpunit-documentation-russian.readthedocs.io/ru/latest/organizing-tests.html

//https://habr.com/ru/companies/vk/articles/549698/#12
	
	private $object;
	
 	 public function setUp(){
		
		
		//$this->object=Model::factory('test');
		
	}  
	
	/** @test */
	
	public function testAddGuest()//проверка добавления гостя
	{
		
			//$result=$this->object->getList();
			//$result=Controller::factory('guest')->index();
			$result=Model::factory('test')->getList();
			$this->assertEquals(5, $result);
		
				
	}




	

}