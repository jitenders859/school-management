<?php
class Paypal extends Trongate {

	function ipn_listener() {

		$res = $this->_is_order_valid(); //ask Paypal to validate the order

		if ($res !== 'VERIFIED') {
			die(); //order not valid (according to Paypal)
		}

		header('HTTP/1.1 200 OK'); //let Paypal know that we have received the IPN
		$posted_data = file_get_contents('php://input');
		$params = json_decode($posted_data, true);

		if ($params == false) {
			$params = $_POST;
		}

		$data['date_created'] = time();
		$data['posted_information'] = serialize($params);
		$this->model->insert($data, 'paypal');

        $customer_name = $params['first_name'].' '.$params['last_name'];
        $email = $params['payer_email'];

        if(isset($params['payment_status'])) {
            if($params['payment_status'] == 'Completed') {
                // new purchase!
                $this->module('encryption');
                $this->module('golden_tickets');

                // get the session_id of the person who made the purchase

                $member_session_id = $this->encryption->_decrypt($params['custom']);
                $this->golden_tickets->_init_join($customer_name, $email, $member_session_id);
            }
        }

	}

    function _is_order_valid() {

        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost = array();
        foreach ($raw_post_array as $keyval) {
            $keyval = explode('=', $keyval);
            if (count($keyval) == 2) {
                // Since we do not want the plus in the datetime string to be encoded to a space, we manually encode it.
                if ($keyval[0] === 'payment_date') {
                    if (substr_count($keyval[1], '+') === 1) {
                        $keyval[1] = str_replace('+', '%2B', $keyval[1]);
                    }
                }
                $myPost[$keyval[0]] = urldecode($keyval[1]);
            }
        }

        // Build the body of the verification post request, adding the _notify-validate command.
        $req = 'cmd=_notify-validate';
        $get_magic_quotes_exists = false;
        if (function_exists('get_magic_quotes_gpc')) {
            $get_magic_quotes_exists = true;
        }
        foreach ($myPost as $key => $value) {
            if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
                $value = urlencode(stripslashes($value));
            } else {
                $value = urlencode($value);
            }
            $req .= "&$key=$value";
        }

        // Post the data back to PayPal, using curl. Throw exceptions if errors occur.
        $ch = curl_init('https://ipnpb.paypal.com/cgi-bin/webscr');
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSLVERSION, 6);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'User-Agent: PHP-IPN-Verification-Script',
            'Connection: Close',
        ));
        $res = curl_exec($ch);
        if ( ! ($res)) {
            $errno = curl_errno($ch);
            $errstr = curl_error($ch);
            curl_close($ch);
            throw new Exception("cURL error: [$errno] $errstr");
        }

        $info = curl_getinfo($ch);
        $http_code = $info['http_code'];
        if ($http_code != 200) {
            throw new Exception("PayPal responded with http code $http_code");
        }

        curl_close($ch);

        // Check if PayPal verifies the IPN data, and if so, return true.
        return $res;
    }

}