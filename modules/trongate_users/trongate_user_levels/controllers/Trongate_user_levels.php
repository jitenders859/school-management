<?php
class Trongate_user_levels extends Trongate {

    function __construct() {
        parent::__construct();
        $this->parent_module = 'trongate_users';
        $this->child_module = 'trongate_user_levels';
    }




// if($rows != false)
// {
//     $keys = [];

//     foreach($rows as $row) {
//         $keys.array_push($row->id);
//     }

//     $token = $this->trongate_tokens->_attempt_get_valid_token($keys);
// }

    function _get_user_level_from_user_types($userTypes) {
        $keys = [];
        // $params['array'] = $array;
        // $types = [];
        $sql = "SELECT trongate_user_levels.id FROM trongate_user_levels WHERE trongate_user_levels.level_title IN ('teacher', 'principal', 'vice-principal', 'parent', 'school', 'student')";
        $rows = $this->model->query($sql);


        if($rows != false)
            {
                foreach($rows as $row) {
                    $keys.array_push($row->id);
                }
            } else {
                return false;
            }

            return $keys;

        // $data['user_id'] = $user_id;
        // $result = $this->model->query_bind($sql, $data, 'array');

        // if (isset($result[0])) {
        // $user_level = $result[0]['level_title'];
        // } else {
        // $user_level = '';
        // }

        // return $user_level;
    }

    function _get_user_level($user_id) {

        $sql = 'SELECT
                    trongate_user_levels.level_title
                FROM
                    trongate_users
                JOIN trongate_user_levels ON trongate_users.user_level_id = trongate_user_levels.id
                where trongate_users.id = :user_id';

        $data['user_id'] = $user_id;
        $result = $this->model->query_bind($sql, $data, 'array');

        if (isset($result[0])) {
            $user_level = $result[0]['level_title'];
        } else {
            $user_level = '';
        }

        return $user_level;
    }

    function __destruct() {
        $this->parent_module = '';
        $this->child_module = '';
    }

}