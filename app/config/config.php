<?php


//Database Configuration
define('DB_HOST', 'database-1.crme2mkgqnhq.eu-north-1.rds.amazonaws.com');
define('DB_USER', 'admin');
define('DB_PASSWORD', 'mahindA69');
define("DB_NAME", "simpex_db");



//Addresses
define('APPROOT', dirname(dirname(__FILE__)));
define('URLROOT', 'http://localhost/simpex-solar');
define('SITENAME', 'simpex ');


define('SMTP_HOST', 'smtp.gmail.com'); 
define('SMTP_USER', 'pasansanjiiwa2023@gmail.com');
define('SMTP_PASS', 'ygkj tcso qcde bzgd'); 
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

