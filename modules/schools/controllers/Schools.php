<?php
class Schools extends Trongate {

    function _init_picture_settings() {
        $picture_settings['targetModule'] = 'schools';
        $picture_settings['maxFileSize'] = 2000;
        $picture_settings['maxWidth'] = 1200;
        $picture_settings['maxHeight'] = 1200;
        $picture_settings['resizedMaxWidth'] = 450;
        $picture_settings['resizedMaxHeight'] = 450;
        $picture_settings['destination'] = 'schools_pics';
        $picture_settings['targetColumnName'] = 'picture';
        $picture_settings['thumbnailDir'] = 'schools_pics_thumbnails';
        $picture_settings['thumbnailMaxWidth'] = 120;
        $picture_settings['thumbnailMaxHeight'] = 120;
        return $picture_settings;
    }

    function manage() {
        $this->module('security');
        $data['token'] = $this->security->_make_sure_allowed();
        $data['order_by'] = 'id desc';

        //format the pagination
        $data['total_rows'] = $this->model->count('schools');
        $data['record_name_plural'] = 'schools';

        $data['headline'] = 'Manage Schools';
        $data['view_module'] = 'schools';
        $data['view_file'] = 'manage';

        $this->template('admin', $data);
    }

    function show() {
        $this->module('security');
        $token = $this->security->_make_sure_allowed();

        $update_id = $this->url->segment(3);

        if ((!is_numeric($update_id)) && ($update_id != '')) {
            redirect('schools/manage');
        }

        $data = $this->_get_data_from_db($update_id);
        $data['token'] = $token;

        if ($data == false) {
            redirect('schools/manage');
        } else {
            $data['form_location'] = BASE_URL.'schools/submit/'.$update_id;
            $data['update_id'] = $update_id;
            $data['headline'] = 'School Information';
            $data['created_date'] = $this->_datetime_to_words($data['created_date']);
            $data['view_file'] = 'show';
            $this->template('admin', $data);
        }
    }

    function school_create() {

        //fetch the form data
        $data = $this->_get_data_from_post();

        $data['headline'] = "Create New School";

        $data['btn_text'] = 'CREATE SCHOOL';

        $data['form_location'] = BASE_URL.'schools/submit/';
        $data['view_file'] = 'create_school';
        $this->template('public_milligram', $data);
    }

    function create() {
        $this->module('security');
        $this->security->_make_sure_allowed();

        $update_id = $this->url->segment(3);
        $submit = $this->input('submit', true);

        if ((!is_numeric($update_id)) && ($update_id != '')) {
            redirect('schools/manage');
        }

        //fetch the form data
        if (($submit == '') && ($update_id > 0)) {
            $data = $this->_get_data_from_db($update_id);
        } else {
            $data = $this->_get_data_from_post();
        }

        $data['headline'] = $this->_get_page_headline($update_id);

        if ($update_id > 0) {
            $data['cancel_url'] = BASE_URL.'schools/show/'.$update_id;
            $data['btn_text'] = 'UPDATE SCHOOL DETAILS';
        } else {
            $data['cancel_url'] = BASE_URL.'schools/manage';
            $data['btn_text'] = 'CREATE SCHOOL RECORD';
        }

        $additional_includes_top[] = 'https://code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css';
        $additional_includes_top[] = 'https://trentrichardson.com/examples/timepicker/jquery-ui-timepicker-addon.css';
        $additional_includes_top[] = 'https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"';
        $additional_includes_top[] = 'https://trentrichardson.com/examples/timepicker/jquery-ui-timepicker-addon.js';
        $additional_includes_top[] = BASE_URL.'admin_files/js/i18n/jquery-ui-timepicker-addon-i18n.min.js';
        $additional_includes_top[] = BASE_URL.'admin_files/js/jquery-ui-sliderAccess.js';
        $data['additional_includes_top'] = $additional_includes_top;

        $data['form_location'] = BASE_URL.'schools/submit/'.$update_id;
        $data['update_id'] = $update_id;
        $data['view_file'] = 'create';
        $this->template('admin', $data);
    }

    function _get_page_headline($update_id) {
        //figure out what the page headline should be (on the schools/create page)
        if (!is_numeric($update_id)) {
            $headline = 'Create New School Record';
        } else {
            $headline = 'Update School Details';
        }

        return $headline;
    }

