<?php
class Video_lessons extends Trongate {

    function _get_published_vids() {
        $sql = 'select id, sections_id as section_id from video_lessons where published = 1';
        $rows = $this->model->query($sql, 'object');
        return $rows;
    }

    function _get_watched_vids($token) {
        $this->module('trongate_tokens');
        $token_obj = $this->trongate_tokens->_fetch_token_obj($token);
        $trongate_user_id = $token_obj->user_id;

        //fetch the member record
        $member = $this->model->get_one_where('trongate_user_id', $trongate_user_id, 'members');

        $watched_vids = [];
        if($member !== false) {
            if ($member->videos_watched == '') {
                $watched_vids = [];
            } else {
                $watched_vids = unserialize($member->videos_watched);
            }
        }

        return $watched_vids;
    }

    function _attempt_draw_comments($video_id) {

        $params['target_table'] = 'video_lessons';
        $params['update_id'] = $video_id;
        $sql = 'SELECT
                comments.`comment`,
                comments.date_created,
                members.username
                FROM
                comments
                JOIN trongate_users
                ON comments.user_id = trongate_users.id
                JOIN members
                ON trongate_users.id = members.trongate_user_id
                WHERE comments.target_table = :target_table
                AND comments.update_id = :update_id
                ORDER BY comments.date_created';

        $data['comments'] = $this->model->query_bind($sql, $params, 'object');
        $this->view('video_comments', $data);

    }

    function submit_comment($code) {
        $this->module($code);
        $token  = $this->security->_make_sure_allowed('members area');

        // make sure the code is valid
        $video_obj = $this->model->get_one_where('code', $code);

        if($video_obj == false) {
            redirect('dashboard');
        }

        $submit = $this->input('submit');
        $comment = trim($this->input('comment', true));

        if (($submit == 'Submit Comment') && ($comment != '')) {
            // insert into the comments table
            $this->module('trongate_tokens');
            $user = $this->trongate_tokens->_fetch_token_obj($token);

            $data['comment'] = $comment;
            $data['date_created'] = time();
            $data['user_id'] = $user->user_id;
            $data['target_table'] = 'video_lessons';
            $data['update_id'] = $video_obj->id;
            $data['code'] = make_rand_str(6);

            $this->model->insert($data,'comments');
            set_flashdata('Thank you for posting a comment!');

            redirect(previous_url());

        }
    }

    function _attempt_draw_resources($lesson_id) {

        $params['lesson_id'] = $lesson_id;
        $sql = "SELECT video_resources.* FROM associated_video_lessons_and_video_resources JOIN video_resources ON associated_video_lessons_and_video_resources.video_resources_id = video_resources.id WHERE associated_video_lessons_and_video_resources.video_lessons_id = :lesson_id";
        $data['resources'] = $this->model->query_bind($sql, $params, 'object');
        $num_resources = count($data['resources']);

        if($num_resources > 0) {
            $this->view('video_resources', $data);
        }
    }

    function list_lessons($section_code) {
        $this->module('security');
        $token = $this->security->_make_sure_allowed('members area');

        $section = $this->model->get_one_where('code', $section_code, 'sections');

        if($section == false) {
            redirect('dashboard');
        }

        $data['headline'] = "Module ".$section->priority.': '.$section->title;
        $data['introduction'] = $section->introduction;
        $data['pic_path'] = BASE_URL.'sections_pics/'.$section->id.'/'.$section->picture;

        $params['section_id'] = $section->id;
        $sql = 'select * from video_lessons where published=1 and sections_id=:section_id order by priority';
        $data['video_lessons'] = $this->model->query_bind($sql, $params, 'object');;

        $data['watched_vids'] = $this->_get_watched_vids($token);

        $data['view_file'] = 'list_lessons';
        $this->template('members_area', $data);

    }

    function learn() {
        $this->module('security');
        $token = $this->security->_make_sure_allowed('members area');

        $code = $this->url->segment(3);

        $video_obj = $this->model->get_one_where("code", $code);
        $data = (array) $video_obj;

        //format the code snippets
        $data['code_snippets'] = htmlentities($data['code_snippets']);
        $data['code_snippets'] = str_replace('[code]', '<pre><code>', $data['code_snippets']);
        $data['code_snippets'] = str_replace('[/code]', '</code></pre>', $data['code_snippets']);


        // let's get the section title
        $section = $this->model->get_where($data['sections_id'], 'sections');
        $data['section_title'] = $section->title;
        $data['section_code'] = $section->code;

        $prev_next = $this->_get_prev_next($data);
        $data['prev_url'] = $prev_next['prev'];
        $data['next_url'] = $prev_next['next'];


        $data['token'] = $token;

        $data['view_file'] = 'video_lesson';
        $this->template('members_area', $data);
    }

