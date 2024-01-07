<?php defined('SYSPATH') OR die('No direct script access.'); ?>

2024-01-02 00:11:33 --- CRITICAL: ErrorException [ 4 ]: syntax error, unexpected '}' ~ APPPATH\views\guests\edit.php [ 359 ] in file:line
2024-01-02 00:11:33 --- DEBUG: #0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main} in file:line
2024-01-02 00:12:26 --- CRITICAL: ErrorException [ 4 ]: syntax error, unexpected '}' ~ APPPATH\views\guests\edit.php [ 365 ] in file:line
2024-01-02 00:12:26 --- DEBUG: #0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main} in file:line
2024-01-02 00:13:16 --- CRITICAL: ErrorException [ 4 ]: syntax error, unexpected '}' ~ APPPATH\views\guests\edit.php [ 363 ] in file:line
2024-01-02 00:13:16 --- DEBUG: #0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main} in file:line
2024-01-02 00:13:57 --- CRITICAL: ErrorException [ 4 ]: syntax error, unexpected '}' ~ APPPATH\views\guests\edit.php [ 363 ] in file:line
2024-01-02 00:13:57 --- DEBUG: #0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main} in file:line
2024-01-02 00:14:45 --- CRITICAL: ErrorException [ 8 ]: Undefined variable: cardList ~ APPPATH\views\guests\edit.php [ 329 ] in C:\xampp\htdocs\citycrm\application\views\guests\edit.php:329
2024-01-02 00:14:45 --- DEBUG: #0 C:\xampp\htdocs\citycrm\application\views\guests\edit.php(329): Kohana_Core::error_handler(8, 'Undefined varia...', 'C:\\xampp\\htdocs...', 329, Array)
#1 C:\xampp\htdocs\citycrm\system\classes\Kohana\View.php(62): include('C:\\xampp\\htdocs...')
#2 C:\xampp\htdocs\citycrm\system\classes\Kohana\View.php(359): Kohana_View::capture('C:\\xampp\\htdocs...', Array)
#3 C:\xampp\htdocs\citycrm\system\classes\Kohana\View.php(236): Kohana_View->render()
#4 C:\xampp\htdocs\citycrm\application\views\template.php(46): Kohana_View->__toString()
#5 C:\xampp\htdocs\citycrm\system\classes\Kohana\View.php(62): include('C:\\xampp\\htdocs...')
#6 C:\xampp\htdocs\citycrm\system\classes\Kohana\View.php(359): Kohana_View::capture('C:\\xampp\\htdocs...', Array)
#7 C:\xampp\htdocs\citycrm\system\classes\Kohana\Controller\Template.php(44): Kohana_View->render()
#8 C:\xampp\htdocs\citycrm\system\classes\Kohana\Controller.php(87): Kohana_Controller_Template->after()
#9 [internal function]: Kohana_Controller->execute()
#10 C:\xampp\htdocs\citycrm\system\classes\Kohana\Request\Client\Internal.php(97): ReflectionMethod->invoke(Object(Controller_Guests))
#11 C:\xampp\htdocs\citycrm\system\classes\Kohana\Request\Client.php(114): Kohana_Request_Client_Internal->execute_request(Object(Request), Object(Response))
#12 C:\xampp\htdocs\citycrm\system\classes\Kohana\Request.php(997): Kohana_Request_Client->execute(Object(Request))
#13 C:\xampp\htdocs\citycrm\index.php(118): Kohana_Request->execute()
#14 {main} in C:\xampp\htdocs\citycrm\application\views\guests\edit.php:329
2024-01-02 00:15:16 --- CRITICAL: ErrorException [ 4 ]: syntax error, unexpected ' ~ APPPATH\views\guests\edit.php [ 343 ] in file:line
2024-01-02 00:15:16 --- DEBUG: #0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main} in file:line
2024-01-02 08:51:11 --- DEBUG: Получил запрос в dashboard in C:\xampp\htdocs\citycrm\system\classes\Kohana\Controller.php:69
2024-01-02 08:57:13 --- CRITICAL: Database_Exception [ HY000 ]: SQLSTATE[HY000]: General error: 0 [Gemini InterBase ODBC Driver][INTERBASE]Dynamic SQL Error. SQL error code = -104. Unexpected end of command.  (SQLPrepare[0] at ext\pdo_odbc\odbc_driver.c:206) [ select p.id_pep
		,p.id_org
		, p.surname
		, p.name
		, p.patronymic
		, p.numdoc
		, p.datedoc
		, p."ACTIVE" as is_active
		, p.flag
		, p.sysnote
		, p.time_stamp
		, p.tabnum
		
		from people p

        where p.id_pep= ] ~ APPPATH\classes\Kohana\Database\PDO.php [ 159 ] in C:\xampp\htdocs\citycrm\application\classes\Kohana\Database\Query.php:255
