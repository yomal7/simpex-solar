<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/style.css">


    <title>about</title>
</head>

<body>
    <h1>Users</h1>
    <?php foreach ($data['users'] as $user) : ?>
        <p><?php echo $user->name; ?><?php echo $user->age;?></p>
    <?php endforeach; ?>    
</body>
</html>