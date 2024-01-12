<?php
/*
8.01.2024 тестирование методов работы с контактами:
1. добавление дочерних организаций.
2. перемещение организаций
3. удаление организаций.


*/
 
Class TestCompany_step2 extends Unittest_TestCase
{
    
// C:\xampp\htdocs\citycrm\application\tests>c:\xampp\php\phpunit.bat GuestTest.php		

//https://habr.com/ru/articles/56289/

//https://phpunit-documentation-russian.readthedocs.io/ru/latest/organizing-tests.html описание на русском языке

//https://habr.com/ru/companies/vk/articles/549698/#12
/*

 в ходе теста в организацию с указанным divcode будут добавлены сотрудники

*/

 
 public function addNameList()
    {
       //добавление ФИО в оргназацию с указанным divcode
	   // Ф - И - О - divcode организации.
	   
	   return [
            ['Чехов','Виктор','Владимирович', 'div_art_res'],
       
         
        // ответ - автоматически сформированный табельный номер.
		// при добавлении пипла можно указывать и его табельный номер из головной системы.
          
        ];
    }


	
	 /**
     * @dataProvider addNameList
     */
	
	public function testAddContact($surname, $name, $patronymic, $divcode)//проверка добавления организаций в родителя 723 (это Артсек)
	{
		//$this->markTestSkipped('must be revisited.');
		//Log::instance()->add(Log::DEBUG, Debug::vars('49', $surname, $name, $patronymic ));
		//$divcode='div_art_res_odt3';
		$contact=new Contact();
		$org=new Company();
		$contact->surname=$surname;
		$contact->name=$name;
		$contact->patronymic=$patronymic;
		$contact->id_org=$org->getIdOnDivCode($divcode);
		$this->assertEquals(0, $contact->addContact());
		$contact->setAclDefault();

	
	}

}