2024-01-02 08:57:13 --- DEBUG: #0 C:\xampp\htdocs\citycrm\application\classes\Kohana\Database\Query.php(255): Kohana_Database_PDO->query(1, 'select p.id_pep...', false, Array)
#1 C:\xampp\htdocs\citycrm\application\classes\Guest.php(61): Kohana_Database_Query->execute(Object(Database_PDO))
#2 C:\xampp\htdocs\citycrm\application\classes\Controller\Guests.php(289): Guest->__construct('')
#3 C:\xampp\htdocs\citycrm\system\classes\Kohana\Controller.php(84): Controller_Guests->action_save()
#4 [internal function]: Kohana_Controller->execute()
#5 C:\xampp\htdocs\citycrm\system\classes\Kohana\Request\Client\Internal.php(97): ReflectionMethod->invoke(Object(Controller_Guests))
#6 C:\xampp\htdocs\citycrm\system\classes\Kohana\Request\Client.php(114): Kohana_Request_Client_Internal->execute_request(Object(Request), Object(Response))
#7 C:\xampp\htdocs\citycrm\system\classes\Kohana\Request.php(997): Kohana_Request_Client->execute(Object(Request))
#8 C:\xampp\htdocs\citycrm\index.php(118): Kohana_Request->execute()
#9 {main} in C:\xampp\htdocs\citycrm\application\classes\Kohana\Database\Query.php:255
2024-01-02 08:57:18 --- CRITICAL: Database_Exception [ HY000 ]: SQLSTATE[HY000]: General error: 0 [Gemini InterBase ODBC Driver][INTERBASE]Dynamic SQL Error. SQL error code = -104. Unexpected end of command.  (SQLPrepare[0] at ext\pdo_odbc\odbc_driver.c:206) [ select p.id_pep
		,p.id_org
		, p.surname
		, p.name
		, p.patronymic
		, p.numdoc
		, p.datedoc
		, p."ACTIVE" as is_active
		, p.flag
		, p.sysnote
		, p.time_stamp
		, p.tabnum
		
		from people p

        where p.id_pep= ] ~ APPPATH\classes\Kohana\Database\PDO.php [ 159 ] in C:\xampp\htdocs\citycrm\application\classes\Kohana\Database\Query.php:255
2024-01-02 08:57:18 --- DEBUG: #0 C:\xampp\htdocs\citycrm\application\classes\Kohana\Database\Query.php(255): Kohana_Database_PDO->query(1, 'select p.id_pep...', false, Array)
#1 C:\xampp\htdocs\citycrm\application\classes\Guest.php(61): Kohana_Database_Query->execute(Object(Database_PDO))
#2 C:\xampp\htdocs\citycrm\application\classes\Controller\Guests.php(289): Guest->__construct('')
#3 C:\xampp\htdocs\citycrm\system\classes\Kohana\Controller.php(84): Controller_Guests->action_save()
#4 [internal function]: Kohana_Controller->execute()
#5 C:\xampp\htdocs\citycrm\system\classes\Kohana\Request\Client\Internal.php(97): ReflectionMethod->invoke(Object(Controller_Guests))
#6 C:\xampp\htdocs\citycrm\system\classes\Kohana\Request\Client.php(114): Kohana_Request_Client_Internal->execute_request(Object(Request), Object(Response))
#7 C:\xampp\htdocs\citycrm\system\classes\Kohana\Request.php(997): Kohana_Request_Client->execute(Object(Request))
#8 C:\xampp\htdocs\citycrm\index.php(118): Kohana_Request->execute()
#9 {main} in C:\xampp\htdocs\citycrm\application\classes\Kohana\Database\Query.php:255
2024-01-02 08:57:27 --- CRITICAL: Database_Exception [ HY000 ]: SQLSTATE[HY000]: General error: 0 [Gemini InterBase ODBC Driver][INTERBASE]Dynamic SQL Error. SQL error code = -104. Unexpected end of command.  (SQLPrepare[0] at ext\pdo_odbc\odbc_driver.c:206) [ select p.id_pep
		,p.id_org
		, p.surname
		, p.name
		, p.patronymic
		, p.numdoc
		, p.datedoc
		, p."ACTIVE" as is_active
		, p.flag
		, p.sysnote
		, p.time_stamp
		, p.tabnum
		
		from people p

        where p.id_pep= ] ~ APPPATH\classes\Kohana\Database\PDO.php [ 159 ] in C:\xampp\htdocs\citycrm\application\classes\Kohana\Database\Query.php:255
