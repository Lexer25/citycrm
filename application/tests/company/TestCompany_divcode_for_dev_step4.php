<?php
/*
8.01.2024 тестирование методов работы с организациями:
1. добавление дочерних организаций.
2. перемещение организаций
3. удаление организаций.


*/
 
Class TestCompany_step4 extends Unittest_TestCase
{
    
// C:\xampp\htdocs\citycrm\application\tests>c:\xampp\php\phpunit.bat GuestTest.php		

//https://habr.com/ru/articles/56289/

//https://phpunit-documentation-russian.readthedocs.io/ru/latest/organizing-tests.html описание на русском языке

//https://habr.com/ru/companies/vk/articles/549698/#12
/*

 в ходе теста в организацию с указнным divcode добавляю другие организации

*/

 
 public function addNameOrg()
    {
        //добавление организаций.
		// название организации - в какую организацию добавлять - код добавленной организации.
		return [
           ['Отдел 1', 'div_art_res', 'div_art_res_odt1'],
           ['Отдел 2', 'div_art_res', 'div_art_res_odt2'],
           ['Отдел 3', 'div_art_res', 'div_art_res_odt3'],
           ['Отдел 4', 'div_art_res', 'div_art_res_odt4'],
           ['Отдел 5', 'div_art_res', 'div_art_res_odt5'],
         
          
        ];
    }


	
	 /**
     * @dataProvider addNameOrg
     */
	
	public function testAddOrg($nameOrg, $divcode_parent, $divcode)//проверка добавления организаций в родителя 723 (это Артсек)
	{
		//$this->markTestSkipped('must be revisited.');
		
		$company=new Company();
			
					$company->name=$nameOrg;
					$company->divcode=$divcode;
					$_result=$company->addOrgWithDivcodeInParentDivcode($divcode_parent);
					$this->assertEquals(0, $_result);

	
	}

}