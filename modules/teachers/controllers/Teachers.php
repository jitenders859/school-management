<?php
class Teachers extends Trongate {

    function manage() {
        $this->module('security');
        $data['token'] = $this->security->_make_sure_allowed();
        $data['order_by'] = 'id desc';

        //format the pagination
        $data['total_rows'] = $this->model->count('teachers');
        $data['record_name_plural'] = 'teachers';

        $data['headline'] = 'Manage Teachers';
        $data['view_module'] = 'teachers';
        $data['view_file'] = 'manage';

        $this->template('admin', $data);
    }

    function show() {
        $this->module('security');
        $token = $this->security->_make_sure_allowed();

        $update_id = $this->url->segment(3);

        if ((!is_numeric($update_id)) && ($update_id != '')) {
            redirect('teachers/manage');
        }

        $data = $this->_get_data_from_db($update_id);
        $data['token'] = $token;

        if ($data == false) {
            redirect('teachers/manage');
        } else {
            $data['form_location'] = BASE_URL.'teachers/submit/'.$update_id;
            $data['update_id'] = $update_id;
            $data['headline'] = 'Teacher Information';
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
            redirect('teachers/manage');
        }

        //fetch the form data
        if (($submit == '') && ($update_id > 0)) {
            $data = $this->_get_data_from_db($update_id);
        } else {
            $data = $this->_get_data_from_post();
        }

        $data['headline'] = $this->_get_page_headline($update_id);

        if ($update_id > 0) {
            $data['cancel_url'] = BASE_URL.'teachers/show/'.$update_id;
            $data['btn_text'] = 'UPDATE TEACHER DETAILS';
        } else {
            $data['cancel_url'] = BASE_URL.'teachers/manage';
            $data['btn_text'] = 'CREATE TEACHER RECORD';
        }

        $additional_includes_top[] = 'https://code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css';
        $additional_includes_top[] = 'https://trentrichardson.com/examples/timepicker/jquery-ui-timepicker-addon.css';
        $additional_includes_top[] = 'https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"';
        $additional_includes_top[] = 'https://trentrichardson.com/examples/timepicker/jquery-ui-timepicker-addon.js';
        $additional_includes_top[] = BASE_URL.'admin_files/js/i18n/jquery-ui-timepicker-addon-i18n.min.js';
        $additional_includes_top[] = BASE_URL.'admin_files/js/jquery-ui-sliderAccess.js';
        $data['additional_includes_top'] = $additional_includes_top;

        $data['form_location'] = BASE_URL.'teachers/submit/'.$update_id;
        $data['update_id'] = $update_id;
        $data['view_file'] = 'create';
        $this->template('admin', $data);
    }

    function _get_page_headline($update_id) {
        //figure out what the page headline should be (on the teachers/create page)
        if (!is_numeric($update_id)) {
            $headline = 'Create New Teacher Record';
        } else {
            $headline = 'Update Teacher Details';
        }

        return $headline;
    }

    function submit() {
        $this->module('security');
        $this->security->_make_sure_allowed();

        $submit = $this->input('submit', true);

        if ($submit == 'Submit') {

            $this->validation_helper->set_rules('id_no', 'ID No', 'required|min_length[2]|max_length[255]');
            $this->validation_helper->set_rules('class_id', 'Class ID', 'max_length[11]|numeric|greater_than[0]|required|integer');
            $this->validation_helper->set_rules('section_id', 'Section ID', 'required|max_length[11]|numeric|greater_than[0]|integer');
            $this->validation_helper->set_rules('code', 'Code', 'max_length[50]');
            $this->validation_helper->set_rules('member_id', 'Member ID', 'required|max_length[11]|numeric|greater_than[0]|integer');
            $this->validation_helper->set_rules('subject_id', 'Subject ID', 'required|max_length[11]|numeric|greater_than[0]|integer');

            $result = $this->validation_helper->run();

            if ($result == true) {

                $update_id = $this->url->segment(3);
                $data = $this->_get_data_from_post();
                if (is_numeric($update_id)) {
                    //update an existing record
                    $this->model->update($update_id, $data, 'teachers');
                    $flash_msg = 'The record was successfully updated';
                } else {
                    //insert the new record
                    $update_id = $this->model->insert($data, 'teachers');
                    $flash_msg = 'The record was successfully created';
                }

                set_flashdata($flash_msg);
                redirect('teachers/show/'.$update_id);

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
                redirect('teachers/manage');
            }
        }
    }

    function _get_data_from_db($update_id) {
        $teachers = $this->model->get_where($update_id, 'teachers');

        if ($teachers == false) {
            $this->template('error_404');
            die();
        } else {
            $data['id_no'] = $teachers->id_no;
            $data['class_id'] = $teachers->class_id;
            $data['section_id'] = $teachers->section_id;
            $data['code'] = $teachers->code;
            $data['member_id'] = $teachers->member_id;
            $data['subject_id'] = $teachers->subject_id;
            return $data;
        }
    }

    function _get_data_from_post() {
        $data['id_no'] = $this->input('id_no', true);
        $data['class_id'] = $this->input('class_id', true);
        $data['section_id'] = $this->input('section_id', true);
        $data['code'] = $this->input('code', true);
        $data['member_id'] = $this->input('member_id', true);
        $data['subject_id'] = $this->input('subject_id', true);
        return $data;
    }

}