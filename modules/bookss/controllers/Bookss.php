<?php
class Bookss extends Trongate {

    function _init_picture_settings() { 
        $picture_settings['targetModule'] = 'bookss';
        $picture_settings['maxFileSize'] = 2000;
        $picture_settings['maxWidth'] = 1200;
        $picture_settings['maxHeight'] = 1200;
        $picture_settings['resizedMaxWidth'] = 450;
        $picture_settings['resizedMaxHeight'] = 450;
        $picture_settings['destination'] = 'bookss_pics';
        $picture_settings['targetColumnName'] = 'picture';
        $picture_settings['thumbnailDir'] = 'bookss_pics_thumbnails';
        $picture_settings['thumbnailMaxWidth'] = 120;
        $picture_settings['thumbnailMaxHeight'] = 120;
        return $picture_settings;
    }

    function manage() {
        $this->module('security');
        $data['token'] = $this->security->_make_sure_allowed();
        $data['order_by'] = 'book_name';

        //format the pagination
        $data['total_rows'] = $this->model->count('bookss');
        $data['record_name_plural'] = 'bookss';

        $data['headline'] = 'Manage Bookss';
        $data['view_module'] = 'bookss';
        $data['view_file'] = 'manage';

        $this->template('admin', $data);
    }

    function show() {
        $this->module('security');
        $token = $this->security->_make_sure_allowed();

        $update_id = $this->url->segment(3);

        if ((!is_numeric($update_id)) && ($update_id != '')) {
            redirect('bookss/manage');
        }

        $data = $this->_get_data_from_db($update_id);
        $data['token'] = $token;

        if ($data == false) {
            redirect('bookss/manage');
        } else {
            $data['form_location'] = BASE_URL.'bookss/submit/'.$update_id;
            $data['update_id'] = $update_id;
            $data['headline'] = 'Books Information';
            $data['view_file'] = 'show';
            $this->template('admin', $data);
        }
    }

    function _get_classes_options($selected_key) {

        if ($selected_key == '') {
            $options[''] = 'Select...';
        }

        $sql = '
                SELECT
                    bookss.book_name as assigned_to_description,
                    classes.id,
                    classes.class_title as identifier_column
                FROM
                    classes
                LEFT JOIN bookss ON classes.id = bookss.classes_id
                ORDER BY classes.class_title
        ';

        $rows = $this->model->query($sql, 'object');

        foreach ($rows as $row) {
            $option_id = $row->id;
            $option_value = trim($row->identifier_column);
            $assigned_to_description = trim($row->assigned_to_description);

            if ((trim($assigned_to_description) !== '') && ($option_id !== $selected_key)) {
                $option_value.= ' (currently assigned to '.$assigned_to_description.')';
            }

            $options[$option_id] = $option_value;
        }

        if ($selected_key>0) {
            $options[0] = strtoupper('*** Disassociate from '.$options[$selected_key].' ***');
        }

        return $options;
    }

    function _get_schools_options($selected_key) {

        if ($selected_key == '') {
            $options[''] = 'Select...';
        }

        $sql = '
                SELECT
                    bookss.book_name as assigned_to_description,
                    schools.id,
                    schools.school_name as identifier_column
                FROM
                    schools
                LEFT JOIN bookss ON schools.id = bookss.schools_id
                ORDER BY schools.school_name
        ';

        $rows = $this->model->query($sql, 'object');

        foreach ($rows as $row) {
            $option_id = $row->id;
            $option_value = trim($row->identifier_column);
            $assigned_to_description = trim($row->assigned_to_description);

            if ((trim($assigned_to_description) !== '') && ($option_id !== $selected_key)) {
                $option_value.= ' (currently assigned to '.$assigned_to_description.')';
            }

            $options[$option_id] = $option_value;
        }

        if ($selected_key>0) {
            $options[0] = strtoupper('*** Disassociate from '.$options[$selected_key].' ***');
        }

        return $options;
    }

