<?php defined('SYSPATH') OR die('No direct access allowed.');

/*
06.2023
Эта модель позволяет подготовить список организаций в виде дерева.
ссылки
https://xhtml.ru/2022/html/tree-views/
https://habr.com/ru/articles/477520/ 
*/

class Model_Treeorg extends Model {
	public $id_parking=0;


/*сортировка входящего массива по иерархии
* входной массив имеет формат (`id`, `title`, `parent`)
* выходной массив имеет такой же формат, но организованный в виде дерева
*корневой элемент должен иметь id=0
*/

public function getTree($dataset) {//Функция построения дерева из массива от Tommy Lacroix
	$tree = array();
	$i=0;
	//echo Debug::vars('24', $dataset); exit;
	foreach ($dataset as $id => &$node) {    
	
		//Если нет вложений
		if (!$node['parent']){
		
			$tree[$id] = &$node;
		}else{ 
			
			//Если есть потомки то перебераем массив
            $dataset[$node['parent']]['childs'][$id] = &$node;
		}
		
	}
	
	return $tree;
}


	public function tplMenu($category){ //Шаблон для вывода меню в виде дерева
		
		$menu = '<li>
			<a href="#" title="'. $category['title'] .'">'. 
			$category['title'].'</a>';
		
		if(is_array(Arr::get($category, 'childs')))
		{
			//echo Debug::vars('39', $category);
			if (Arr::get($category, 'parent') == 0) 
			{
				$menu = '<li><details open><summary>(id='.Arr::get($category, 'id').') '.Arr::get($category, 'title').'</summary>';
			} else {
				$menu = '<li><details><summary>(id='.Arr::get($category, 'id').') '.Arr::get($category, 'title').'</summary>';
			}
		} else {
			
			//if((Arr::get($category, 'busy') != $this->id_parking) and (is_null(Arr::get($category, 'busy'))))
			if(Arr::get($category, 'busy') != $this->id_parking) $menu = '<li>'.Arr::get($category, 'title');// организация занята, нельзя управлять
			if(Arr::get($category, 'busy') == $this->id_parking) $menu = '<li>'.Arr::get($category, 'title'); // должна стоять галочка, разрешено снимать.
			if(is_null(Arr::get($category, 'busy'))) $menu = '<li>'.Arr::get($category, 'title'); // свободна, галочка снята, можно выбирать.
		}

		if(isset($category['childs'])){
				$menu .= '<ul>'. $this->showCat($category['childs']) .'</ul>';
			}
		$menu .= '</li>';
		
		return $menu;
	}




public function tplMenu2($category){ //Шаблон для вывода меню в виде списка select
	
	$menu = '<li>
		<a href="company" title="'. $category['title'] .'">'. 
		$category['title'].'</a>';
	
	if(is_array(Arr::get($category, 'childs')))
	{
		//echo Debug::vars('39', $category);
		if (Arr::get($category, 'parent') == 0) 
		{
			$menu = '<li><details open><summary>(id='.Arr::get($category, 'id').') '.Arr::get($category, 'title').'</summary>';
		} else {
			$menu = '<li><details><summary>(id='.Arr::get($category, 'id').') '.Arr::get($category, 'title').'</summary>';
		}
	} else {
		
		//if((Arr::get($category, 'busy') != $this->id_parking) and (is_null(Arr::get($category, 'busy'))))
		if(Arr::get($category, 'busy') != $this->id_parking) $menu = '<li>'.Form::radio('id_org_for_add_garage['.Arr::get($category, 'id').']', Arr::get($category, 'id'), TRUE, array("disabled"=>"disabled")).'(id='.Arr::get($category, 'id').') '.Arr::get($category, 'title');// организация занята, нельзя управлять
		if(Arr::get($category, 'busy') == $this->id_parking) $menu = '<li>'.Form::radio('id_org_for_add_garage['.Arr::get($category, 'id').']', Arr::get($category, 'id'), TRUE).'(id='.Arr::get($category, 'id').') '.Arr::get($category, 'title'); // должна стоять галочка, разрешено снимать.
		if(is_null(Arr::get($category, 'busy'))) $menu = '<li>'.Form::radio('id_org_for_add_garage['.Arr::get($category, 'id').']', Arr::get($category, 'id'), FALSE).'(id='.Arr::get($category, 'id').') '.Arr::get($category, 'title'); // свободна, галочка снята, можно выбирать.
	}

	if(isset($category['childs'])){
			$menu .= '<ul>'. $this->showCat($category['childs']) .'</ul>';
		}
	$menu .= '</li>';
	
	return $menu;
}



public function tplMenu_anchor($category){ //Шаблон для вывода меню в виде ссылок
	
	$menu = '<li>
		<a href="company" title="'. $category['title'] .'">'. 
		$category['title'].'</a>';
	
	if(is_array(Arr::get($category, 'childs')))
	{
		//echo Debug::vars('39', $category);
		if (Arr::get($category, 'parent') == 0) 
		{
			//$menu = '<li><details open><summary>(id='.Arr::get($category, 'id').') '.Arr::get($category, 'title').'</summary>';
			$menu = '<li><details open><summary>'.Arr::get($category, 'title').'</summary>';
		} else {
			$menu = '<li><details><summary>'.HTML::anchor('companies/edit/'. Arr::get($category, 'id'), Arr::get($category, 'title')).'</summary>';
		}
	} else {
		$menu = '<li>'.HTML::anchor('companies/edit/'. Arr::get($category, 'id'), Arr::get($category, 'title')); // свободна, галочка снята, можно выбирать.
	}

	if(isset($category['childs'])){
			$menu .= '<ul>'. $this->showCat($category['childs']) .'</ul>';
		}
	$menu .= '</li>';
	
	return $menu;
}





	/**
	* 
	**/
	public function showCat($data){ //Рекурсивно считываем наш шаблон
	$string = '';
	foreach($data as $item){
		//$string .= $this->tplMenu($item);
		$string .= $this->tplMenu_anchor($item);
	}
	return $string;
}

			
				
	public function make_tree($org_list, $id_garage)// дерево с расставленными метками: кого можно выбрать, кого нельзя. $org_list  - лист организаций(для построения дерева), $org_busy - список организаций, уже занятых где-то, $garage_info - информация о гараже
	{
		//echo Debug::vars('89', $org_list, $id_garage); exit;
		//$id_parking
		
		$this->id_parking = $id_garage;
		$tree = $this->getTree($org_list);

		//Получаем HTML разметку
		$cat_menu = $this->showCat($tree);
		//echo Debug::vars('62', $cat_menu); exit;

		//Выводим на экран
		return '<ul class="tree">'. $cat_menu .'</ul>';
	}
	
	
	/*
	25.04.2024 Дерево со ссылками на редактирование
	*/
	public function make_tree_anchor($org_list, $id_garage)// дерево с расставленными метками: кого можно выбрать, кого нельзя. $org_list  - лист организаций(для построения дерева), $org_busy - список организаций, уже занятых где-то, $garage_info - информация о гараже
	{
		//echo Debug::vars('89', $org_list, $id_garage); exit;
		//$id_parking
		
		$this->id_parking = $id_garage;
		$tree = $this->getTree($org_list);

		//Получаем HTML разметку
		$cat_menu = $this->showCat($tree);
		//echo Debug::vars('62', $cat_menu); exit;

		//Выводим на экран
		return '<ul class="tree">'. $cat_menu .'</ul>';
	}
	
	
}
