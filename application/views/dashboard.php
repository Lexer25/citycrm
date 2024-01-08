<?php 
$configModule=Kohana::$config->load('config_newcrm')->module;
echo '<ul id="shortcut"><li>' . HTML::anchor('/', HTML::image('images/shortcut/home.png') . '<br />' . __('home')) . '</li>';
if (Auth::instance()->logged_in('admin') || Auth::instance()->logged_in('owner')) {
	if(Arr::get($configModule, 'org')) echo '<li>' . HTML::anchor('companies', HTML::image('images/shortcut/company.png') . '<br />' . __('companies')) . '</li>';
	if(Arr::get($configModule, 'contact')) echo '<li>' . HTML::anchor('contacts', HTML::image('images/shortcut/contacts.png') . '<br />' . __('contacts')) . '</li>';
	if(Arr::get($configModule, 'card')) echo '<li>' . HTML::anchor('cards', HTML::image('images/shortcut/card.png') . '<br />' . __('cards')) . '</li>';
	
	if(Arr::get($configModule, 'event')) echo '<li>' . HTML::anchor('eventlog', HTML::image('images/shortcut/note_view.png') . '<br />' . __('eventlog')) . '</li>';
	if(Arr::get($configModule, 'queue')) echo '<li>' . HTML::anchor('queue', HTML::image('images/shortcut/data_out.png') . '<br />' . __('queue.short')) . '</li>';

	if(Arr::get($configModule, 'user')) echo '<li>' . HTML::anchor('users', HTML::image('images/shortcut/users.png') . '<br />' . __('users')) . '</li>';
	if(Arr::get($configModule, 'settings')) echo '<li>' . HTML::anchor('settings', HTML::image('images/shortcut/setting.png') . '<br />' . __('settings')) . '</li>';
	if(Arr::get($configModule, 'stat')) echo '<li>' . HTML::anchor('stats', HTML::image('images/shortcut/stat.png') . '<br />' . __('stat')) . '</li></ul>';
};
echo '</ul>';

if (false)
{
	echo '<br><fieldset><legend>Test</legend>';
	echo 'Test';

	//$door=new Door(559);
	$door=new Door(504);
	//$door=new Door(111);
	//echo Debug::vars('27', $door);
	$dev= new Device($door->parent);
	echo Debug::vars('27', $dev, $dev->checkConnect()); exit;
	//echo Debug::vars('27', $door->getKeyList());
	
	//$ts2client=new TS2client();
	//echo Debug::vars('27', $ts2client);
	//echo Debug::vars('27', $ts2client->startServer());
	//$ts2client->startServer();
	$t1=microtime(true);
	
}