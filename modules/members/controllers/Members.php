<?php
class Members extends Trongate {

    function test() {
        $token = $this->_make_sure_allowed();
        echo $token;
    }

    function register_video_watched($video_id) {
        if(!is_numeric($video_id)) {
            http_response_code(400);
            die();
        }

        api_auth();

        // get the token from the user
        $token = $_SERVER['HTTP_TRONGATETOKEN'];

        // get the trongate_user_id
        $this->module('trongate_tokens');
        $token_obj = $this->trongate_tokens->_fetch_token_obj($token);
        $trongate_user_id = $token_obj->user_id;


        // fetch the videos watched for this user
        $result = $this->model->get_one_where("trongate_user_id", $trongate_user_id, 'members');
        $videos_watched = $result->videos_watched;
        $update_id = $result->id;

        if($videos_watched !== '') {
            $videos_watched_array = unserialize($videos_watched);
        }

        if(!in_array($video_id, $videos_watched_array)) {
            $videos_watched_array[] = $video_id;

            $data['videos_watched'] = serialize($videos_watched_array);
             $this->model->update($update_id, $data, 'members');

             echo json_encode('great success');
        }

    }

    function get_all_names() {
        $sql = "SELECT * FROM schools";
        $result = $this->model->query($sql);
        var_dump($result);
    }

    function _make_sure_allowed($userType = NULL) {

        //let's assume that only users with a valid token
        //who are user_level_id = 2 can view
        $this->module('trongate_tokens');

        if(is_array($userType) || $userType == "all members") {

            if($userType == "all members") {
                $types = ["teacher", "principal", "vice-principal", "parent", "school", "student"];
                $array = implode(",",$types);
            } else {
                $array = implode(",",$userType);
            }


            $this->module('trongate_users-trongate_user_levels');
            $keys = $this->trongate_user_levels->_get_user_level_from_user_types($array);

            if($keys != false) {
                $token = $this->trongate_tokens->_attempt_get_valid_token($keys);
            }
            echo "system";
            var_dump($keys);
            die();

        } else {
            $token = $this->trongate_tokens->_attempt_get_valid_token( $userType ?? 2);
        }


        if ($token == false) {
            redirect('members/login');
        } else {
            return $token;
        }

    }

    function start() {
        $this->module('golden_tickets');
        $golden_ticket = $this->golden_tickets->_make_sure_allowed();
        $data['email'] = $this->input('email', true);

        if(!isset($data['email']) || $data['email'] == '') {
            $data['email'] = $golden_ticket->email;
        }

        $data['first_name'] = $this->input('first_name', true);
        $data['last_name'] = $this->input('last_name', true);
        $data['username'] = $this->input('username', true);
        $data['mobile_number'] = $this->input('mobile_number', true);
        $data['form_location'] = str_replace('/start', '/submit_create_account', current_url());
        $data['view_module'] = 'members';
        $data['view_file'] = 'create_new_account';
        $this->template('public_milligram', $data);
    }

    function your_account() {
        $this->module('security');
        $token = $this->security->_make_sure_allowed('all members');
        $member_id = $this->_get_member_id();

        $member_obj = $this->model->get_where($member_id, 'members');
        $pword = $member_obj->pword;
        $num_logins = $member_obj->num_logins;

        if ($pword == '') {
            //force user to set a password
            redirect('members/update_password');
        }

        if ($num_logins == 0) {
            //first time visitor!
            redirect('members/welcome');
        }

        $data = (array) $member_obj;
        $data['view_module'] = 'members';
        $data['view_file'] = 'your_account';
        $this->template('members_area', $data);
    }

    function welcome() {
        $this->module('security');
        $token = $this->security->_make_sure_allowed('members area');

        $member_id = $this->_get_member_id();
        $this->_update_num_logins($member_id);

        $data['view_module'] = 'members';
        $data['view_file'] = 'welcome';
        $this->template('members_area', $data);
    }

