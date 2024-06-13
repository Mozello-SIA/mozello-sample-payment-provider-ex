<?php

require "libs/paymentapi.php";
require 'config.php';

$payments = new PaymentApi($api_key);

if (isset($_GET['OrderNumber']) && $_GET['OrderNumber']) {

    $order = $_GET['OrderNumber'];
    $settings = $payments->getPaymentSettings($order);
    if ($settings && isset($settings['meta']['notify_url']) && $settings['meta']['notify_url']) {

        //TODO: some validation to make sure that this request is legit
        // ... ... ...


        $notify_url = $settings['meta']['notify_url'];
        $payments->sendIPN($order, $notify_url);

        die('OK');

    } else {
        trigger_error('Error with payment settings');
        die('Error with payment settings');
    }
} else {
    trigger_error('invalid payment parameters');
    die('Invalid payment parameters');
}