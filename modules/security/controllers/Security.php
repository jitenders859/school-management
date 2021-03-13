<?php
class Security extends Trongate {

    function _make_sure_allowed($scenario='admin panel') {
        //returns EITHER (trongate)token OR initialises 'not allowed' procedure

        switch ($scenario) {
            case 'teacher':
                $this->module('members');
                $token = $this->members->_make_sure_allowed(4);
                break;
            case 'parent':
                $this->module('members');
                $token = $this->members->_make_sure_allowed(5);
                break;
            case 'student':
                $this->module('members');
                $token = $this->members->_make_sure_allowed(3);
                break;
            case 'principal':
                $this->module('members');
                $token = $this->members->_make_sure_allowed(6);
                break;
            case 'vice-principal':
                $this->module('members');
                $token = $this->members->_make_sure_allowed(7);
                break;
            case 'school':
                $this->module('members');
                $token = $this->members->_make_sure_allowed(8);
                break;
            case 'all members':
                $this->module('members');
                $token = $this->members->_make_sure_allowed($scenario);
                break;
            case 'members area':
                $this->module('members');
                $token = $this->members->_make_sure_allowed();
                break;
            case is_array($scenario):
                $this->module('members');
                $token = $this->members->_make_sure_allowed($scenario);
                break;
            default:
                $this->module('trongate_administrators');
                $token = $this->trongate_administrators->_make_sure_allowed();
                break;
        }

        return $token;
    }

    function _get_user_id() {
        //attempt fetch trongate_user_id (this gets called by the API explorer)
        $trongate_user_id = 0;

        if (isset($_COOKIE['trongatetoken'])) {
            $trongate_user_id = $this->_is_token_valid($_COOKIE['trongatetoken'], true);

            if ($trongate_user_id == 0) {
                //user has an invalid cookie - destroy it
                setcookie('trongatetoken', '', time() - 3600);
            }
        }

        if ((isset($_SESSION['trongatetoken'])) && ($trongate_user_id == 0)) {
            $trongate_user_id = $this->_is_token_valid($_SESSION['trongatetoken'], true);
        }

        return $trongate_user_id;
    }

    function _is_token_valid($token, $return_id=false) {
        $params['token'] = $token;
        $params['nowtime'] = time();
        $sql = 'select * from trongate_tokens where token = :token and expiry_date > :nowtime';
        $rows = $this->model->query_bind($sql, $params, 'object');

        if (count($rows)!==1) {

            if ($return_id == true) {
                return 0;
            } else {
                return false;
            }

        } else {

            if ($return_id == true) {
                $user_obj = $rows[0];
                return $user_obj->user_id;
            } else {
                return true;
            }

        }
    }

}