    function submit() {

        if(previous_url() != BASE_URL."schools/school_create") {
            $this->module('security');
            $this->security->_make_sure_allowed();
        }

        $submit = $this->input('submit', true);






        if ($submit == 'Submit') {

            $this->validation_helper->set_rules('school_name', 'School Name', 'required|min_length[7]|max_length[255]');
            $this->validation_helper->set_rules('mobile_number', 'Mobile Number', 'required|max_length[20]');
            $this->validation_helper->set_rules('telephone_number', 'Telephone Number', 'required|max_length[20]');
            $this->validation_helper->set_rules('your_email_address', 'Email Address', 'required|min_length[7]|max_length[255]|valid_email');
            $this->validation_helper->set_rules('address_line_1', 'Address Line 1', 'required|max_length[255]');
            $this->validation_helper->set_rules('city', 'City', 'required|min_length[2]|max_length[85]');
            $this->validation_helper->set_rules('state__province__region', 'State / Province / Region', 'required|min_length[2]|max_length[125]');
            $this->validation_helper->set_rules('zip__postal_code', 'Zip / Postal Code', 'required|min_length[2]|max_length[16]');
            $this->validation_helper->set_rules('country', 'Country', 'required|min_length[4]|max_length[56]');

            $result = $this->validation_helper->run();

            $data = $this->_get_data_from_post();


            if ($result == true) {

                $this->module('golden_tickets');
                $update_id = $this->url->segment(3);
                if (is_numeric($update_id)) {
                    //update an existing record
                    $this->model->update($update_id, $data, 'schools');
                    $flash_msg = 'The record was successfully updated';

                } else {
                    //insert the new record
                    $update_id = $this->model->insert($data, 'schools');
                    $flash_msg = 'The record was successfully created';

                    $code = $this->golden_tickets->_create_manual_ticket($update_id, 8, $data['your_email_address']);
                }
                if(previous_url() != BASE_URL."schools/school_create" ) {
                    set_flashdata($flash_msg);
                    redirect('schools/show/'.$update_id);
                } else {


                    $flash_msg = 'Your School successfully Created. Please Create Account.';
                    set_flashdata($flash_msg);

                    redirect('members/start/'.$code);
                }

            } else {
                //form submission error

                if(previous_url() != BASE_URL."schools/school_create" ) {
                    $this->school_create();
                } else {
                    $this->create();
                }
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
                redirect('schools/manage');
            }
        }
    }

    function _get_data_from_db($update_id) {
        $schools = $this->model->get_where($update_id, 'schools');

        if ($schools == false) {
            $this->template('error_404');
            die();
        } else {
            $data['school_name'] = $schools->school_name;
            $data['mobile_number'] = $schools->mobile_number;
            $data['telephone_number'] = $schools->telephone_number;
            $data['code'] = $schools->code;
            $data['your_email_address'] = $schools->your_email_address;
            $data['created_date'] = $schools->created_date;
            $data['address_line_1'] = $schools->address_line_1;
            $data['city'] = $schools->city;
            $data['state__province__region'] = $schools->state__province__region;
            $data['zip__postal_code'] = $schools->zip__postal_code;
            $data['country'] = $schools->country;
            return $data;
        }
    }

    function _get_data_from_post() {
        $data['school_name'] = $this->input('school_name', true);
        $data['mobile_number'] = $this->input('mobile_number', true);
        $data['telephone_number'] = $this->input('telephone_number', true);
        $data['your_email_address'] = $this->input('your_email_address', true);
        $data['address_line_1'] = $this->input('address_line_1', true);
        $data['city'] = $this->input('city', true);
        $data['state__province__region'] = $this->input('state__province__region', true);
        $data['zip__postal_code'] = $this->input('zip__postal_code', true);
        $data['country'] = $this->input('country', true);
        return $data;
    }

    function _datetime_to_words($date) {
        $date = date('l, F jS Y \a\t g:i:s A', strtotime($date));
        return $date;
    }

    function _prep_output($output) {
        $output['body'] = json_decode($output['body']);
        foreach($output['body'] as $key => $value) {
            $output['body'][$key] ->created_date = $this->_datetime_to_words($value->created_date);
        }

        $output['body'] = json_encode($output['body']);

        return $output;
    }

}