     function _get_prev_next($data) {
        // get the prev link
        $params['priority'] = $data['priority'];
        $params['sections_id'] = $data['sections_id'];
        $sql1 = 'select * from video_lessons where priority <:priority AND published = 1 AND sections_id = :sections_id ORDER BY priority DESC LIMIT 0, 1';

        $result1 = $this->model->query_bind($sql1, $params, 'object');

        if($result1 == false) {
            // no prev video found so, link back to the sections home area
            $prev = BASE_URL.'video_lessons/list_lessons/'.$data['section_code'];
        } else {
            // get the id of the video
            $target_video_code = $result1[0]->code;
            $prev = BASE_URL.'video_lessons/learn/'.$target_video_code;
        }

        // get the next link
        $sql2 = str_replace('priority <:priority', 'priority >:priority', $sql1);
        $sql2 = str_replace('priority DESC', 'priority ASC', $sql2);


        $result2 = $this->model->query_bind($sql2, $params, 'object');

        if($result2 == false) {
            // no next video found so, link back to the sections home area
            $next = BASE_URL.'video_lessons/list_lessons/'.$data['section_code'];
        } else {
            // get the id of the video
            $target_video_code = $result2[0]->code;
            $next = BASE_URL.'video_lessons/learn/'.$target_video_code;
        }

        $prev_next_links['prev'] = $prev;
        $prev_next_links['next'] = $next;

        return $prev_next_links;
        }

    function manage() {
        $this->module('security');
        $data['token'] = $this->security->_make_sure_allowed();
        $data['order_by'] = 'priority';

        //format the pagination
        $data['total_rows'] = $this->model->count('video_lessons');
        $data['record_name_plural'] = 'video lessons';

        $data['headline'] = 'Manage Video Lessons';
        $data['view_module'] = 'video_lessons';
        $data['view_file'] = 'manage';

        $this->template('admin', $data);
    }

    function show() {
        $this->module('security');
        $token = $this->security->_make_sure_allowed();

        $update_id = $this->url->segment(3);

        if ((!is_numeric($update_id)) && ($update_id != '')) {
            redirect('video_lessons/manage');
        }

        $data = $this->_get_data_from_db($update_id);
        $data['token'] = $token;

        if ($data == false) {
            redirect('video_lessons/manage');
        } else {
            $data['form_location'] = BASE_URL.'video_lessons/submit/'.$update_id;
            $data['update_id'] = $update_id;
            $data['headline'] = 'Video Lesson Information';
            $data['published'] = $this->_boolean_to_words($data['published']);
            $data['view_file'] = 'show';
            $this->template('admin', $data);
        }
    }

