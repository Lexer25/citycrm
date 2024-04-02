https://kohana.top/3.3/guide/kohana/files
Это шаблон для создания отдельных модулей.
Содержимое этой папке равносильно включению файлов папки template в папку \application\


для включения модуля в файле C:\xampp\htdocs\citycrm\application\bootstrap.php
надо разрешить подключение этого модуля строкой
	'template' => MODPATH . 'template', // подключение модуля template
	
Исп. Бухаров А.В.
28.03.2024