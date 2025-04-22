<?php require APPROOT.'/views/client/header.php';?>

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/errors/404.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>

</head>
<body>
    

    <!-- <php require APPROOT.'/views/inc/components/topnavbar.php'; ?> -->



    <div class="stars"></div>

    <div class="content-wrapper">
        <div class="logo-container">
            <img src="<?php echo URLROOT; ?>/assets/simpex-logo.png" alt="SimplEx Solar" class="logo">
        </div>
        
        <div class="error-content">
            <h1 class="error-code">404</h1>
            <h2 class="error-message">Oops! The page you are looking for does not exist.</h2>
            <p class="error-description">Looks like our solar panels couldn't generate this page</p>
            <div class="error-actions">
                <a href="<?php echo URLROOT; ?>" class="btn btn-primary">Return Home</a>
                <a href="javascript:history.back()" class="btn btn-secondary">Go Back</a>
            </div>
        </div>
    </div>
    
    <div class="celestial-objects">
        <div class="sun"></div>
    </div>
    
    <script>

        const URLROOT = '<?php echo URLROOT; ?>';

    </script>
    <script src="<?php echo URLROOT; ?>/js/errors/404.js"></script>
<?php require APPROOT.'/views/client/footer.php';?>