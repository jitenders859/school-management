<?php
class Video_resources extends Trongate {

    function manage() {
        $this->module('security');
        $data['token'] = $this->security->_make_sure_allowed();
        $data['order_by'] = 'button_title';

        //format the pagination
        $data['total_rows'] = $this->model->count('video_resources');
        $data['record_name_plural'] = 'video resources';

        $data['headline'] = 'Manage Video Resources';
        $data['view_module'] = 'video_resources';
        $data['view_file'] = 'manage';

        $this->template('admin', $data);
    }

    function show() {
        $this->module('security');
        $token = $this->security->_make_sure_allowed();

        $update_id = $this->url->segment(3);

        if ((!is_numeric($update_id)) && ($update_id != '')) {
            redirect('video_resources/manage');
        }

        $data = $this->_get_data_from_db($update_id);
        $data['token'] = $token;

        if ($data == false) {
            redirect('video_resources/manage');
        } else {
            $data['form_location'] = BASE_URL.'video_resources/submit/'.$update_id;
            $data['update_id'] = $update_id;
            $data['headline'] = 'Video Resource Information';
            $data['downloadable'] = $this->_boolean_to_words($data['downloadable']);
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
            redirect('video_resources/manage');
        }

        //fetch the form data
        if (($submit == '') && ($update_id > 0)) {
            $data = $this->_get_data_from_db($update_id);
        } else {
            $data = $this->_get_data_from_post();
        }

        $data['headline'] = $this->_get_page_headline($update_id);

        if ($update_id > 0) {
            $data['cancel_url'] = BASE_URL.'video_resources/show/'.$update_id;
            $data['btn_text'] = 'UPDATE VIDEO RESOURCE DETAILS';
        } else {
            $data['cancel_url'] = BASE_URL.'video_resources/manage';
            $data['btn_text'] = 'CREATE VIDEO RESOURCE RECORD';
        }

        $additional_includes_top[] = 'https://code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css';
        $additional_includes_top[] = 'https://trentrichardson.com/examples/timepicker/jquery-ui-timepicker-addon.css';
        $additional_includes_top[] = 'https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"';
        $additional_includes_top[] = 'https://trentrichardson.com/examples/timepicker/jquery-ui-timepicker-addon.js';
        $additional_includes_top[] = BASE_URL.'admin_files/js/i18n/jquery-ui-timepicker-addon-i18n.min.js';
        $additional_includes_top[] = BASE_URL.'admin_files/js/jquery-ui-sliderAccess.js';
        $data['additional_includes_top'] = $additional_includes_top;

        $data['form_location'] = BASE_URL.'video_resources/submit/'.$update_id;
        $data['update_id'] = $update_id;
        $data['view_file'] = 'create';
        $this->template('admin', $data);
    }

    function _get_page_headline($update_id) {
        //figure out what the page headline should be (on the video_resources/create page)
        if (!is_numeric($update_id)) {
            $headline = 'Create New Video Resource Record';
        } else {
            $headline = 'Update Video Resource Details';
        }

        return $headline;
    }

    function submit() {
        $this->module('security');
        $this->security->_make_sure_allowed();

        $submit = $this->input('submit', true);

        if ($submit == 'Submit') {

            $this->validation_helper->set_rules('button_title', 'Button Title', 'required|min_length[2]|max_length[255]');
            $this->validation_helper->set_rules('target_url', 'Target URL', 'required|min_length[2]|max_length[255]');

            $result = $this->validation_helper->run();

            if ($result == true) {

                $update_id = $this->url->segment(3);
                $data = $this->_get_data_from_post();
                settype($data['downloadable'], 'int');
                if (is_numeric($update_id)) {
                    //update an existing record
                    $this->model->update($update_id, $data, 'video_resources');
                    $flash_msg = 'The record was successfully updated';
                } else {
                    //insert the new record
                    $update_id = $this->model->insert($data, 'video_resources');
                    $flash_msg = 'The record was successfully created';
                }

                set_flashdata($flash_msg);
                redirect('video_resources/show/'.$update_id);

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
                redirect('video_resources/manage');
            }
        }
    }

    function _get_data_from_db($update_id) {
        $video_resources = $this->model->get_where($update_id, 'video_resources');

        if ($video_resources == false) {
            $this->template('error_404');
            die();
        } else {
            $data['button_title'] = $video_resources->button_title;
            $data['target_url'] = $video_resources->target_url;
            $data['downloadable'] = $video_resources->downloadable;
            return $data;
        }
    }

    function _get_data_from_post() {
        $data['button_title'] = $this->input('button_title', true);
        $data['target_url'] = $this->input('target_url', true);
        $data['downloadable'] = $this->input('downloadable', true);
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
            $output['body'][$key] ->downloadable = $this->_boolean_to_words($value->downloadable);
        }

        $output['body'] = json_encode($output['body']);

        return $output;
    }

}