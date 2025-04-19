<?php

/*
# New PHP Unserialize_CWE_502 CTF Challenge
# Based on original author structure by h1xa
*/

error_reporting(0);
highlight_file(__FILE__);
include('flag.php');

class ctfUnserializeChallenge {
    public $isVip = false;
    public $payload = '';

    public function __wakeup() {
        if ($this->isVip === true && $this->payload === 'get_flag') {
            global $flag;
            echo "Welcome VIP! Here is your flag: " . $flag;
        } else {
            echo "Access denied!";
        }
    }
}

if (isset($_GET['data'])) {
    @unserialize($_GET['data']);
} else {
    showHint();
}

function showHint() {
    echo "<br>Hint: You must be a VIP and trigger the right condition to get the flag.<br>";
    echo "Try submitting a serialized object via ?data= parameter.";
}