    function update_account() {
        $this->module('security');
        $token = $this->security->_make_sure_allowed('members area');

        $submit = $this->input('submit', true);

        if ($submit == 'Submit') {
            $data['first_name'] = $this->input('first_name');
            $data['last_name'] = $this->input('last_name');
            $data['username'] = $this->input('username');
            $data['email'] = $this->input('email');
        } else {
            $member_id = $this->_get_member_id();
            $member_obj = $this->model->get_where($member_id, 'members');
            $data = (array) $member_obj;
        }

        $data['form_location'] = str_replace('update_account', 'submit_account_details', current_url());
        $data['view_module'] = 'members';
        $data['view_file'] = 'update_account';
        $this->template('members_area', $data);
    }

    function update_password() {
        $this->module('security');
        $token = $this->security->_make_sure_allowed('members area');

        $member_id = $this->_get_member_id();
        $member_obj = $this->model->get_where($member_id, 'members');

        $num_logins = $member_obj->num_logins;
        if ($num_logins>0) {
            $data['headline'] = 'Update Your Password';
        } else {
            $data['headline'] = 'Please Create A Password';
        }

        $data['form_location'] = str_replace('update_password', 'submit_new_password', current_url());
        $data['view_module'] = 'members';
        $data['view_file'] = 'update_password';
        $this->template('members_area', $data);
    }

    function submit_account_details() {
        $this->module('security');
        $this->security->_make_sure_allowed('members area');

        $submit = $this->input('submit', true);

        if ($submit == 'Submit') {

            $this->validation_helper->set_rules('first_name', 'First Name', 'required|min_length[2]|max_length[110]');
            $this->validation_helper->set_rules('last_name', 'Last Name', 'required|min_length[2]|max_length[85]');
            $this->validation_helper->set_rules('email', 'Email', 'required|min_length[7]|max_length[255]|valid_email');
            $this->validation_helper->set_rules('username', 'Username', 'required|min_length[2]|max_length[150]|callback_member_details_check');

            $result = $this->validation_helper->run();

            if ($result == true) {

                $this->module('members');
                $update_id = $this->members->_get_member_id();

                $data['first_name'] = $this->input('first_name');
                $data['last_name'] = $this->input('last_name');
                $data['email'] = $this->input('email');
                $data['username'] = $this->input('username');

                //update an existing record
                $this->model->update($update_id, $data, 'members');
                $flash_msg = 'Your account details have been successfully updated';

                set_flashdata($flash_msg);
                redirect('members/your_account');

            } else {
                //form submission error
                $this->update_account();
            }

        }
    }

    function submit_new_password() {
        $this->module('security');
        $this->security->_make_sure_allowed('members area');

        $submit = $this->input('submit', true);

        if ($submit == 'Submit') {

            $this->validation_helper->set_rules('pword', 'password', 'required|min_length[7]|max_length[65]');
            $this->validation_helper->set_rules('repeat_pword', 'repeat password', 'required|matches[pword]');

            $result = $this->validation_helper->run();

            if ($result == true) {

                $this->module('members');
                $update_id = $this->members->_get_member_id();
                $pword = $this->input('pword');
                $data['pword'] = $this->members->_hash_string($pword);

                //update an existing record
                $this->model->update($update_id, $data, 'members');

                //only set flashdata if num_logins is > 0 to prevent unwanted flashdata after welcome msg
                $member_obj = $this->model->get_where($update_id, 'members');
                $num_logins = $member_obj->num_logins;

                if ($num_logins>0) {
                    $flash_msg = 'Your password has been successfully updated';
                    set_flashdata($flash_msg);
                }

                redirect('members/your_account');

            } else {
                //form submission error
                $this->update_password();
            }

        }

    }

    function _get_member_id() {
        $member_id = 0;

        //attempt to get trongate token
        $this->module('trongate_tokens');
        $token = $this->trongate_tokens->_attempt_get_valid_token(2);

        if ($token == false) {
            return $member_id;
        } else {

            $sql = 'SELECT
                    members.id
                    FROM
                    members
                    JOIN trongate_tokens
                    ON members.trongate_user_id = trongate_tokens.user_id
                    WHERE trongate_tokens.token = :token';

            $params['token'] = $token;
            $rows = $this->model->query_bind($sql, $params, 'object');

            if (count($rows) == 0) {
                //no record found
                return $member_id;
            } else {
                //get the id from the rows
                $user_obj = $rows[0];
                $member_id = $user_obj->id;
                return $member_id;
            }

        }

    }

