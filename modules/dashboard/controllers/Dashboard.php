<?php
class Dashboard extends Trongate {
    // function index() {

    //     $this->module('security');
    //     $token = $this->security->_make_sure_allowed('school');

    //     $this->module('video_lessons');

    //     // get an array of all the videos that this person has watched
    //     $data['watched_vids'] = $this->video_lessons->_get_watched_vids($token);
    //     $data['published_vids'] = $this->video_lessons->_get_published_vids($token);

    //     $sql = "select * from sections where published=1 and section_types_id=1 order by priority";
    //     $data['training_modules'] = $this->model->query($sql, "object");

    //     $sql = "select * from sections where published=1 and section_types_id=2 order by priority";
    //     $data['projects'] = $this->model->query($sql, "object");

    //     $data['view_file'] = 'dashboard';
    //     $this->template('members_area', $data);
    // }


        function index() {
            $this->school_dashboard();
        }

    function school_dashboard() {
        $this->module('security');
        $this->module('trongate_tokens');
        $token = $this->security->_make_sure_allowed('school');
        $data['school_id'] = $this->trongate_tokens->_attempt_get_school_id($token);
        $data['view_module'] = "dashboard";
        $data['view_file'] = "principal-dashboard";


        $this->template('principal_admin_panel', $data);
    }

    function teacher_dashboard() {
        $this->module('security');
        $token = $this->security->_make_sure_allowed('teacher');
    }

    function principal_dashboard() {
        $this->module('security');
        $token = $this->security->_make_sure_allowed('principal');
    }

    function student_dashboard() {
        $this->module('security');
        $token = $this->security->_make_sure_allowed('student');
    }

    function parent_dashboard() {
        $this->module('security');
        $token = $this->security->_make_sure_allowed('parent');
    }
}
