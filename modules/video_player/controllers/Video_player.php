<?php
class Video_player extends Trongate {

	function _draw($video_path, $video_id=NULL, $token=NULL) {

		if((isset($video_id)) && (isset($token))) {
			$data['video_id'] = $video_id;
			$data['token'] = $token;
		}
		$data['video_path'] = $video_path;
		$this->view('video_player', $data);
	}

}