    function login() {
        $this->module('trongate_tokens');
        $this->trongate_tokens->_destroy();
        $data['username'] = $this->input('username');
        $data['form_location'] = str_replace('/login', '/submit_login', current_url());
        $data['view_module'] = 'members';
        $data['view_file'] = 'login';
        $this->template('public_milligram', $data);
    }

    function logout() {
        $this->module('trongate_tokens');
        $this->trongate_tokens->_destroy();
        redirect('members/login');
    }

    function manage() {
        $this->module('security');
        $data['token'] = $this->security->_make_sure_allowed();
        $data['order_by'] = 'id';

        //format the pagination
        $data['total_rows'] = $this->model->count('members');
        $data['record_name_plural'] = 'members';

        $data['headline'] = 'Manage Members';
        $data['view_module'] = 'members';
        $data['view_file'] = 'manage';

        $this->template('admin', $data);
    }

    function all_members($member_type) {
        $this->module('security');
        $data['token'] = $this->security->_make_sure_allowed();
        $data['order_by'] = 'id';
        $params['token'] = $data['token'];
        $sql = "SELECT * from members INNER JOIN `trongate_tokens` on members.trongate_user_id = trongate_tokens.id WHERE trongate_tokens.token = :token";
        $user = $this->model->query_bind($sql, $params, 'object');

        //format the pagination
        $params2['school_id'] = $user[0]->school_id;
        $params2['level_title'] = $member_type ?? 'teacher';
        $sql = "SELECT * from members INNER JOIN trongate_users on members.trongate_user_id = trongate_users.id INNER JOIN trongate_users.user_level_id = trongate_user_levels.id where school_id = :school_id && trongate_user_levels.level_title = :level_title";
        $data['rows'] = $this->model->query_bind($sql, $params2, 'object');
        $data['record_name_plural'] = 'teachers';
        $data['headline'] = 'Manage teachers';
        $data['view_module'] = 'members';
        $data['view_file'] = 'all_members';

        $this->template('admin', $data);
    }

    function show_member() {

    }

    function all_students() {
        $this->module('security');
        $data['token'] = $this->security->_make_sure_allowed();
        $data['order_by'] = 'id';

        //format the pagination
        $data['total_rows'] = $this->model->count('members');
        $data['record_name_plural'] = 'members';

        $data['headline'] = 'Manage Members';
        $data['view_module'] = 'members';
        $data['view_file'] = 'manage';

        $this->template('admin', $data);
    }

    function show() {
        $this->module('security');
        $token = $this->security->_make_sure_allowed();

        $update_id = $this->url->segment(3);

        if ((!is_numeric($update_id)) && ($update_id != '')) {
            redirect('members/manage');
        }

        $data = $this->_get_data_from_db($update_id);
        $data['token'] = $token;

        if ($data == false) {
            redirect('members/manage');
        } else {
            $data['form_location'] = BASE_URL.'members/submit/'.$update_id;
            $data['update_id'] = $update_id;
            $data['headline'] = 'Member Information';
            $data['view_file'] = 'show';
            $this->template('admin', $data);
        }
    }

    function _get_parents_options($selected_key) {

        if ($selected_key == '') {
            $options[''] = 'Select...';
        }
        
        $sql = "select * from parents order by child_id";
        $rows = $this->model->query($sql, 'object');

        foreach ($rows as $row) {
            $row_desc = $row->child_id;;
            $options[$row->id] = $row_desc;
        }

        if ($selected_key>0) {
            $row_label = $options[$selected_key];
            $options[0] = strtoupper('*** Disassociate with '.$row_label.' ***');
        }

        return $options;
    }

