<?php

// Set default timezone
date_default_timezone_set('Asia/Colombo');

// Fill with real credentials.
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');              
define('DB_NAME', 'simpex_db');         

//Addresses
define('APPROOT', dirname(dirname(__FILE__)));
define('URLROOT', 'http://localhost/simpex-solar');
define('SITENAME', 'simpex');

// SMTP Configuration
// Fill with real credentials.
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USER', 'your_email@gmail.com');
define('SMTP_PASS', 'your_smtp_password');
define('SITE_NAME', 'simpex solar');

define('address', '2nd floor,  McLaren\'s Building, No.123, Bauddhaloka Mawatha, Colombo 04');

// Bank Account Details
define('BANK_ACCOUNTS', [
    [
        'bank_name' => 'Bank of Ceylon',
        'account_name' => 'Simpex Holding',
        'account_number' => '1234567890',
        'branch' => 'Matara',
        'branch_code' => '001122'
    ],
    [
        'bank_name' => 'Commercial Bank',
        'account_name' => 'Simpex Holding',
        'account_number' => '0987654321',
        'branch' => 'Colombo',
        'branch_code' => '334455'
    ]
]);
