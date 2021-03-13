<?php
class Sections extends Trongate {

    function _init_picture_settings() { 
        $picture_settings['targetModule'] = 'sections';
        $picture_settings['maxFileSize'] = 2000;
        $picture_settings['maxWidth'] = 1200;
        $picture_settings['maxHeight'] = 1200;
        $picture_settings['resizedMaxWidth'] = 450;
        $picture_settings['resizedMaxHeight'] = 450;
        $picture_settings['destination'] = 'sections_pics';
        $picture_settings['targetColumnName'] = 'picture';
        $picture_settings['thumbnailDir'] = 'sections_pics_thumbnails';
        $picture_settings['thumbnailMaxWidth'] = 120;
        $picture_settings['thumbnailMaxHeight'] = 120;
        return $picture_settings;
    }

    function manage() {
        $this->module('security');
        $data['token'] = $this->security->_make_sure_allowed();
        $data['order_by'] = 'priority';

        //format the pagination
        $data['total_rows'] = $this->model->count('sections');
        $data['record_name_plural'] = 'sections';

        $data['headline'] = 'Manage Sections';
        $data['view_module'] = 'sections';
        $data['view_file'] = 'manage';

        $this->template('admin', $data);
    }

    function show() {
        $this->module('security');
        $token = $this->security->_make_sure_allowed();

        $update_id = $this->url->segment(3);

        if ((!is_numeric($update_id)) && ($update_id != '')) {
            redirect('sections/manage');
        }

        $data = $this->_get_data_from_db($update_id);
        $data['token'] = $token;

        if ($data == false) {
            redirect('sections/manage');
        } else {
            $data['form_location'] = BASE_URL.'sections/submit/'.$update_id;
            $data['update_id'] = $update_id;
            $data['headline'] = 'Section Information';
            $data['published'] = $this->_boolean_to_words($data['published']);
            $data['view_file'] = 'show';
            $this->template('admin', $data);
        }
    }

    function _get_section_types_options($selected_key) {

        if ($selected_key == '') {
            $options[''] = 'Select...';
        }
        
        $sql = "select * from section_types order by title";
        $rows = $this->model->query($sql, 'object');

        foreach ($rows as $row) {
            $row_desc = $row->title;;
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
            redirect('sections/manage');
        }

        //fetch the form data
        if (($submit == '') && ($update_id > 0)) {
            $data = $this->_get_data_from_db($update_id);
        } else {
            $data = $this->_get_data_from_post();
        }

        if ($data['section_types_id'] == 0) {
            $data['section_types_id'] = '';
        }

        $data['section_types_options'] = $this->_get_section_types_options($data['section_types_id']);
        $data['headline'] = $this->_get_page_headline($update_id);

        if ($update_id > 0) {
            $data['cancel_url'] = BASE_URL.'sections/show/'.$update_id;
            $data['btn_text'] = 'UPDATE SECTION DETAILS';
        } else {
            $data['cancel_url'] = BASE_URL.'sections/manage';
            $data['btn_text'] = 'CREATE SECTION RECORD';
        }

        $additional_includes_top[] = 'https://code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css';
        $additional_includes_top[] = 'https://trentrichardson.com/examples/timepicker/jquery-ui-timepicker-addon.css';
        $additional_includes_top[] = 'https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"';
        $additional_includes_top[] = 'https://trentrichardson.com/examples/timepicker/jquery-ui-timepicker-addon.js';
        $additional_includes_top[] = BASE_URL.'admin_files/js/i18n/jquery-ui-timepicker-addon-i18n.min.js';
        $additional_includes_top[] = BASE_URL.'admin_files/js/jquery-ui-sliderAccess.js';
        $data['additional_includes_top'] = $additional_includes_top;

        $data['form_location'] = BASE_URL.'sections/submit/'.$update_id;
        $data['update_id'] = $update_id;
        $data['view_file'] = 'create';
        $this->template('admin', $data);
    }

    function _get_page_headline($update_id) {
        //figure out what the page headline should be (on the sections/create page)
        if (!is_numeric($update_id)) {
            $headline = 'Create New Section Record';
        } else {
            $headline = 'Update Section Details';
        }

        return $headline;
    }

    function submit() {
        $this->module('security');
        $this->security->_make_sure_allowed();

        $submit = $this->input('submit', true);

        if ($submit == 'Submit') {

            $this->validation_helper->set_rules('title', 'Title', 'required|min_length[2]|max_length[255]');
            $this->validation_helper->set_rules('introduction', 'Introduction', 'required|min_length[2]');
            $this->validation_helper->set_rules('priority', 'Priority', 'required|max_length[11]|numeric|greater_than[0]|integer');

            $result = $this->validation_helper->run();

            if ($result == true) {

                $update_id = $this->url->segment(3);
                $data = $this->_get_data_from_post();
                settype($data['published'], 'int');
                if (is_numeric($update_id)) {
                    //update an existing record
                    $this->model->update($update_id, $data, 'sections');
                    $flash_msg = 'The record was successfully updated';
                } else {
                    //insert the new record
                    $data['code'] = make_rand_str(6);
                    $update_id = $this->model->insert($data, 'sections');
                    $flash_msg = 'The record was successfully created';
                }

                set_flashdata($flash_msg);
                redirect('sections/show/'.$update_id);

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
                redirect('sections/manage');
            }
        }
    }

    function _get_data_from_db($update_id) {
        $sections = $this->model->get_where($update_id, 'sections');

        if ($sections == false) {
            $this->template('error_404');
            die();
        } else {
            $data['title'] = $sections->title;
            $data['introduction'] = $sections->introduction;
            $data['priority'] = $sections->priority;
            $data['code'] = $sections->code;
            $data['published'] = $sections->published;
            $data['section_types_id'] = $sections->section_types_id;
            return $data;
        }
    }

    function _get_data_from_post() {
        $data['title'] = $this->input('title', true);
        $data['introduction'] = $this->input('introduction', true);
        $data['priority'] = $this->input('priority', true);
        $data['published'] = $this->input('published', true);
        $data['section_types_id'] = $this->input('section_types_id', true);
        return $data;
    }

    function _boolean_to_words($value) {
        if ($value == 1) {
            $value = 'yes';
        } else {
            $value = 'no';
        }
        return $value;
    }

    function _prep_output($output) {
        $output['body'] = json_decode($output['body']);
        foreach($output['body'] as $key => $value) {
            $output['body'][$key] ->published = $this->_boolean_to_words($value->published);
        }

        $output['body'] = json_encode($output['body']);

        return $output;
    }

}