    function create() {
        $this->module('security');
        $this->security->_make_sure_allowed();

        $update_id = $this->url->segment(3);
        $submit = $this->input('submit', true);

        if ((!is_numeric($update_id)) && ($update_id != '')) {
            redirect('members/manage');
        }

        //fetch the form data
        if (($submit == '') && ($update_id > 0)) {
            $data = $this->_get_data_from_db($update_id);
        } else {
            $data = $this->_get_data_from_post();
        }

        if ($data['parents_id'] == 0) {
            $data['parents_id'] = '';
        }

        $data['parents_options'] = $this->_get_parents_options($data['parents_id']);
        $data['headline'] = $this->_get_page_headline($update_id);

        if ($update_id > 0) {
            $data['cancel_url'] = BASE_URL.'members/show/'.$update_id;
            $data['btn_text'] = 'UPDATE MEMBER DETAILS';
        } else {
            $data['cancel_url'] = BASE_URL.'members/manage';
            $data['btn_text'] = 'CREATE MEMBER RECORD';
        }

        $additional_includes_top[] = 'https://code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css';
        $additional_includes_top[] = 'https://trentrichardson.com/examples/timepicker/jquery-ui-timepicker-addon.css';
        $additional_includes_top[] = 'https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"';
        $additional_includes_top[] = 'https://trentrichardson.com/examples/timepicker/jquery-ui-timepicker-addon.js';
        $additional_includes_top[] = BASE_URL.'admin_files/js/i18n/jquery-ui-timepicker-addon-i18n.min.js';
        $additional_includes_top[] = BASE_URL.'admin_files/js/jquery-ui-sliderAccess.js';
        $data['additional_includes_top'] = $additional_includes_top;

        $data['form_location'] = BASE_URL.'members/submit/'.$update_id;
        $data['update_id'] = $update_id;
        $data['view_file'] = 'create';
        $this->template('admin', $data);
    }

    function _get_page_headline($update_id) {
        //figure out what the page headline should be (on the members/create page)
        if (!is_numeric($update_id)) {
            $headline = 'Create New Member Record';
        } else {
            $headline = 'Update Member Details';
        }

        return $headline;
    }

    function _hash_string($str) {
        $hashed_string = password_hash($str, PASSWORD_BCRYPT, array(
            'cost' => 11
        ));
        return $hashed_string;
    }

    function _verify_hash($plain_text_str, $hashed_string) {
        $result = password_verify($plain_text_str, $hashed_string);
        return $result; //TRUE or FALSE
    }

    function submit_create_account() {

        $this->module('security');
        $this->module('golden_tickets');
        $golden_ticket = $this->golden_tickets->_make_sure_allowed();
        $submit = $this->input('submit', true);


        if ($submit == 'Create Account') {

            $this->validation_helper->set_rules('first_name', 'First Name', 'required|min_length[2]|max_length[110]');
            $this->validation_helper->set_rules('last_name', 'Last Name', 'required|min_length[2]|max_length[85]');
            $this->validation_helper->set_rules('email', 'Email', 'required|min_length[7]|max_length[255]|valid_email|callback_create_account_check');
            $this->validation_helper->set_rules('username', 'Username', 'required|min_length[2]|max_length[150]|callback_username_check');
            $this->validation_helper->set_rules('mobile_number', 'Mobile Number', 'required|min_length[2]|max_length[20]');

            $result = $this->validation_helper->run();

            if ($result == true) {

                //insert the new record
                $data['email'] = $this->input('email');
                $pword = $this->input('pword');
                $data['pword'] = '';
                $data['date_created'] = time();

                //insert a new trongate_user record
               $school_id = $golden_ticket->school_id;
               $trongate_user_levels_id = $golden_ticket->trongate_user_levels_id;

                $trongate_user_data['code'] = make_rand_str(32);
                $trongate_user_data['user_level_id'] = $trongate_user_levels_id; //customer
                $data['trongate_user_id'] = $this->model->insert($trongate_user_data, 'trongate_users');

                $data['code'] = make_rand_str(12);
                $data['first_name'] = $this->input('first_name', true);
                $data['last_name'] = $this->input('last_name', true);
                $data['username'] = $this->input('username', true);
                $data['school_id'] = $school_id;
                $data['num_logins'] = 0;
                // delete the golden ticket
                $this->golden_tickets->_delete_golden_ticket();

                $update_id = $this->model->insert($data, 'members');
                $this->_log_user_in($update_id,  $data['trongate_user_id'], $school_id, false);

            } else {
                //form submission error
                $this->start();
            }

        }

    }

