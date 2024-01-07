
2024-01-06 17:44:30 --- CRITICAL: ErrorException [ 1 ]: Call to a member function restore_environment() on null ~ MODPATH\unittest\classes\Kohana\Unittest\TestCase.php [ 65 ] in file:line
2024-01-06 17:44:30 --- DEBUG: #0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main} in file:line
2024-01-06 17:45:33 --- CRITICAL: ErrorException [ 1 ]: Call to a member function restore_environment() on null ~ MODPATH\unittest\classes\Kohana\Unittest\TestCase.php [ 65 ] in file:line
2024-01-06 17:45:33 --- DEBUG: #0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main} in file:line
2024-01-06 17:49:39 --- CRITICAL: ErrorException [ 1 ]: Call to a member function restore_environment() on null ~ MODPATH\unittest\classes\Kohana\Unittest\TestCase.php [ 65 ] in file:line
2024-01-06 17:49:39 --- DEBUG: #0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main} in file:line
2024-01-06 17:52:20 --- CRITICAL: ErrorException [ 1 ]: Call to a member function restore_environment() on null ~ MODPATH\unittest\classes\Kohana\Unittest\TestCase.php [ 65 ] in file:line
2024-01-06 17:52:20 --- DEBUG: #0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main} in file:line
2024-01-06 17:53:25 --- CRITICAL: ErrorException [ 1 ]: Call to a member function restore_environment() on null ~ MODPATH\unittest\classes\Kohana\Unittest\TestCase.php [ 65 ] in file:line
2024-01-06 17:53:25 --- DEBUG: #0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main} in file:line
2024-01-06 17:55:01 --- CRITICAL: ErrorException [ 1 ]: Call to a member function restore_environment() on null ~ MODPATH\unittest\classes\Kohana\Unittest\TestCase.php [ 65 ] in file:line
2024-01-06 17:55:01 --- DEBUG: #0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main} in file:line
2024-01-06 17:55:53 --- CRITICAL: ErrorException [ 1 ]: Call to a member function restore_environment() on null ~ MODPATH\unittest\classes\Kohana\Unittest\TestCase.php [ 65 ] in file:line
2024-01-06 17:55:53 --- DEBUG: #0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main} in file:line
2024-01-06 17:56:22 --- CRITICAL: ErrorException [ 1 ]: Call to a member function restore_environment() on null ~ MODPATH\unittest\classes\Kohana\Unittest\TestCase.php [ 65 ] in file:line
2024-01-06 17:56:22 --- DEBUG: #0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main} in file:line
2024-01-06 17:56:53 --- CRITICAL: ErrorException [ 1 ]: Call to a member function restore_environment() on null ~ MODPATH\unittest\classes\Kohana\Unittest\TestCase.php [ 65 ] in file:line
2024-01-06 17:56:53 --- DEBUG: #0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main} in file:line
2024-01-06 17:58:25 --- CRITICAL: ErrorException [ 1 ]: Call to undefined method Controller::factory() ~ APPPATH\tests\GuestModelTest.php [ 31 ] in file:line
2024-01-06 17:58:25 --- DEBUG: #0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main} in file:line
2024-01-06 17:59:45 --- CRITICAL: ErrorException [ 1 ]: Call to undefined method Controller::factory() ~ APPPATH\tests\GuestModelTest.php [ 31 ] in file:line
2024-01-06 17:59:45 --- DEBUG: #0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main} in file:line
2024-01-06 18:00:18 --- CRITICAL: ErrorException [ 1 ]: Call to a member function restore_environment() on null ~ MODPATH\unittest\classes\Kohana\Unittest\TestCase.php [ 65 ] in file:line
2024-01-06 18:00:18 --- DEBUG: #0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main} in file:line
2024-01-06 18:01:03 --- CRITICAL: ErrorException [ 1 ]: Call to a member function restore_environment() on null ~ MODPATH\unittest\classes\Kohana\Unittest\TestCase.php [ 65 ] in file:line
2024-01-06 18:01:03 --- DEBUG: #0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main} in file:line
2024-01-06 21:43:16 --- DEBUG: SQLSTATE[HY000]: General error: -413 [Gemini InterBase ODBC Driver][INTERBASE]conversion error from string "".  (SQLExecute[-413] at ext\pdo_odbc\odbc_stmt.c:254) [ INSERT INTO CARD (ID_CARD,ID_DB,ID_PEP, TIMESTART,TIMEEND,STATUS,"ACTIVE",FLAG,ID_CARDTYPE)
					VALUES (
					''
					,1
					,15480
					, ''
					,''
					
					,0
					,1
					,1
					,1
					) ] in C:\xampp\htdocs\citycrm\application\classes\Controller\Guests.php:348
