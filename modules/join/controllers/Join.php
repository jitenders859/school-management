<?php
class Join extends Trongate {
	
	function index() {
		$data['view_file'] = 'join';
		$this->template('public_milligram', $data);
	}

}