    function _update_num_logins($member_id) {
        $member_obj = $this->model->get_where($member_id, 'members');
        $num_logins = $member_obj->num_logins;
        $data['num_logins'] = $num_logins+1;
        $this->model->update($member_id, $data, 'members');
    }

    function _log_user_in($member_id, $trongate_user_id, $school_id, $update_num_logins=NULL) {

        $this->module('members');
        $this->module('trongate_tokens');

        if (isset($update_num_logins)) {
            $this->_update_num_logins($member_id);
        }

        $token_data['user_id'] = $trongate_user_id;
        $remember = $this->input('remember');

        if ($remember == 1) {
            //set token for 30 days (cookie)
            $thirty_days = 86400*30; //number of seconds in 30 days
            $nowtime = time(); // unix timestamp
            $token_data['expiry_date'] = $nowtime+$thirty_days; //30 days ahead as a timestamp
            $token_data['set_cookie'] = true;
            $token_data['school_id'] = $school_id;
            $this->trongate_tokens->_generate_token($token_data); //generate toke & set cookie

        } else {
            //set short term token (session)
            $_SESSION['trongatetoken'] = $this->trongate_tokens->_generate_token($token_data);
            $_SESSION['school_id'] = $school_id;
        }

        if (isset($update_num_logins)) {
            redirect('dashboard');
        } else {
            redirect('members/your_account');
        }

    }

    function submit() {
        $this->module('security');
        $this->security->_make_sure_allowed();

        $submit = $this->input('submit', true);

        if ($submit == 'Submit') {

            $this->validation_helper->set_rules('first_name', 'First Name', 'required|min_length[2]|max_length[120]');
            $this->validation_helper->set_rules('last_name', 'Last Name', 'required|min_length[2]|max_length[85]');
            $this->validation_helper->set_rules('username', 'Username', 'required|min_length[2]|max_length[150]|callback_username_check');
            $this->validation_helper->set_rules('email', 'Email', 'min_length[7]|max_length[255]|valid email address|valid_email');
            $this->validation_helper->set_rules('mobile_number', 'Mobile Number', 'required|min_length[7]|max_length[20]');

            $result = $this->validation_helper->run();

            if ($result == true) {

                $update_id = $this->url->segment(3);
                $data = $this->_get_data_from_post();
                if (is_numeric($update_id)) {
                    //update an existing record
                    $this->model->update($update_id, $data, 'members');
                    $flash_msg = 'The record was successfully updated';
                } else {
                    //insert the new record
                    $data['date_created'] = time();
                    $data['pword'] = '';

                    //insert a new trongate_user record
                    $trongate_user_data['code'] = make_rand_str(32);
                    $trongate_user_data['user_level_id'] = 2; //customer
                    $data['trongate_user_id'] = $this->model->insert($trongate_user_data, 'trongate_users');
                    $data['num_logins'] = 0;

                    $update_id = $this->model->insert($data, 'members');
                    $flash_msg = 'The record was successfully created';
                }

                set_flashdata($flash_msg);
                redirect('members/show/'.$update_id);

            } else {
                //form submission error
                $this->create();
            }

        }

    }

    function submit_login() {
        $submit = $this->input('submit', true);

        if ($submit == 'Submit') {

            $this->validation_helper->set_rules('username', 'Username', 'required|callback_login_check');
            $this->validation_helper->set_rules('pword', 'Password', 'required');

            $result = $this->validation_helper->run();

            if ($result == true) {

                $username = $this->input('username');
                $member_obj = $this->model->get_one_where('username', $username, 'members');
                $member_id = $member_obj->id;
                $trongate_user_id = $member_obj->trongate_user_id;
                $school_id = $member_obj->school_id;

                $this->_log_user_in($member_id, $trongate_user_id, $school_id, true);
                redirect('dashboard');
            } else {
                $this->login();
            }
        }
    }

