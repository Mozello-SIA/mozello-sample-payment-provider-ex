<?php

class PaymentApi {

    private $api_prefix = 'https://api.mozello.com/v1/';
    private $api_key;
    public $last_api_response;

    public function __construct($api_key) {
        $this->api_key = $api_key;
    }

    public function getPaymentSettings($order_uuid) {
        $r = $this->_curlRequest($this->api_prefix . 'payment/payment_settings/?order_uuid=' . $order_uuid . '&api_key=' . $this->api_key, 'GET', '');
        if ($r['status'] == 200) {
            return json_decode($r['response'], true);
        } else {
            return false;
        }
    }

    public function sendIPN($order_uuid, $notify_url, $approved = true, $check_pending = false) {
        $data = array(
            'order_uuid' => $order_uuid,
            'status' => $approved ? 'approved' : 'failed'
        );
        if ($check_pending) {
            $data['check_pending'] = 1;
        }
        // generate data string
        $message = '';
        foreach ($data as $key => $value) {
            if ($key != 'signature') {
                $message = $message . $value;
            }
        }
        // calculate signature hash and add it to POST data
        $data['signature'] = base64_encode(hash_hmac('sha256', $message, $this->api_key, true));

        $r = $this->_curlRequest($notify_url, 'POST', $data);
        return $r;
    }

    public function validatePostSignature() {
        // generate data string
        $message = '';
        foreach ($_POST as $key => $value) {
            if ($key != "signature") {
                $message = $message . $value;
            }
        }
        // calculate signature hash
        $signature = base64_encode(hash_hmac('sha256', $message, $this->api_key, true));
        // check if hash matches
        if ($signature == $_POST['signature']) {
            return true;
        } else {
            return false;
        }
    }

    private function _curlRequest($url, $method, $payload) {
        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_TIMEOUT        => 10
        ]);

        $lastRawResponse = curl_exec($ch);
        $lastRawResponseInfo = curl_getinfo($ch);
        $http_status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);

        $this->last_api_response = [ 'status' => $http_status, 'response' => $lastRawResponse ];
        return $this->last_api_response;
    }
}