<?php
class Golden_tickets extends Trongate {

    function _delete_golden_ticket() {
        $params['code'] = $this->url->segment(3);
        $sql = 'delete from golden_tickets where code=:code';
        $this->model->query_bind($sql, $params);
    }

    function _init_join($customer_name, $email, $member_session_id) {
        // insert into golden_tickets table
        $data['email'] = $email;
        $data['code'] = make_rand_str(32);
        $data['session_id'] = $member_session_id;
        $data['date_created'] = time();
       $update_id = $this->model->insert($data, 'golden_tickets');

        $this->_send_welcome_email_auto($update_id, $customer_name);
    }


    function _build_plain_txt_msg($html_msg) {
        $plain_txt_msg = strip_tags($html_msg);
        $plain_txt_msg = nl2br($plain_txt_msg);
        $plain_txt_msg = str_replace('<br />', '\n', $plain_txt_msg);
        return $plain_txt_msg;
    }

    function _send_welcome_email_auto($update_id, $customer_name) {

        $this->module('security');
        $this->security->_make_sure_allowed();

        $data['customer_name'] = $customer_name;
        $golden_ticket_obj = $this->model->get_where($update_id, 'golden_tickets');

        if($golden_ticket_obj == false) {
            echo 'Could not find golden ticket.'; die();
        }
        $data['code'] = $golden_ticket_obj->code;
        $data['email'] = $golden_ticket_obj->email;
        $data['html_msg'] = $this->_build_msg_html($data);
        $data['plain_txt_msg'] = $this->_build_plain_txt_msg($data['html_msg']);
        $data['sender_name'] = "Jitender Singh";
        $data['from_email'] = 'jitenders859@gmail.com';

        // $this->module('email_boss');
        // $this->email_boss->_send($data);
        echo 'great success';
    }

    function send_welcome_email_manual($update_id) {

        $this->module('security');
        $this->security->_make_sure_allowed();

        $data['customer_name'] = $this->input('customer_name');
        $golden_ticket_obj = $this->model->get_where($update_id);

        if($golden_ticket_obj == false) {
            echo 'Could not find golden ticket.'; die();
        }
        $data['code'] = $golden_ticket_obj->code;
        $data['email'] = $golden_ticket_obj->email;
        $data['html_msg'] = $this->_build_msg_html($data);
        $data['plain_txt_msg'] = $this->_build_plain_txt_msg($data['html_msg']);
        $data['sender_name'] = "Jitender Singh";
        $data['from_email'] = 'jitenders859@gmail.com';

        // $this->module('email_boss');
        // $this->email_boss->_send($data);

        set_flashdata('The email was successfully Sent.');
        redirect('golden_tickets/manage');
    }

    function _build_msg_html($data) {
        $data['join_url'] = BASE_URL.'members/start'. $data["code"];
        $html_msg = $this->view('html_msg', $data, true);
        return $html_msg;
    }

    function create_welcome_email() {
        $data['customer_name'] = $this->input('customer_name');
        $data['form_location'] = str_replace('/create_welcome_email', '/send_welcome_email_manual', current_url());
        $data['view_file'] = 'create_email';
        $data['cancel_url'] = BASE_URL.'golden_tickets/manage';
        $this->template('admin', $data);
    }

    function _make_sure_allowed() {
        $code = $this->url->segment(3);

        if(strlen($code) != 32) {
            $this->_not_allowed();
        } else {
            $result = $this->model->get_one_where('code', $code, 'golden_tickets');
            if($result !== false) {
                // code must be valid;
                return $result;
            } else {
                $this->_not_allowed();
            }
        }
    }

    function _not_allowed() {
        echo 'not allowed'; die();
    }

    function manage() {
        $this->module('security');
        $data['token'] = $this->security->_make_sure_allowed();
        $data['order_by'] = 'date_created desc';

        //format the pagination
        $data['total_rows'] = $this->model->count('golden_tickets');
        $data['record_name_plural'] = 'golden tickets';

        $data['headline'] = 'Manage Golden Tickets';
        $data['view_module'] = 'golden_tickets';
        $data['view_file'] = 'manage';

        $this->template('admin', $data);
    }

    function show() {
        $this->module('security');
        $token = $this->security->_make_sure_allowed();

        $update_id = $this->url->segment(3);

        if ((!is_numeric($update_id)) && ($update_id != '')) {
            redirect('golden_tickets/manage');
        }

        $data = $this->_get_data_from_db($update_id);
        $data['token'] = $token;

        if ($data == false) {
            redirect('golden_tickets/manage');
        } else {
            $data['form_location'] = BASE_URL.'golden_tickets/submit/'.$update_id;
            $data['update_id'] = $update_id;
            $data['headline'] = 'Golden Ticket Information';
            $data['view_file'] = 'show';
            $this->template('admin', $data);
        }
    }