    function submit_delete() {
        $this->module('security');
        $this->security->_make_sure_allowed();

        $submit = $this->input('submit', true);

        if ($submit == 'Submit') {
            $update_id = $this->url->segment(3);

            if (!is_numeric($update_id)) {
                die();
            } else {
                $data['update_id'] = $update_id;

                //delete all of the comments associated with this record
                $sql = 'delete from comments where target_table = :module and update_id = :update_id';
                $data['module'] = $this->module;
                $this->model->query_bind($sql, $data);

                //delete the record
                $this->model->delete($update_id, $this->module);

                //set the flashdata
                $flash_msg = 'The record was successfully deleted';
                set_flashdata($flash_msg);

                //redirect to the manage page
                redirect('members/manage');
            }
        }
    }

    function _get_data_from_db($update_id) {
        $members = $this->model->get_where($update_id, 'members');

        if ($members == false) {
            $this->template('error_404');
            die();
        } else {
            $data['date_created'] = date('l jS \of F Y \a\t h:i:s A', $members->date_created);
            $data['first_name'] = $members->first_name;
            $data['last_name'] = $members->last_name;
            $data['username'] = $members->username;
            $data['email'] = $members->email;
            $data['mobile_number'] = $members->mobile_number;
            $data['parents_id'] = $members->parents_id;
            return $data;
        }
    }

    function _get_data_from_post() {
        $data['first_name'] = $this->input('first_name', true);
        $data['last_name'] = $this->input('last_name', true);
        $data['username'] = $this->input('username', true);
        $data['email'] = $this->input('email', true);
        $data['mobile_number'] = $this->input('mobile_number', true);
        $data['parents_id'] = $this->input('parents_id', true);
        return $data;
    }

    function create_account_check($email) {

        //return EITHER a string (error) or true (bool)

        //make sure the email account is available
        $account = $this->model->get_one_where('email', $email, 'members');

        if ($account !== false) {
            //this email must be in use!
            $error = 'The email address that you submitted appears to be in use.';
            return $error;
        } else {

            //the email address is available

            $pword = $this->input('pword');
            $pword_repeat = $this->input('pword_repeat');

            if ($pword !== $pword_repeat) {
                $error = 'The repeat password field must match the password field.';
                return $error;
            } else {
                return true;
            }

        }

    }


    function login_check($username) {

        $error = 'Your username and/or password was not valid.';
        $user_obj = $this->model->get_one_where('username', $username, 'members');

        if ($user_obj == false) {
            //username was NOT valid
            return $error;
        } else {
            //username was correct

            //check to see if the password was correct
            $pword = $this->input('pword');
            $password_result = $this->_verify_hash($pword, $user_obj->pword);

            if ($password_result == false) {
                //wrong password
                return $error;
            } else {
                //password was correct
                return true;
            }
        }
    }

    function username_check($username) {
        $update_id = $this->url->segment(3);

        if (!is_numeric($update_id)) {
            //make sure this username is unique
            $user_obj = $this->model->get_one_where('username', $username, 'members');
            if ($user_obj !== false) {
                $error_msg = 'The username that you submitted is not available.';
                return $error_msg;
            }
        } else {
            return true;
        }
    }

    function member_details_check() {
        //a logged in member has submitted new account details, so...
        //make sure no other account has same email or username
        $params['member_id'] = $this->_get_member_id();
        $params['email'] = $this->input('email');

        $sql1 = 'select * from members where email=:email and id!=:member_id';
        $rows1 = $this->model->query_bind($sql1, $params, 'object');

        if (count($rows1)>0) {
            $error_msg = 'The email address that you submitted is being used by another member.';
            return $error_msg;
        }

        unset($params['email']);
        $params['username'] = $this->input('username');

        $sql2 = 'select * from members where username=:username and id!=:member_id';
        $rows2 = $this->model->query_bind($sql2, $params, 'object');

        if (count($rows2)>0) {
            $error_msg = 'The username that you submitted is being used by another member.';
            return $error_msg;
        }

        return true;

    }

}