    function create() {
        $this->module('security');
        $this->security->_make_sure_allowed();

        $update_id = $this->url->segment(3);
        $submit = $this->input('submit', true);

        if ((!is_numeric($update_id)) && ($update_id != '')) {
            redirect('bookss/manage');
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
        if ($data['schools_id'] == 0) {
            $data['schools_id'] = '';
        }

        $data['schools_options'] = $this->_get_schools_options($data['schools_id']);
        $data['headline'] = $this->_get_page_headline($update_id);

        if ($update_id > 0) {
            $data['cancel_url'] = BASE_URL.'bookss/show/'.$update_id;
            $data['btn_text'] = 'UPDATE BOOKS DETAILS';
        } else {
            $data['cancel_url'] = BASE_URL.'bookss/manage';
            $data['btn_text'] = 'CREATE BOOKS RECORD';
        }

        $additional_includes_top[] = 'https://code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css';
        $additional_includes_top[] = 'https://trentrichardson.com/examples/timepicker/jquery-ui-timepicker-addon.css';
        $additional_includes_top[] = 'https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"';
        $additional_includes_top[] = 'https://trentrichardson.com/examples/timepicker/jquery-ui-timepicker-addon.js';
        $additional_includes_top[] = BASE_URL.'admin_files/js/i18n/jquery-ui-timepicker-addon-i18n.min.js';
        $additional_includes_top[] = BASE_URL.'admin_files/js/jquery-ui-sliderAccess.js';
        $data['additional_includes_top'] = $additional_includes_top;

        $data['form_location'] = BASE_URL.'bookss/submit/'.$update_id;
        $data['update_id'] = $update_id;
        $data['view_file'] = 'create';
        $this->template('admin', $data);
    }

    function create_book() {
        $this->module('security');
        $this->module('trongate_tokens');
        $token = $this->security->_make_sure_allowed('school');
        $data['school_id'] = $this->trongate_tokens->_attempt_get_school_id($token);
        $token_obj = $this->trongate_tokens->_fetch_token_obj($token);

        if($token_obj == false) {
            die();
        }

        $data['published_by'] = $this->$token_obj->user_id;

        $update_id = $this->url->segment(3);
        $submit = $this->input('submit', true);

        if ((!is_numeric($update_id)) && ($update_id != '')) {
            redirect('bookss/manage');
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
        if ($data['schools_id'] == 0) {
            $data['schools_id'] = '';
        }

        $data['schools_options'] = $this->_get_schools_options($data['schools_id']);
        $data['headline'] = $this->_get_page_headline($update_id);

        if ($update_id > 0) {
            $data['cancel_url'] = BASE_URL.'bookss/show_book/'.$update_id;
            $data['btn_text'] = 'UPDATE BOOKS DETAILS';
        } else {
            $data['cancel_url'] = BASE_URL.'bookss/all_books';
            $data['btn_text'] = 'CREATE BOOKS RECORD';
        }

        $data['form_location'] = BASE_URL.'bookss/submit/'.$update_id;
        $data['update_id'] = $update_id;
        $data['view_file'] = 'create';
        $this->template('admin', $data);
    }

    function _get_page_headline($update_id) {
        //figure out what the page headline should be (on the bookss/create page)
        if (!is_numeric($update_id)) {
            $headline = 'Create New Books Record';
        } else {
            $headline = 'Update Books Details';
        }

        return $headline;
    }

    function submit() {
        $this->module('security');
        $this->security->_make_sure_allowed();

        $submit = $this->input('submit', true);

        if ($submit == 'Submit') {

            $this->validation_helper->set_rules('book_name', 'Book Name', 'required|min_length[2]|max_length[255]');
            $this->validation_helper->set_rules('subject', 'Subject', 'min_length[2]|max_length[255]');
            $this->validation_helper->set_rules('writter_name', 'Writter Name', 'min_length[2]|max_length[255]');
            $this->validation_helper->set_rules('id_no', 'ID No', 'min_length[2]|max_length[255]');
            $this->validation_helper->set_rules('published_date', 'Published Date', 'required|max_length[11]|numeric|greater_than[0]|integer');

            $result = $this->validation_helper->run();

            if ($result == true) {

                $update_id = $this->url->segment(3);
                $data = $this->_get_data_from_post();
                if (is_numeric($update_id)) {
                    //update an existing record
                    $this->model->update($update_id, $data, 'bookss');
                    $flash_msg = 'The record was successfully updated';
                } else {
                    //insert the new record
                    $update_id = $this->model->insert($data, 'bookss');
                    $flash_msg = 'The record was successfully created';
                }

                        $sync_schools_data['update_id'] = $update_id;
                        $sync_schools_data['schools_id'] = $data['schools_id'];
                        $this->_sync_with_schools($sync_schools_data);



                        $sync_classes_data['update_id'] = $update_id;
                        $sync_classes_data['classes_id'] = $data['classes_id'];
                        $this->_sync_with_classes($sync_classes_data);



                set_flashdata($flash_msg);
                redirect('bookss/show/'.$update_id);

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

                                //remove any existing association with schools module
                                $sql = 'update schools set bookss_id = 0 where bookss_id = '.$update_id;
                                $this->model->query($sql);


                                //remove any existing association with classes module
                                $sql = 'update classes set bookss_id = 0 where bookss_id = '.$update_id;
                                $this->model->query($sql);


                //set the flashdata
                $flash_msg = 'The record was successfully deleted';
                set_flashdata($flash_msg);

                //redirect to the manage page
                redirect('bookss/manage');
            }
        }
    }

    function _get_data_from_db($update_id) {
        $bookss = $this->model->get_where($update_id, 'bookss');

        if ($bookss == false) {
            $this->template('error_404');
            die();
        } else {
            $data['book_name'] = $bookss->book_name;
            $data['subject'] = $bookss->subject;
            $data['writter_name'] = $bookss->writter_name;
            $data['id_no'] = $bookss->id_no;
            $data['published_date'] = $bookss->published_date;
            $data['upload_date'] = $bookss->upload_date;
            $data['uploaded_by'] = $bookss->uploaded_by;
            $data['classes_id'] = $bookss->classes_id;
        $data['schools_id'] = $bookss->schools_id;
        return $data;
        }
    }

        function _sync_with_classes($sync_data) {
        $sql1 = 'update classes set bookss_id = 0 where bookss_id = '.$sync_data['update_id'];
        $this->model->query($sql1);

        $sql2 = 'update classes set bookss_id = :update_id where id = :classes_id';
        $this->model->query_bind($sql2, $sync_data);

        $sql3 = 'update bookss set classes_id = 0 where classes_id = :classes_id and id != :update_id';
        $this->model->query_bind($sql3, $sync_data);
    }

    function _sync_with_schools($sync_data) {
        $sql1 = 'update schools set bookss_id = 0 where bookss_id = '.$sync_data['update_id'];
        $this->model->query($sql1);

        $sql2 = 'update schools set bookss_id = :update_id where id = :schools_id';
        $this->model->query_bind($sql2, $sync_data);

        $sql3 = 'update bookss set schools_id = 0 where schools_id = :schools_id and id != :update_id';
        $this->model->query_bind($sql3, $sync_data);
    }

function _get_data_from_post() {
        $data['book_name'] = $this->input('book_name', true);
        $data['subject'] = $this->input('subject', true);
        $data['writter_name'] = $this->input('writter_name', true);
        $data['id_no'] = $this->input('id_no', true);
        $data['published_date'] = $this->input('published_date', true);
        $data['url_string'] = strtolower(url_title($data['book_name']));
        $data['classes_id'] = $this->input('classes_id', true);
        settype($data['classes_id'], 'int');
        $data['schools_id'] = $this->input('schools_id', true);
        settype($data['schools_id'], 'int');
        return $data;
    }

}