    function _create_manual_ticket($school_id, $user_level, $email) {
        if(!isset($school_id) || !isset($user_level) || !isset($email)) {
            redirect(BASE_URL);
        }

        $data['code'] = make_rand_str(32);
        $data['session_id'] = '';
        $data['date_created'] = time();
        $data['trongate_user_levels_id'] = $user_level;
        $data['school_id'] = $school_id;
        $data['email'] = $email;
        $data['session_id'] = session_id();
        $this->model->insert($data);

        return $data['code'];
    }

    function create() {
        $this->module('security');
        $this->security->_make_sure_allowed();

        $update_id = $this->url->segment(3);
        $submit = $this->input('submit', true);

        if ((!is_numeric($update_id)) && ($update_id != '')) {
            redirect('golden_tickets/manage');
        }

        //fetch the form data
        if (($submit == '') && ($update_id > 0)) {
            $data = $this->_get_data_from_db($update_id);
        } else {
            $data = $this->_get_data_from_post();
        }

        $data['headline'] = $this->_get_page_headline($update_id);

        if ($update_id > 0) {
            $data['cancel_url'] = BASE_URL.'golden_tickets/show/'.$update_id;
            $data['btn_text'] = 'UPDATE GOLDEN TICKET DETAILS';
        } else {
            $data['cancel_url'] = BASE_URL.'golden_tickets/manage';
            $data['btn_text'] = 'CREATE GOLDEN TICKET RECORD';
        }

        $additional_includes_top[] = 'https://code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css';
        $additional_includes_top[] = 'https://trentrichardson.com/examples/timepicker/jquery-ui-timepicker-addon.css';
        $additional_includes_top[] = 'https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"';
        $additional_includes_top[] = 'https://trentrichardson.com/examples/timepicker/jquery-ui-timepicker-addon.js';
        $additional_includes_top[] = BASE_URL.'admin_files/js/i18n/jquery-ui-timepicker-addon-i18n.min.js';
        $additional_includes_top[] = BASE_URL.'admin_files/js/jquery-ui-sliderAccess.js';
        $data['additional_includes_top'] = $additional_includes_top;

        $data['form_location'] = BASE_URL.'golden_tickets/submit/'.$update_id;
        $data['update_id'] = $update_id;
        $data['view_file'] = 'create';
        $this->template('admin', $data);
    }

    function _get_page_headline($update_id) {
        //figure out what the page headline should be (on the golden_tickets/create page)
        if (!is_numeric($update_id)) {
            $headline = 'Create New Golden Ticket Record';
        } else {
            $headline = 'Update Golden Ticket Details';
        }

        return $headline;
    }

    function submit() {
        $this->module('security');
        $this->security->_make_sure_allowed();

        $submit = $this->input('submit', true);

        if ($submit == 'Submit') {

            $this->validation_helper->set_rules('email', 'Email', 'required|min_length[7]|max_length[255]|valid_email');

            $result = $this->validation_helper->run();

            if ($result == true) {

                $update_id = $this->url->segment(3);
                $data = $this->_get_data_from_post();

                $data['code'] = make_rand_str(32);
                $data['session_id'] = '';
                $data['date_created'] = time();
                if (is_numeric($update_id)) {
                    //update an existing record
                    $this->model->update($update_id, $data, 'golden_tickets');
                    $flash_msg = 'The record was successfully updated';
                } else {
                    //insert the new record
                    $update_id = $this->model->insert($data, 'golden_tickets');
                    $flash_msg = 'The record was successfully created';
                }

                set_flashdata($flash_msg);
                redirect('golden_tickets/show/'.$update_id);

            } else {
                //form submission error
                $this->create();
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
                redirect('golden_tickets/manage');
            }
        }
    }

    function _get_data_from_db($update_id) {
        $golden_tickets = $this->model->get_where($update_id, 'golden_tickets');

        if ($golden_tickets == false) {
            $this->template('error_404');
            die();
        } else {
            $data['email'] = $golden_tickets->email;
            $data['code'] = $golden_tickets->code;
            $data['session_id'] = $golden_tickets->session_id;
            $data['date_created'] = $golden_tickets->date_created;
            return $data;
        }
    }

    function _get_data_from_post() {
        $data['email'] = $this->input('email', true);
        return $data;
    }

}