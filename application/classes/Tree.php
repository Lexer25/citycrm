<?php defined('SYSPATH') or die('No direct script access.');
/*
11.08.2023
Бухаров А.В.
Этот класс строит дерево с элементами псевдографики.
*/
class Tree
{
	//https://snipp.ru/php/select-option-tree
	
	/*
	массив array должен иметь такую структуру: 
	array(21) (
    1 => array(4) (
        "id" => string(1) "1"
        "title" => string(6) "все"
        "parent" => integer 0
        "busy" => NULL
    )
    101 => array(4) (
        "id" => string(3) "101"
        "title" => string(50) "ATACONS ГЛАВНЫЕ В ПУСКОНАЛАДКЕ"
        "parent" => string(1) "1"
        "busy" => NULL
    )
    102 => array(4) (
        "id" => string(3) "102"
        "title" => string(6) "ER_ER "
        "parent" => string(1) "1"
        "busy" => NULL
    )
	
	$sub - с какого уровня выводить дерево
	*/
public function array_to_tree($array, $sub = 0)
{
	$a = array();
	foreach($array as $v) {
		if($sub == $v['parent']) {
			$b = $this->array_to_tree($array, $v['id']);
			if(!empty($b)) {
				$a[$v['id']] = $v;
				$a[$v['id']]['children'] = $b;
			} else {
				$a[$v['id']] = $v;
			}
		}
	}
	return $a;
}
 
 /*
 Подготова html select для вывода
$array - результат работы метода  array_to_tree
selected_id - указание на выбранный option
$lelvel 
 */
public function out_options($array, $selected_id = 0, $level = 0) 
{
	$level++;
	$out = '';
	foreach ($array as $i => $row) {
		$out .= '<option value="' . $row['id'] . '"';
		if ($row['id'] == $selected_id) {
			$out .= ' selected';
		}
		$out .= '>';
 
		if ($level > 1) {
			if ($level > 2) {
				$out .= str_repeat('&nbsp;', $level - 1);
			}
			
			$keys = array_keys($array);
			$last_keys = $keys[count($array) - 1];			
			if ($last_keys != $i) {
				$out .= '├';
			} else {
				$out .=  '└';
			}
		}
 
		$out .= $row['title'] . '</option>';
 
		if (!empty($row['children'])) {
			$out .= $this->out_options($row['children'], $selected_id, $level);
		}
	}
	return $out;
}
	
	
}