2024-01-06 22:14:36 --- CRITICAL: ErrorException [ 8 ]: Undefined variable: arrayImage ~ APPPATH\views\Alert.php [ 12 ] in C:\xampp\htdocs\citycrm\application\views\Alert.php:12
2024-01-06 22:14:36 --- DEBUG: #0 C:\xampp\htdocs\citycrm\application\views\Alert.php(12): Kohana_Core::error_handler(8, 'Undefined varia...', 'C:\\xampp\\htdocs...', 12, Array)
#1 C:\xampp\htdocs\citycrm\application\views\guests\edit.php(186): include('C:\\xampp\\htdocs...')
#2 C:\xampp\htdocs\citycrm\system\classes\Kohana\View.php(62): include('C:\\xampp\\htdocs...')
#3 C:\xampp\htdocs\citycrm\system\classes\Kohana\View.php(359): Kohana_View::capture('C:\\xampp\\htdocs...', Array)
#4 C:\xampp\htdocs\citycrm\system\classes\Kohana\View.php(236): Kohana_View->render()
#5 C:\xampp\htdocs\citycrm\application\views\template.php(46): Kohana_View->__toString()
#6 C:\xampp\htdocs\citycrm\system\classes\Kohana\View.php(62): include('C:\\xampp\\htdocs...')
#7 C:\xampp\htdocs\citycrm\system\classes\Kohana\View.php(359): Kohana_View::capture('C:\\xampp\\htdocs...', Array)
#8 C:\xampp\htdocs\citycrm\system\classes\Kohana\Controller\Template.php(44): Kohana_View->render()
#9 C:\xampp\htdocs\citycrm\system\classes\Kohana\Controller.php(87): Kohana_Controller_Template->after()
#10 [internal function]: Kohana_Controller->execute()
#11 C:\xampp\htdocs\citycrm\system\classes\Kohana\Request\Client\Internal.php(97): ReflectionMethod->invoke(Object(Controller_Guests))
#12 C:\xampp\htdocs\citycrm\system\classes\Kohana\Request\Client.php(114): Kohana_Request_Client_Internal->execute_request(Object(Request), Object(Response))
#13 C:\xampp\htdocs\citycrm\system\classes\Kohana\Request.php(997): Kohana_Request_Client->execute(Object(Request))
#14 C:\xampp\htdocs\citycrm\index.php(118): Kohana_Request->execute()
#15 {main} in C:\xampp\htdocs\citycrm\application\views\Alert.php:12
2024-01-06 22:15:07 --- CRITICAL: ErrorException [ 8 ]: Undefined variable: arrayImage ~ APPPATH\views\Alert.php [ 12 ] in C:\xampp\htdocs\citycrm\application\views\Alert.php:12
2024-01-06 22:15:07 --- DEBUG: #0 C:\xampp\htdocs\citycrm\application\views\Alert.php(12): Kohana_Core::error_handler(8, 'Undefined varia...', 'C:\\xampp\\htdocs...', 12, Array)
#1 C:\xampp\htdocs\citycrm\application\views\guests\edit.php(186): include('C:\\xampp\\htdocs...')
#2 C:\xampp\htdocs\citycrm\system\classes\Kohana\View.php(62): include('C:\\xampp\\htdocs...')
#3 C:\xampp\htdocs\citycrm\system\classes\Kohana\View.php(359): Kohana_View::capture('C:\\xampp\\htdocs...', Array)
#4 C:\xampp\htdocs\citycrm\system\classes\Kohana\View.php(236): Kohana_View->render()
#5 C:\xampp\htdocs\citycrm\application\views\template.php(46): Kohana_View->__toString()
#6 C:\xampp\htdocs\citycrm\system\classes\Kohana\View.php(62): include('C:\\xampp\\htdocs...')
#7 C:\xampp\htdocs\citycrm\system\classes\Kohana\View.php(359): Kohana_View::capture('C:\\xampp\\htdocs...', Array)
#8 C:\xampp\htdocs\citycrm\system\classes\Kohana\Controller\Template.php(44): Kohana_View->render()
#9 C:\xampp\htdocs\citycrm\system\classes\Kohana\Controller.php(87): Kohana_Controller_Template->after()
#10 [internal function]: Kohana_Controller->execute()
#11 C:\xampp\htdocs\citycrm\system\classes\Kohana\Request\Client\Internal.php(97): ReflectionMethod->invoke(Object(Controller_Guests))
#12 C:\xampp\htdocs\citycrm\system\classes\Kohana\Request\Client.php(114): Kohana_Request_Client_Internal->execute_request(Object(Request), Object(Response))
#13 C:\xampp\htdocs\citycrm\system\classes\Kohana\Request.php(997): Kohana_Request_Client->execute(Object(Request))
#14 C:\xampp\htdocs\citycrm\index.php(118): Kohana_Request->execute()
#15 {main} in C:\xampp\htdocs\citycrm\application\views\Alert.php:12
2024-01-06 22:46:06 --- DEBUG: 178 SQLSTATE[23000]: Integrity constraint violation: -803 [Gemini InterBase ODBC Driver][INTERBASE]violation of PRIMARY or UNIQUE KEY constraint "UNQ_SS_ACCESSUSER" on table "SS_ACCESSUSER".  (SQLExecute[-803] at ext\pdo_odbc\odbc_stmt.c:254) [ INSERT INTO SS_ACCESSUSER (ID_DB,ID_PEP,ID_ACCESSNAME,USERNAME) VALUES (1,15482,4,'ADMIN') ] in C:\xampp\htdocs\citycrm\application\classes\Controller\Guests.php:280
2024-01-06 22:53:50 --- DEBUG: 178 SQLSTATE[23000]: Integrity constraint violation: -803 [Gemini InterBase ODBC Driver][INTERBASE]violation of PRIMARY or UNIQUE KEY constraint "UNQ_SS_ACCESSUSER" on table "SS_ACCESSUSER".  (SQLExecute[-803] at ext\pdo_odbc\odbc_stmt.c:254) [ INSERT INTO SS_ACCESSUSER (ID_DB,ID_PEP,ID_ACCESSNAME,USERNAME) VALUES (1,15483,4,'ADMIN') ] in C:\xampp\htdocs\citycrm\application\classes\Controller\Guests.php:280