2024-01-02 08:57:27 --- DEBUG: #0 C:\xampp\htdocs\citycrm\application\classes\Kohana\Database\Query.php(255): Kohana_Database_PDO->query(1, 'select p.id_pep...', false, Array)
#1 C:\xampp\htdocs\citycrm\application\classes\Guest.php(61): Kohana_Database_Query->execute(Object(Database_PDO))
#2 C:\xampp\htdocs\citycrm\application\classes\Controller\Guests.php(289): Guest->__construct('')
#3 C:\xampp\htdocs\citycrm\system\classes\Kohana\Controller.php(84): Controller_Guests->action_save()
#4 [internal function]: Kohana_Controller->execute()
#5 C:\xampp\htdocs\citycrm\system\classes\Kohana\Request\Client\Internal.php(97): ReflectionMethod->invoke(Object(Controller_Guests))
#6 C:\xampp\htdocs\citycrm\system\classes\Kohana\Request\Client.php(114): Kohana_Request_Client_Internal->execute_request(Object(Request), Object(Response))
#7 C:\xampp\htdocs\citycrm\system\classes\Kohana\Request.php(997): Kohana_Request_Client->execute(Object(Request))
#8 C:\xampp\htdocs\citycrm\index.php(118): Kohana_Request->execute()
#9 {main} in C:\xampp\htdocs\citycrm\application\classes\Kohana\Database\Query.php:255
2024-01-02 09:31:37 --- CRITICAL: Database_Exception [ HY000 ]: SQLSTATE[HY000]: General error: 0 [Gemini InterBase ODBC Driver][INTERBASE]Dynamic SQL Error. SQL error code = -104. Unexpected end of command.  (SQLPrepare[0] at ext\pdo_odbc\odbc_driver.c:206) [ select p.id_pep
		,p.id_org
		, p.surname
		, p.name
		, p.patronymic
		, p.numdoc
		, p.datedoc
		, p."ACTIVE" as is_active
		, p.flag
		, p.sysnote
		, p.time_stamp
		, p.tabnum
		
		from people p

        where p.id_pep= ] ~ APPPATH\classes\Kohana\Database\PDO.php [ 159 ] in C:\xampp\htdocs\citycrm\application\classes\Kohana\Database\Query.php:255
2024-01-02 09:31:37 --- DEBUG: #0 C:\xampp\htdocs\citycrm\application\classes\Kohana\Database\Query.php(255): Kohana_Database_PDO->query(1, 'select p.id_pep...', false, Array)
#1 C:\xampp\htdocs\citycrm\application\classes\Guest.php(61): Kohana_Database_Query->execute(Object(Database_PDO))
#2 C:\xampp\htdocs\citycrm\application\classes\Controller\Guests.php(289): Guest->__construct('')
#3 C:\xampp\htdocs\citycrm\system\classes\Kohana\Controller.php(84): Controller_Guests->action_save()
#4 [internal function]: Kohana_Controller->execute()
#5 C:\xampp\htdocs\citycrm\system\classes\Kohana\Request\Client\Internal.php(97): ReflectionMethod->invoke(Object(Controller_Guests))
#6 C:\xampp\htdocs\citycrm\system\classes\Kohana\Request\Client.php(114): Kohana_Request_Client_Internal->execute_request(Object(Request), Object(Response))
#7 C:\xampp\htdocs\citycrm\system\classes\Kohana\Request.php(997): Kohana_Request_Client->execute(Object(Request))
#8 C:\xampp\htdocs\citycrm\index.php(118): Kohana_Request->execute()
#9 {main} in C:\xampp\htdocs\citycrm\application\classes\Kohana\Database\Query.php:255
2024-01-02 09:32:40 --- CRITICAL: Database_Exception [ HY000 ]: SQLSTATE[HY000]: General error: 0 [Gemini InterBase ODBC Driver][INTERBASE]Dynamic SQL Error. SQL error code = -104. Unexpected end of command.  (SQLPrepare[0] at ext\pdo_odbc\odbc_driver.c:206) [ select p.id_pep
		,p.id_org
		, p.surname
		, p.name
		, p.patronymic
		, p.numdoc
		, p.datedoc
		, p."ACTIVE" as is_active
		, p.flag
		, p.sysnote
		, p.time_stamp
		, p.tabnum
		
		from people p

        where p.id_pep= ] ~ APPPATH\classes\Kohana\Database\PDO.php [ 159 ] in C:\xampp\htdocs\citycrm\application\classes\Kohana\Database\Query.php:255
