<?php
class Trongate_users extends Trongate {

    function _get_user_id_from_token($userTypes) {
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

}