<?php
require("SpeedSMSAPI.php");

function getUserInfo() {
    $sms = new SpeedSMSAPI();
    $userInfo = $sms->getUserInfo();
    var_dump($userInfo);
}

function sendSMS($phones, $content) {
    $sms = new SpeedSMSAPI();
    $return = $sms->sendSMS($phones, $content, SpeedSMSAPI::SMS_TYPE_CSKH, "", 1);
    var_dump($return);
}

sendSMS(['0969786120'], 'test sms');