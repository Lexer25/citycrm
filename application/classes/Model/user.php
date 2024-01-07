<?php defined('SYSPATH') OR die('No direct access allowed.');

    class Model_User
        extends Model_Auth_User {
			
				protected $_table_name = 'users';
				protected $_primary_key = 'id';
				
				protected $_table_columns = array(
				'id' => NULL,
				'email' => NULL,
				'username' => NULL,
				'password' => NULL,
				'logins' => NULL,
				'last_login' => NULL,
				'name' => NULL,
				'surname' => NULL,
				'language' => NULL,
				'listsize' => NULL,
			  ); 



        public function getCount($filter = null) {
			
            
			$users = ORM::factory('user');
			
			
            if ($filter)
                $users->where('username', 'like', "%$filter%")
                      ->or_where('email', 'like', "%$filter%");
            $count = $users->count_all();

            return $count;
        }

        public function getList($page = 1, $perpage = 10, $filter = null) {
            $users = ORM::factory('user');
            if ($filter)
                $users->where('username', 'LIKE', "%$filter%")
                      ->or_where('email', 'like', "%$filter%");
            $list = $users
                ->offset(($page - 1) * $perpage)
                ->limit($perpage)
                ->find_all();

            return $list;
        }

        public function getListFilters($page = 1, $perpage = 10, $filter = array()) {
            $users = ORM::factory('user');

            if ($filter['search'])
                $users->where('username', 'LIKE', "%{$filter['search']}%");

            if ($filter['object'])
                $users->where('object_id', '=', $filter['object']);

            $list = $users
                ->offset(($page - 1) * $perpage)
                ->limit($perpage)
                ->find_all();

            return $list;
        }


        public function getNames() {
            $users = ORM::factory('user');

            $list = $users->find_all()
                          ->as_array();

            return $list;
        }

        public function getUserACL1($user) // исходный текст
        {
            $data = array();

            $sql = 'SELECT id_group, name FROM "GROUP"';

            $res = DB::query(Database::SELECT, $sql)
                     ->execute(Database::instance('fb'));

            $tmp = $res->as_array();

            foreach ($tmp as $row) {
                $data[$row['ID_GROUP']] = array(
                    'name'     => $row['NAME'],
                    'o_view'   => 0,
                    'o_edit'   => 0,
                    'o_add'    => 0,
                    'o_delete' => 0,
                    'p_edit'   => 0,
                    'p_add'    => 0,
                    'p_delete' => 0,
                    'c_edit'   => 0,
                    'c_add'    => 0,
                    'c_delete' => 0
                );
            }

            $sql = "SELECT * FROM usersgroups WHERE id_user = $user";

            $res = DB::query(Database::SELECT, $sql)
                     ->execute(Database::instance('fb'));

            $tmp = $res->as_array();

            foreach ($tmp as $row) {
                if (array_key_exists($row['ID_GROUP'], $data)) {
                    $data[$row['ID_GROUP']]['o_view']   = $row['O_VIEW'];
                    $data[$row['ID_GROUP']]['o_edit']   = $row['O_EDIT'];
                    $data[$row['ID_GROUP']]['o_add']    = $row['O_ADD'];
                    $data[$row['ID_GROUP']]['o_delete'] = $row['O_DELETE'];
                    $data[$row['ID_GROUP']]['p_edit']   = $row['P_EDIT'];
                    $data[$row['ID_GROUP']]['p_add']    = $row['P_ADD'];
                    $data[$row['ID_GROUP']]['p_delete'] = $row['P_DELETE'];
                    $data[$row['ID_GROUP']]['c_edit']   = $row['C_EDIT'];
                    $data[$row['ID_GROUP']]['c_add']    = $row['C_ADD'];
                    $data[$row['ID_GROUP']]['c_delete'] = $row['C_DELETE'];
                }
            }

            return $data;
        }

        public function getUserACL($user) // add 17.12.2013
        {
            $data = array();
            /*
            $sql = 'SELECT id_group, name FROM "GROUP"';

            $res = DB::query(Database::SELECT, $sql)
                ->execute(Database::instance('fb'));

            $tmp = $res->as_array();
            */
            $tmp = array(
                'o_view'   => 0,
                'o_edit'   => 0,
                'o_add'    => 0,
                'o_delete' => 0,
                'p_edit'   => 0,
                'p_add'    => 0,
                'p_delete' => 0,
                'c_edit'   => 0,
                'c_add'    => 0,
                'c_delete' => 0
            ); // подставновка значений
            foreach ($tmp as $row) {
                $data['0'] = array(
                    'name'     => $row['NAME'],
                    'o_view'   => 0,
                    'o_edit'   => 0,
                    'o_add'    => 0,
                    'o_delete' => 0,
                    'p_edit'   => 0,
                    'p_add'    => 0,
                    'p_delete' => 0,
                    'c_edit'   => 0,
                    'c_add'    => 0,
                    'c_delete' => 0
                );
            }

            /*
            $sql = "SELECT * FROM usersgroups WHERE id_user = $user";

            $res = DB::query(Database::SELECT, $sql)
                ->execute(Database::instance('fb'));

            $tmp = $res->as_array();
            */

            foreach ($tmp as $row) {
                if (array_key_exists($row['ID_GROUP'], $data)) {
                    $data[$row['ID_GROUP']]['o_view']   = $row['O_VIEW'];
                    $data[$row['ID_GROUP']]['o_edit']   = $row['O_EDIT'];
                    $data[$row['ID_GROUP']]['o_add']    = $row['O_ADD'];
                    $data[$row['ID_GROUP']]['o_delete'] = $row['O_DELETE'];
                    $data[$row['ID_GROUP']]['p_edit']   = $row['P_EDIT'];
                    $data[$row['ID_GROUP']]['p_add']    = $row['P_ADD'];
                    $data[$row['ID_GROUP']]['p_delete'] = $row['P_DELETE'];
                    $data[$row['ID_GROUP']]['c_edit']   = $row['C_EDIT'];
                    $data[$row['ID_GROUP']]['c_add']    = $row['C_ADD'];
                    $data[$row['ID_GROUP']]['c_delete'] = $row['C_DELETE'];
                }
            }

            return $data;
        }

        public function getGroupACL_original($group) // ������� ����� getGroupACL
        {
            //$sql =	"SELECT u.*, g.* FROM users u LEFT OUTER JOIN (SELECT * FROM users_groups WHERE id_group = $group) g ON u.id = g.id_user";

            //$res = DB::query(Database::SELECT, $sql)
            //	->execute(Database::instance('default'));

            //return $res->as_array();

            $data = array();

            $sql = "SELECT * FROM users";
            $res = DB::query(Database::SELECT, $sql)
                     ->execute(Database::instance('default'));

            $tmp = $res->as_array();

            foreach ($tmp as $row) {
                $data[$row['id']] = array(
                    'id'       => $row['id'],
                    'name'     => $row['name'],
                    'surname'  => $row['surname'],
                    'o_view'   => 0,
                    'o_edit'   => 0,
                    'o_add'    => 0,
                    'o_delete' => 0,
                    'p_edit'   => 0,
                    'p_add'    => 0,
                    'p_delete' => 0,
                    'c_edit'   => 0,
                    'c_add'    => 0,
                    'c_delete' => 0
                );
            }

            $sql = "SELECT * FROM usersgroups WHERE id_group = $group";
            $res = DB::query(Database::SELECT, $sql)
                     ->execute(Database::instance('fb'));

            $tmp = $res->as_array();

            foreach ($tmp as $row) {
                if (array_key_exists($row['ID_USER'], $data)) {
                    $data[$row['ID_USER']]['o_view']   = $row['O_VIEW'];
                    $data[$row['ID_USER']]['o_edit']   = $row['O_EDIT'];
                    $data[$row['ID_USER']]['o_add']    = $row['O_ADD'];
                    $data[$row['ID_USER']]['o_delete'] = $row['O_DELETE'];
                    $data[$row['ID_USER']]['p_edit']   = $row['P_EDIT'];
                    $data[$row['ID_USER']]['p_add']    = $row['P_ADD'];
                    $data[$row['ID_USER']]['p_delete'] = $row['P_DELETE'];
                    $data[$row['ID_USER']]['c_edit']   = $row['C_EDIT'];
                    $data[$row['ID_USER']]['c_add']    = $row['C_ADD'];
                    $data[$row['ID_USER']]['c_delete'] = $row['C_DELETE'];
                }
            }

            return $data;
        }

        public function getGroupACL($group) // ���������� ����� getGroupACL
        {

            $data = array();

            $sql = "SELECT * FROM users";
            $res = DB::query(Database::SELECT, $sql)
                     ->execute(Database::instance('default')); // ������� ������ � ������������ �� ������� MYSQL

            $tmp = $res->as_array();

            foreach ($tmp as $row) {
                $data[$row['id']] = array(
                    'id'       => $row['id'],
                    'name'     => $row['name'],
                    'surname'  => $row['surname'],
                    'o_view'   => 0,
                    'o_edit'   => 0,
                    'o_add'    => 0,
                    'o_delete' => 0,
                    'p_edit'   => 0,
                    'p_add'    => 0,
                    'p_delete' => 0,
                    'c_edit'   => 0,
                    'c_add'    => 0,
                    'c_delete' => 0
                );
            }

            /*
            $sql = "SELECT * FROM usersgroups WHERE id_group = $group";
            $res = DB::query(Database::SELECT, $sql)
                ->execute(Database::instance('fb'));

            $tmp = $res->as_array();
            */
            $tmp = array(
                'o_view'   => 0,
                'o_edit'   => 0,
                'o_add'    => 0,
                'o_delete' => 0,
                'p_edit'   => 0,
                'p_add'    => 0,
                'p_delete' => 0,
                'c_edit'   => 0,
                'c_add'    => 0,
                'c_delete' => 0
            ); // ������ ���� ������ �������.
            foreach ($tmp as $row) {
                if (array_key_exists($row['ID_USER'], $data)) {
                    $data[$row['ID_USER']]['o_view']   = $row['O_VIEW'];
                    $data[$row['ID_USER']]['o_edit']   = $row['O_EDIT'];
                    $data[$row['ID_USER']]['o_add']    = $row['O_ADD'];
                    $data[$row['ID_USER']]['o_delete'] = $row['O_DELETE'];
                    $data[$row['ID_USER']]['p_edit']   = $row['P_EDIT'];
                    $data[$row['ID_USER']]['p_add']    = $row['P_ADD'];
                    $data[$row['ID_USER']]['p_delete'] = $row['P_DELETE'];
                    $data[$row['ID_USER']]['c_edit']   = $row['C_EDIT'];
                    $data[$row['ID_USER']]['c_add']    = $row['C_ADD'];
                    $data[$row['ID_USER']]['c_delete'] = $row['C_DELETE'];
                }
            }

            return $data;
        }

        public function setGroupACL($group, $user, $data) {
            $this->setUserACL($user, $group, $data);
        }

        public function setUserACL($user, $group, $data) {
            $sql = "DELETE FROM usersgroups WHERE id_user = :user AND id_group = :group";
            $res = DB::query(Database::DELETE, $sql)
                     ->parameters(array(
                    ':user'  => $user,
                    ':group' => $group
                ))
                     ->execute(Database::instance('fb'));

            $sql = 'INSERT INTO usersgroups (id_user, id_group, "O_VIEW", "O_EDIT", "O_ADD", "O_DELETE", "P_EDIT", "P_ADD", "P_DELETE", "C_EDIT", "C_ADD", "C_DELETE") ' .
                'VALUES (:user, :group, :view1, :edit1, :add1, :delete1, :edit2, :add2, :delete2, :edit3, :add3, :delete3)';
            $res = DB::query(Database::INSERT, $sql)
                     ->parameters(array(
                    ':view1'   => $data['o_view'],
                    ':edit1'   => $data['o_edit'],
                    ':add1'    => $data['o_add'],
                    ':delete1' => $data['o_delete'],
                    ':edit2'   => $data['p_edit'],
                    ':add2'    => $data['p_add'],
                    ':delete2' => $data['p_delete'],
                    ':edit3'   => $data['c_edit'],
                    ':add3'    => $data['c_add'],
                    ':delete3' => $data['c_delete'],
                    ':user'    => $user,
                    ':group'   => $group
                ))
                     ->execute(Database::instance('fb'));
        }

        public function getUser($id) {
            if (!is_numeric($id))
                return false;

            $user = ORM::factory('user')
                       ->find($id);

            return $user;
        }

        public function force_login($user, $mark_session_as_forced = false) {
            if (!is_object($user)) {
                $username = $user;

                $user = ORM::factory('user');
                $user->where($user->unique_key($username), '=', $username)
                     ->find();
            }

            if ($mark_session_as_forced === true) {
                Session::instance()->set('auth_forced', $user->username);
            }

            return parent::complete_login($user);
        }
    }