
2024-01-01 23:04:45 --- DEBUG: 178 Array to string conversion in C:\xampp\htdocs\citycrm\application\classes\Guest.php:135
2024-01-01 23:22:19 --- CRITICAL: ErrorException [ 4 ]: syntax error, unexpected ';' ~ APPPATH\classes\Controller\Guests.php [ 668 ] in file:line
2024-01-01 23:22:19 --- DEBUG: #0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main} in file:line
2024-01-01 23:22:31 --- CRITICAL: ErrorException [ 4 ]: syntax error, unexpected '=' ~ APPPATH\classes\Guest.php [ 420 ] in file:line
2024-01-01 23:22:31 --- DEBUG: #0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main} in file:line
2024-01-01 23:22:47 --- CRITICAL: ErrorException [ 4 ]: syntax error, unexpected '=' ~ APPPATH\classes\Guest.php [ 420 ] in file:line
2024-01-01 23:22:47 --- DEBUG: #0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main} in file:line
2024-01-01 23:22:52 --- CRITICAL: ErrorException [ 4 ]: syntax error, unexpected '=' ~ APPPATH\classes\Guest.php [ 420 ] in file:line
2024-01-01 23:22:52 --- DEBUG: #0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main} in file:line
2024-01-01 23:23:02 --- CRITICAL: ErrorException [ 4 ]: syntax error, unexpected '=' ~ APPPATH\classes\Guest.php [ 420 ] in file:line
2024-01-01 23:23:02 --- DEBUG: #0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main} in file:line
2024-01-01 23:23:29 --- CRITICAL: ErrorException [ 8 ]: Undefined variable: card ~ APPPATH\views\guests\list.php [ 160 ] in C:\xampp\htdocs\citycrm\application\views\guests\list.php:160
2024-01-01 23:23:29 --- DEBUG: #0 C:\xampp\htdocs\citycrm\application\views\guests\list.php(160): Kohana_Core::error_handler(8, 'Undefined varia...', 'C:\\xampp\\htdocs...', 160, Array)
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
#14 {main} in C:\xampp\htdocs\citycrm\application\views\guests\list.php:160
2024-01-01 23:37:45 --- DEBUG: <pre class="debug"><small>string</small><span>(5)</span> "15313"
<small>integer</small> 0</pre> in file:line
2024-01-01 23:37:46 --- DEBUG: <pre class="debug"><small>string</small><span>(5)</span> "15313"</pre> in C:\xampp\htdocs\citycrm\application\tests\GuestTest.php:36
2024-01-01 23:37:46 --- DEBUG: <pre class="debug"><small>NULL</small></pre> in C:\xampp\htdocs\citycrm\application\tests\GuestTest.php:58
2024-01-01 23:49:11 --- CRITICAL: ErrorException [ 8 ]: Undefined variable: contact ~ APPPATH\classes\Controller\Guests.php [ 356 ] in C:\xampp\htdocs\citycrm\application\classes\Controller\Guests.php:356
2024-01-01 23:49:11 --- DEBUG: #0 C:\xampp\htdocs\citycrm\application\classes\Controller\Guests.php(356): Kohana_Core::error_handler(8, 'Undefined varia...', 'C:\\xampp\\htdocs...', 356, Array)
#1 C:\xampp\htdocs\citycrm\system\classes\Kohana\Controller.php(84): Controller_Guests->action_edit()
#2 [internal function]: Kohana_Controller->execute()
#3 C:\xampp\htdocs\citycrm\system\classes\Kohana\Request\Client\Internal.php(97): ReflectionMethod->invoke(Object(Controller_Guests))
#4 C:\xampp\htdocs\citycrm\system\classes\Kohana\Request\Client.php(114): Kohana_Request_Client_Internal->execute_request(Object(Request), Object(Response))
#5 C:\xampp\htdocs\citycrm\system\classes\Kohana\Request.php(997): Kohana_Request_Client->execute(Object(Request))
#6 C:\xampp\htdocs\citycrm\index.php(118): Kohana_Request->execute()
#7 {main} in C:\xampp\htdocs\citycrm\application\classes\Controller\Guests.php:356
2024-01-01 23:53:01 --- CRITICAL: ErrorException [ 4 ]: syntax error, unexpected ')', expecting ',' or ';' ~ APPPATH\views\guests\edit.php [ 244 ] in file:line
2024-01-01 23:53:01 --- DEBUG: #0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main} in file:line
2024-01-01 23:57:19 --- CRITICAL: ErrorException [ 8 ]: Undefined variable: key ~ APPPATH\views\guests\edit.php [ 318 ] in C:\xampp\htdocs\citycrm\application\views\guests\edit.php:318
2024-01-01 23:57:19 --- DEBUG: #0 C:\xampp\htdocs\citycrm\application\views\guests\edit.php(318): Kohana_Core::error_handler(8, 'Undefined varia...', 'C:\\xampp\\htdocs...', 318, Array)
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
#14 {main} in C:\xampp\htdocs\citycrm\application\views\guests\edit.php:318