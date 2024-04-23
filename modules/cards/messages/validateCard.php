<?php defined('SYSPATH') or die('No direct script access.'); 
$messages = array(
   'name'   =>
      array(
         'not_empty'       => ':field Не может быть пустым',
      ),

	'getCardInfo'   =>
      array(
         'not_empty'=> 'Номер карты не может быть пустым',
         'regex'=> 'Недопустимый набор данных. Номер должен состоянить из цифр 0-1, запятой, букв ABCDEF!',
      ),

	'card'   =>
      array(
         't555'=> ':value Карта уже зарегистрирована.',
       ),
	  
	'id_pep'   =>  array(
         'not_empty'=> 'Пользователь не найден.',
      ),
	  
	 'idPepInfo'   =>  array(
         'Model_People::unique_username'=> 'Пользователь не найден.',
		 'not_empty'=> 'Номер пользователья не может быть пустым',
		 'digit'=> 'Номер пользователья должен быть числом',
      ),
	  
	  
	 'q'   =>  array(        
		 'digit'=> 'Строка поиска ":value" должна состоять из цифр',
      ),
	  
	  
	  
);
 
return $messages;