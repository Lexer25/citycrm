<?php defined('SYSPATH') OR die('No direct access allowed.');

    class Model_Objects extends ORM {
        protected $_table_name = 'objects';
        protected $_primary_key = 'id';

        public function rules() {
            return array(
                'name'          => array(
                    array('not_empty')
                ),
                'config_server' => array(
                    array('not_empty')
                ),
                'config_bdpass' => array(
                    array('not_empty')
                ),
                'config_bduser' => array(
                    array('not_empty')
                ),
                'config_bdfile' => array(
                    array('not_empty')
                )
            );
        }

        public function getList($page = 1, $perpage = 10, $filter = false) {
            $objects = ORM::factory($this->_table_name);

            if ($filter) {
                $objects = $objects->where('name', 'LIKE', '%' . $filter . '%');
            }

            $list = $objects
                ->offset(($page - 1) * $perpage)
                ->limit($perpage)
                ->find_all();

            return $list;
        }
    }
