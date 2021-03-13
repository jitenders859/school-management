<?php
class Parents extends Trongate {

    function manage() {
        $this->module('security');
        $data['token'] = $this->security->_make_sure_allowed();
        $data['order_by'] = 'id desc';

        //format the pagination
        $data['total_rows'] = $this->model->count('parents');
        $data['record_name_plural'] = 'parents';

        $data['headline'] = 'Manage Parents';
        $data['view_module'] = 'parents';
        $data['view_file'] = 'manage';

        $this->template('admin', $data);
    }

    function show() {
        $this->module('security');
        $token = $this->security->_make_sure_allowed();

        $update_id = $this->url->segment(3);

        if ((!is_numeric($update_id)) && ($update_id != '')) {
            redirect('parents/manage');
        }

        $data = $this->_get_data_from_db($update_id);
        $data['token'] = $token;

        if ($data == false) {
            redirect('parents/manage');
        } else {
            $data['form_location'] = BASE_URL.'parents/submit/'.$update_id;
            $data['update_id'] = $update_id;
            $data['headline'] = 'Parent Information';
            $data['view_file'] = 'show';
            $this->template('admin', $data);
        }
    }

    function create() {
        $this->module('security');
        $this->security->_make_sure_allowed();

        $update_id = $this->url->segment(3);
        $submit = $this->input('submit', true);

        if ((!is_numeric($update_id)) && ($update_id != '')) {
            redirect('parents/manage');
        }

        //fetch the form data
        if (($submit == '') && ($update_id > 0)) {
            $data = $this->_get_data_from_db($update_id);
        } else {
            $data = $this->_get_data_from_post();
        }

        $data['headline'] = $this->_get_page_headline($update_id);

        if ($update_id > 0) {
            $data['cancel_url'] = BASE_URL.'parents/show/'.$update_id;
            $data['btn_text'] = 'UPDATE PARENT DETAILS';
        } else {
            $data['cancel_url'] = BASE_URL.'parents/manage';
            $data['btn_text'] = 'CREATE PARENT RECORD';
        }

        $additional_includes_top[] = 'https://code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css';
        $additional_includes_top[] = 'https://trentrichardson.com/examples/timepicker/jquery-ui-timepicker-addon.css';
        $additional_includes_top[] = 'https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"';
        $additional_includes_top[] = 'https://trentrichardson.com/examples/timepicker/jquery-ui-timepicker-addon.js';
        $additional_includes_top[] = BASE_URL.'admin_files/js/i18n/jquery-ui-timepicker-addon-i18n.min.js';
        $additional_includes_top[] = BASE_URL.'admin_files/js/jquery-ui-sliderAccess.js';
        $data['additional_includes_top'] = $additional_includes_top;

        $data['form_location'] = BASE_URL.'parents/submit/'.$update_id;
        $data['update_id'] = $update_id;
        $data['view_file'] = 'create';
        $this->template('admin', $data);
    }

    function _get_page_headline($update_id) {
        //figure out what the page headline should be (on the parents/create page)
        if (!is_numeric($update_id)) {
            $headline = 'Create New Parent Record';
        } else {
            $headline = 'Update Parent Details';
        }

        return $headline;
    }

    function submit() {
        $this->module('security');
        $this->security->_make_sure_allowed();

        $submit = $this->input('submit', true);

        if ($submit == 'Submit') {

            $this->validation_helper->set_rules('email_address', 'Email Address', 'valid_email');
            $this->validation_helper->set_rules('code', 'Code', 'max_length[50]');
            $this->validation_helper->set_rules('occupation', 'Occupation', 'max_length[255]');
            $this->validation_helper->set_rules('child_id', 'Child ID', 'required|max_length[11]|numeric|greater_than[0]|integer');
            $this->validation_helper->set_rules('member_id', 'Member ID', 'required|max_length[11]|numeric|greater_than[0]|integer');

            $result = $this->validation_helper->run();

            if ($result == true) {

                $update_id = $this->url->segment(3);
                $data = $this->_get_data_from_post();
                if (is_numeric($update_id)) {
                    //update an existing record
                    $this->model->update($update_id, $data, 'parents');
                    $flash_msg = 'The record was successfully updated';
                } else {
                    //insert the new record
                    $update_id = $this->model->insert($data, 'parents');
                    $flash_msg = 'The record was successfully created';
                }

                set_flashdata($flash_msg);
                redirect('parents/show/'.$update_id);

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
                redirect('parents/manage');
            }
        }
    }

    function _get_data_from_db($update_id) {
        $parents = $this->model->get_where($update_id, 'parents');

        if ($parents == false) {
            $this->template('error_404');
            die();
        } else {
            $data['email_address'] = $parents->email_address;
            $data['mobile_number'] = $parents->mobile_number;
            $data['code'] = $parents->code;
            $data['occupation'] = $parents->occupation;
            $data['child_id'] = $parents->child_id;
            $data['member_id'] = $parents->member_id;
            return $data;
        }
    }

    function _get_data_from_post() {
        $data['email_address'] = $this->input('email_address', true);
        $data['mobile_number'] = $this->input('mobile_number', true);
        $data['code'] = $this->input('code', true);
        $data['occupation'] = $this->input('occupation', true);
        $data['child_id'] = $this->input('child_id', true);
        $data['member_id'] = $this->input('member_id', true);
        return $data;
    }

}