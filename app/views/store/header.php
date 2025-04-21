<!-- app/views/store/header.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($data['title']) ? $data['title'] : 'Solar Store'; ?> - SimplEx Solar</title>

    <!-- Common CSS -->
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/style.css">

    <!-- Store CSS -->
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/store/base.css">