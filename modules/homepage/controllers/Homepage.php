<?php
class Homepage extends Trongate {
 
    function index() {
        $data['view_module'] = 'homepage';
        $data['view_file'] = 'homepage_content';
        $this->template('public_milligram', $data);
    }

    

}