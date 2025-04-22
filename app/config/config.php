<?php

// Set default timezone
date_default_timezone_set('Asia/Colombo');

//Database Configuration
define('DB_HOST', 'mysolardb.cvggoaa6op0w.eu-north-1.rds.amazonaws.com');
define('DB_USER', 'admin');
define('DB_PASSWORD', 'Simpexdb25');
define("DB_NAME", "simpex_db");


// define('DB_HOST', 'mysql-128f1e1e-thisum-feb8.f.aivencloud.com:12959');
// define('DB_USER', 'sadmin1');
// define('DB_PASSWORD', 'AVNS_J_3AliTbKdr_hFlOTXx');
// define("DB_NAME", "simpex_db");

// define('DB_HOST', 'localhost');
// define('DB_USER', 'root');              
// define('DB_PASSWORD', '');               
// define('DB_NAME', 'simpex_db');         




//Addresses
define('APPROOT', dirname(dirname(__FILE__)));
define('URLROOT', 'http://localhost/simpex-solar');
define('SITENAME', 'simpex');


define('SMTP_HOST', 'smtp.gmail.com'); 
define('SMTP_USER', 'solarprojectcs25@gmail.com');
define('SMTP_PASS', 'rvxr gkmt svdp hbtq'); 
define('SITE_NAME', 'simpex solar'); 

define('address', 'Simpex Holding, No 465, Galle Road, Colombo 03');

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

