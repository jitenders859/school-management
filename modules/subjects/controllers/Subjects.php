<?php
class Subjects extends Trongate {

    function manage() {
        $this->module('security');
        $data['token'] = $this->security->_make_sure_allowed();
        $data['order_by'] = 'date_created desc';

        //format the pagination
        $data['total_rows'] = $this->model->count('subjects');
        $data['record_name_plural'] = 'subjects';

        $data['headline'] = 'Manage Subjects';
        $data['view_module'] = 'subjects';
        $data['view_file'] = 'manage';

        $this->template('admin', $data);
    }

    function show() {
        $this->module('security');
        $token = $this->security->_make_sure_allowed();

        $update_id = $this->url->segment(3);

        if ((!is_numeric($update_id)) && ($update_id != '')) {
            redirect('subjects/manage');
        }

        $data = $this->_get_data_from_db($update_id);
        $data['token'] = $token;

        if ($data == false) {
            redirect('subjects/manage');
        } else {
            $data['form_location'] = BASE_URL.'subjects/submit/'.$update_id;
            $data['update_id'] = $update_id;
            $data['headline'] = 'Subject Information';
            $data['view_file'] = 'show';
            $this->template('admin', $data);
        }
    }

    function _get_classes_options($selected_key) {

        if ($selected_key == '') {
            $options[''] = 'Select...';
        }
        
        $sql = "select * from classes order by id";
        $rows = $this->model->query($sql, 'object');

        foreach ($rows as $row) {
            $row_desc = $row->id;;
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
            redirect('subjects/manage');
        }

        //fetch the form data
        if (($submit == '') && ($update_id > 0)) {
            $data = $this->_get_data_from_db($update_id);
        } else {
            $data = $this->_get_data_from_post();
        }

        if ($data['classes_id'] == 0) {
            $data['classes_id'] = '';
        }

        $data['classes_options'] = $this->_get_classes_options($data['classes_id']);
        $data['headline'] = $this->_get_page_headline($update_id);

        if ($update_id > 0) {
            $data['cancel_url'] = BASE_URL.'subjects/show/'.$update_id;
            $data['btn_text'] = 'UPDATE SUBJECT DETAILS';
        } else {
            $data['cancel_url'] = BASE_URL.'subjects/manage';
            $data['btn_text'] = 'CREATE SUBJECT RECORD';
        }

        $additional_includes_top[] = 'https://code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css';
        $additional_includes_top[] = 'https://trentrichardson.com/examples/timepicker/jquery-ui-timepicker-addon.css';
        $additional_includes_top[] = 'https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"';
        $additional_includes_top[] = 'https://trentrichardson.com/examples/timepicker/jquery-ui-timepicker-addon.js';
        $additional_includes_top[] = BASE_URL.'admin_files/js/i18n/jquery-ui-timepicker-addon-i18n.min.js';
        $additional_includes_top[] = BASE_URL.'admin_files/js/jquery-ui-sliderAccess.js';
        $data['additional_includes_top'] = $additional_includes_top;

        $data['form_location'] = BASE_URL.'subjects/submit/'.$update_id;
        $data['update_id'] = $update_id;
        $data['view_file'] = 'create';
        $this->template('admin', $data);
    }

    function _get_page_headline($update_id) {
        //figure out what the page headline should be (on the subjects/create page)
        if (!is_numeric($update_id)) {
            $headline = 'Create New Subject Record';
        } else {
            $headline = 'Update Subject Details';
        }

        return $headline;
    }

    function submit() {
        $this->module('security');
        $this->security->_make_sure_allowed();

        $submit = $this->input('submit', true);

        if ($submit == 'Submit') {

            $this->validation_helper->set_rules('subject_name', 'Subject Name', 'required|min_length[2]|max_length[255]');
            $this->validation_helper->set_rules('subject_intro', 'Subject Intro', 'required|min_length[2]');
            $this->validation_helper->set_rules('date_created', 'Date Created', 'integer');

            $result = $this->validation_helper->run();

            if ($result == true) {

                $update_id = $this->url->segment(3);
                $data = $this->_get_data_from_post();
                if (is_numeric($update_id)) {
                    //update an existing record
                    $this->model->update($update_id, $data, 'subjects');
                    $flash_msg = 'The record was successfully updated';
                } else {
                    //insert the new record
                    $update_id = $this->model->insert($data, 'subjects');
                    $flash_msg = 'The record was successfully created';
                }

                set_flashdata($flash_msg);
                redirect('subjects/show/'.$update_id);

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
                redirect('subjects/manage');
            }
        }
    }

    function _get_data_from_db($update_id) {
        $subjects = $this->model->get_where($update_id, 'subjects');

        if ($subjects == false) {
            $this->template('error_404');
            die();
        } else {
            $data['subject_name'] = $subjects->subject_name;
            $data['subject_intro'] = $subjects->subject_intro;
            $data['school_id'] = $subjects->school_id;
            $data['code'] = $subjects->code;
            $data['date_created'] = $subjects->date_created;
            $data['classes_id'] = $subjects->classes_id;
            return $data;
        }
    }

    function _get_data_from_post() {
        $data['subject_name'] = $this->input('subject_name', true);
        $data['subject_intro'] = $this->input('subject_intro', true);
        $data['date_created'] = $this->input('date_created', true);
        $data['classes_id'] = $this->input('classes_id', true);
        return $data;
    }

}