    function _get_sections_options($selected_key) {

        if ($selected_key == '') {
            $options[''] = 'Select...';
        }

        $sql = "select * from sections order by title";
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

    function _get_schools_options($selected_key) {

        if ($selected_key == '') {
            $options[''] = 'Select...';
        }

        $sql = '
                SELECT
                    video_lessons.id as assigned_to_description,
                    schools.id,
                    schools.school_name as identifier_column
                FROM
                    schools                
                LEFT JOIN video_lessons ON schools.id = video_lessons.schools_id 
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

    function _get_classes_options($selected_key) {

        if ($selected_key == '') {
            $options[''] = 'Select...';
        }

        $sql = '
                SELECT
                    video_lessons.title as assigned_to_description,
                    classes.id,
                    classes.class_title as identifier_column
                FROM
                    classes                
                LEFT JOIN video_lessons ON classes.id = video_lessons.classes_id 
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

    function _get_subjects_options($selected_key) {

        if ($selected_key == '') {
            $options[''] = 'Select...';
        }

        $sql = '
                SELECT
                    video_lessons.title as assigned_to_description,
                    subjects.id,
                    subjects.subject_name as identifier_column
                FROM
                    subjects                
                LEFT JOIN video_lessons ON subjects.id = video_lessons.subjects_id 
                ORDER BY subjects.subject_name
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
            redirect('video_lessons/manage');
        }

        //fetch the form data
        if (($submit == '') && ($update_id > 0)) {
            $data = $this->_get_data_from_db($update_id);
        } else {
            $data = $this->_get_data_from_post();
        }

        if ($data['sections_id'] == 0) {
            $data['sections_id'] = '';
        }

        $data['sections_options'] = $this->_get_sections_options($data['sections_id']);
        if ($data['schools_id'] == 0) {
            $data['schools_id'] = '';
        }

        $data['schools_options'] = $this->_get_schools_options($data['schools_id']);
        if ($data['classes_id'] == 0) {
            $data['classes_id'] = '';
        }

        $data['classes_options'] = $this->_get_classes_options($data['classes_id']);
        if ($data['subjects_id'] == 0) {
            $data['subjects_id'] = '';
        }

        $data['subjects_options'] = $this->_get_subjects_options($data['subjects_id']);
        $data['headline'] = $this->_get_page_headline($update_id);

        if ($update_id > 0) {
            $data['cancel_url'] = BASE_URL.'video_lessons/show/'.$update_id;
            $data['btn_text'] = 'UPDATE VIDEO LESSON DETAILS';
        } else {
            $data['cancel_url'] = BASE_URL.'video_lessons/manage';
            $data['btn_text'] = 'CREATE VIDEO LESSON RECORD';
        }

        $additional_includes_top[] = 'https://code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css';
        $additional_includes_top[] = 'https://trentrichardson.com/examples/timepicker/jquery-ui-timepicker-addon.css';
        $additional_includes_top[] = 'https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"';
        $additional_includes_top[] = 'https://trentrichardson.com/examples/timepicker/jquery-ui-timepicker-addon.js';
        $additional_includes_top[] = BASE_URL.'admin_files/js/i18n/jquery-ui-timepicker-addon-i18n.min.js';
        $additional_includes_top[] = BASE_URL.'admin_files/js/jquery-ui-sliderAccess.js';
        $data['additional_includes_top'] = $additional_includes_top;

        $data['form_location'] = BASE_URL.'video_lessons/submit/'.$update_id;
        $data['update_id'] = $update_id;
        $data['view_file'] = 'create';
        $this->template('admin', $data);
    }

    function _get_page_headline($update_id) {
        //figure out what the page headline should be (on the video_lessons/create page)
        if (!is_numeric($update_id)) {
            $headline = 'Create New Video Lesson Record';
        } else {
            $headline = 'Update Video Lesson Details';
        }

        return $headline;
    }

    function submit() {
        $this->module('security');
        $this->security->_make_sure_allowed();

        $submit = $this->input('submit', true);

        if ($submit == 'Submit') {

            $this->validation_helper->set_rules('title', 'Title', 'required|min_length[2]|max_length[255]');
            $this->validation_helper->set_rules('video_path', 'Video Path', 'required|min_length[2]');
            $this->validation_helper->set_rules('video_teaser', 'Video Teaser', 'required|min_length[2]');
            $this->validation_helper->set_rules('synopsis', 'Synopsis', 'required|min_length[2]');
            $this->validation_helper->set_rules('essentialometer', 'Essentialometer', 'required|max_length[11]|numeric|greater_than[0]|integer');
            $this->validation_helper->set_rules('priority', 'Priority', 'required|max_length[11]|numeric|greater_than[0]|integer');
            $this->validation_helper->set_rules('code_snippets', 'Code Snippets', 'required|min_length[2]');

            $result = $this->validation_helper->run();

            if ($result == true) {

                $update_id = $this->url->segment(3);
                $data = $this->_get_data_from_post();
                settype($data['published'], 'int');
                $data['code'] = make_rand_str(16);
                if (is_numeric($update_id)) {
                    //update an existing record

                    $this->model->update($update_id, $data, 'video_lessons');
                    $flash_msg = 'The record was successfully updated';
                } else {
                    //insert the new record
                    $update_id = $this->model->insert($data, 'video_lessons');
                    $flash_msg = 'The record was successfully created';
                }

                        $sync_subjects_data['update_id'] = $update_id;
                        $sync_subjects_data['subjects_id'] = $data['subjects_id'];
                        $this->_sync_with_subjects($sync_subjects_data);

                


                        $sync_classes_data['update_id'] = $update_id;
                        $sync_classes_data['classes_id'] = $data['classes_id'];
                        $this->_sync_with_classes($sync_classes_data);

                


                        $sync_schools_data['update_id'] = $update_id;
                        $sync_schools_data['schools_id'] = $data['schools_id'];
                        $this->_sync_with_schools($sync_schools_data);

                


                set_flashdata($flash_msg);
                redirect('video_lessons/show/'.$update_id);

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

                                //remove any existing association with subjects module
                                $sql = 'update subjects set video_lessons_id = 0 where video_lessons_id = '.$update_id;
                                $this->model->query($sql);

                            

                                //remove any existing association with classes module
                                $sql = 'update classes set video_lessons_id = 0 where video_lessons_id = '.$update_id;
                                $this->model->query($sql);

                            

                                //remove any existing association with schools module
                                $sql = 'update schools set video_lessons_id = 0 where video_lessons_id = '.$update_id;
                                $this->model->query($sql);

                            

                //set the flashdata
                $flash_msg = 'The record was successfully deleted';
                set_flashdata($flash_msg);

                //redirect to the manage page
                redirect('video_lessons/manage');
            }
        }
    }

    function _get_data_from_db($update_id) {
        $video_lessons = $this->model->get_where($update_id, 'video_lessons');

        if ($video_lessons == false) {
            $this->template('error_404');
            die();
        } else {
            $data['title'] = $video_lessons->title;
            $data['video_path'] = $video_lessons->video_path;
            $data['video_teaser'] = $video_lessons->video_teaser;
            $data['synopsis'] = $video_lessons->synopsis;
            $data['essentialometer'] = $video_lessons->essentialometer;
            $data['priority'] = $video_lessons->priority;
            $data['code_snippets'] = $video_lessons->code_snippets;
            $data['code'] = $video_lessons->code;
            $data['published'] = $video_lessons->published;
            $data['sections_id'] = $video_lessons->sections_id;
            $data['schools_id'] = $video_lessons->schools_id;
        $data['classes_id'] = $video_lessons->classes_id;
        $data['subjects_id'] = $video_lessons->subjects_id;
        return $data;
        }
    }

    function _get_data_from_post() {
        $data['title'] = $this->input('title', true);
        $data['video_path'] = $this->input('video_path', true);
        $data['video_teaser'] = $this->input('video_teaser', true);
        $data['synopsis'] = $this->input('synopsis', true);
        $data['essentialometer'] = $this->input('essentialometer', true);
        $data['priority'] = $this->input('priority', true);
        $data['code_snippets'] = $this->input('code_snippets', true);
        $data['published'] = $this->input('published', true);
        $data['sections_id'] = $this->input('sections_id', true);
        $data['schools_id'] = $this->input('schools_id', true);
        settype($data['schools_id'], 'int');
        $data['classes_id'] = $this->input('classes_id', true);
        settype($data['classes_id'], 'int');
        $data['subjects_id'] = $this->input('subjects_id', true);
        settype($data['subjects_id'], 'int');
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

        function _sync_with_schools($sync_data) {
        $sql1 = 'update schools set video_lessons_id = 0 where video_lessons_id = '.$sync_data['update_id'];
        $this->model->query($sql1);

        $sql2 = 'update schools set video_lessons_id = :update_id where id = :schools_id';
        $this->model->query_bind($sql2, $sync_data);

        $sql3 = 'update video_lessons set schools_id = 0 where schools_id = :schools_id and id != :update_id';
        $this->model->query_bind($sql3, $sync_data);
    }

    function _sync_with_classes($sync_data) {
        $sql1 = 'update classes set video_lessons_id = 0 where video_lessons_id = '.$sync_data['update_id'];
        $this->model->query($sql1);

        $sql2 = 'update classes set video_lessons_id = :update_id where id = :classes_id';
        $this->model->query_bind($sql2, $sync_data);

        $sql3 = 'update video_lessons set classes_id = 0 where classes_id = :classes_id and id != :update_id';
        $this->model->query_bind($sql3, $sync_data);
    }

    function _sync_with_subjects($sync_data) {
        $sql1 = 'update subjects set video_lessons_id = 0 where video_lessons_id = '.$sync_data['update_id'];
        $this->model->query($sql1);

        $sql2 = 'update subjects set video_lessons_id = :update_id where id = :subjects_id';
        $this->model->query_bind($sql2, $sync_data);

        $sql3 = 'update video_lessons set subjects_id = 0 where subjects_id = :subjects_id and id != :update_id';
        $this->model->query_bind($sql3, $sync_data);
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