2024-01-02 09:32:40 --- DEBUG: #0 C:\xampp\htdocs\citycrm\application\classes\Kohana\Database\Query.php(255): Kohana_Database_PDO->query(1, 'select p.id_pep...', false, Array)
#1 C:\xampp\htdocs\citycrm\application\classes\Guest.php(61): Kohana_Database_Query->execute(Object(Database_PDO))
#2 C:\xampp\htdocs\citycrm\application\classes\Controller\Guests.php(289): Guest->__construct('')
#3 C:\xampp\htdocs\citycrm\system\classes\Kohana\Controller.php(84): Controller_Guests->action_save()
#4 [internal function]: Kohana_Controller->execute()
#5 C:\xampp\htdocs\citycrm\system\classes\Kohana\Request\Client\Internal.php(97): ReflectionMethod->invoke(Object(Controller_Guests))
#6 C:\xampp\htdocs\citycrm\system\classes\Kohana\Request\Client.php(114): Kohana_Request_Client_Internal->execute_request(Object(Request), Object(Response))
#7 C:\xampp\htdocs\citycrm\system\classes\Kohana\Request.php(997): Kohana_Request_Client->execute(Object(Request))
#8 C:\xampp\htdocs\citycrm\index.php(118): Kohana_Request->execute()
#9 {main} in C:\xampp\htdocs\citycrm\application\classes\Kohana\Database\Query.php:255
2024-01-02 09:32:48 --- CRITICAL: Database_Exception [ HY000 ]: SQLSTATE[HY000]: General error: 0 [Gemini InterBase ODBC Driver][INTERBASE]Dynamic SQL Error. SQL error code = -104. Unexpected end of command.  (SQLPrepare[0] at ext\pdo_odbc\odbc_driver.c:206) [ select p.id_pep
		,p.id_org
		, p.surname
		, p.name
		, p.patronymic
		, p.numdoc
		, p.datedoc
		, p."ACTIVE" as is_active
		, p.flag
		, p.sysnote
		, p.time_stamp
		, p.tabnum
		
		from people p

        where p.id_pep= ] ~ APPPATH\classes\Kohana\Database\PDO.php [ 159 ] in C:\xampp\htdocs\citycrm\application\classes\Kohana\Database\Query.php:255
2024-01-02 09:32:48 --- DEBUG: #0 C:\xampp\htdocs\citycrm\application\classes\Kohana\Database\Query.php(255): Kohana_Database_PDO->query(1, 'select p.id_pep...', false, Array)
#1 C:\xampp\htdocs\citycrm\application\classes\Guest.php(61): Kohana_Database_Query->execute(Object(Database_PDO))
#2 C:\xampp\htdocs\citycrm\application\classes\Controller\Guests.php(289): Guest->__construct('')
#3 C:\xampp\htdocs\citycrm\system\classes\Kohana\Controller.php(84): Controller_Guests->action_save()
#4 [internal function]: Kohana_Controller->execute()
#5 C:\xampp\htdocs\citycrm\system\classes\Kohana\Request\Client\Internal.php(97): ReflectionMethod->invoke(Object(Controller_Guests))
#6 C:\xampp\htdocs\citycrm\system\classes\Kohana\Request\Client.php(114): Kohana_Request_Client_Internal->execute_request(Object(Request), Object(Response))
#7 C:\xampp\htdocs\citycrm\system\classes\Kohana\Request.php(997): Kohana_Request_Client->execute(Object(Request))
#8 C:\xampp\htdocs\citycrm\index.php(118): Kohana_Request->execute()
#9 {main} in C:\xampp\htdocs\citycrm\application\classes\Kohana\Database\Query.php:255
2024-01-02 09:41:27 --- DEBUG: SQLSTATE[23000]: Integrity constraint violation: -803 [Gemini InterBase ODBC Driver][INTERBASE]violation of PRIMARY or UNIQUE KEY constraint "PK_CARD" on table "CARD".  (SQLExecute[-803] at ext\pdo_odbc\odbc_stmt.c:254) [ INSERT INTO CARD (ID_CARD,ID_DB,ID_PEP, TIMESTART,TIMEEND,STATUS,"ACTIVE",FLAG,ID_CARDTYPE)
					VALUES (
					'33333333'
					,1
					,15314
					, '29.12.2023'
					,'31.12.2023'
					
					,0
					,1
					,1
					,1
					) ] in C:\xampp\htdocs\citycrm\application\classes\Controller\Guests.php:753
2024-01-02 09:42:19 --- DEBUG: SQLSTATE[23000]: Integrity constraint violation: -803 [Gemini InterBase ODBC Driver][INTERBASE]violation of PRIMARY or UNIQUE KEY constraint "PK_CARD" on table "CARD".  (SQLExecute[-803] at ext\pdo_odbc\odbc_stmt.c:254) [ INSERT INTO CARD (ID_CARD,ID_DB,ID_PEP, TIMESTART,TIMEEND,STATUS,"ACTIVE",FLAG,ID_CARDTYPE)
					VALUES (
					'33333333'
					,1
					,15315
					, '29.12.2023'
					,'31.12.2023'
					
					,0
					,1
					,1
					,1
					) ] in C:\xampp\htdocs\citycrm\application\classes\Controller\Guests.php:753