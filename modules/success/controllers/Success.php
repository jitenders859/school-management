<?php
class Success extends Trongate {

    function index() {

        $session_id = session_id();
        $golden_ticket_obj = $this->model->get_one_where("session_id", session_id(), 'golden_tickets');

        if($golden_ticket_obj !== false) {
            $code = $golden_ticket_obj->code;
            $target_url = BASE_URL.'members/start/'.$code;
            redirect($target_url);
        }

        $data['view_file'] = "success";
        $this->template('public_milligram', $data);
    }
}