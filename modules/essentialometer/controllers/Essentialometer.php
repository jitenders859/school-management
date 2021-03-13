<?php
class Essentialometer extends Trongate {

	function _draw($essentialometer) {
        $data['essentialometer'] = $essentialometer;
		$data['essentialometer_desc'] = $this->_get_essentialometer_desc($essentialometer);
		$this->view('essentialometer', $data);
	}

    function _get_essentialometer_desc($essentialometer) {

        switch ($essentialometer) {
            case $essentialometer <= 20:
                $desc = 'The information discussed in this lesson is nice to know but not essential. Relax!';
                break;
            case $essentialometer <= 25:
                $desc = 'You will rarely need to use the information or technique discussed in the video';
                break;
            case $essentialometer <= 50:
                $desc = 'This lesson contains skills or techniques that you will probably use a few times a month';
                break;
            case $essentialometer <= 75:
                $desc = 'You can reasonably expect to use the information or techniques discussed fairly frequently';
                break;
            case $essentialometer > 75:
                $desc = 'The information contained in this lesson is absolutely essential.  Pay close attention!';
                break;
            default:
                $desc = 'The information contained within this lesson is essential';
                break;
        }

        return $desc;
    }

}