<?php
/*
Удаляю все из Артсека


*/
 
Class testClearArtsec extends Unittest_TestCase
{
    

 public function divcodeList()
    {
        return [
           
			 ['Отдел 1', 'div_art_res', 'div_art_res_odt1'],
           ['Отдел 2', 'div_art_res', 'div_art_res_odt2'],
           ['Отдел 3', 'div_art_res', 'div_art_res_odt3'],
           ['Отдел 4', 'div_art_res', 'div_art_res_odt4'],
           ['Отдел 5', 'div_art_res', 'div_art_res_odt5'],
		   ['Бухгалтерия', 723, 'div_art_buh'],
            ['Отдел разработок', 723, 'div_art_res'],
            ['Производство монтажных работ', 723, 'div_art_pmr'],
            ['Маркетинг', 723, 'div_art_mark'],
          
        ];
    }


	
	 /**
     * @dataProvider divcodeList
     */
 

	
	public function testClearOrg($a, $b, $divcode)//проверка добавления организаций в родителя 723 (это Артсек)
	{
		$org=new Company();
		$org->delOrgDivcode($divcode);
	}

}