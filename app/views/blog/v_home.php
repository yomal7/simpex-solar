<?php require APPROOT.'/views/blog/header.php';?>
        <title>LifeBlog | Home </title>
</head>
<body>
        <!-- container - wraps whole page -->
        <div class="container">
                <!-- navbar -->
                <?php require APPROOT.'/views/blog/navbar.php'; ?>
                <!-- // navbar -->
                <div class="banner">
                        <div class="welcome_msg">
                                <h1>Today's Inspiration</h1>
                                <p> 
                                    One day your life <br> 
                                    will flash before your eyes. <br> 
                                    Make sure it's worth watching. <br>
                                        <span>~ Gerard Way</span>
                                </p>
                                <a href="register.php" class="btn">Join us!</a>
                        </div>
                        <div class="login_div">
                                <form action="index.php" method="post" >
                                        <h2>Login</h2>
                                        <input type="text" name="username" placeholder="Username">
                                        <input type="password" name="password"  placeholder="Password"> 
                                        <button class="btn" type="submit" name="login_btn">Sign in</button>
                                </form>
                        </div>
                </div>
                <!-- Page content -->
                <div class="content">
                        <h2 class="content-title">Recent Articles</h2>
                        <h2 class="content-title">
                        <!-- Articles on <u><?php echo $data['topicName']; ?></u> -->
                        </h2>
                        <hr>  
                        
                        <?php foreach ($data['posts'] as $post): ?>
                                <div class="post" style="margin-left: 0px;">
                                        <img src="<?php echo URLROOT; ?>/public/assets/blog/<?php echo $post->image; ?>" class="post_image" alt="">
                                        <?php if (isset($post->topic->name)): ?>
                                        <a 
                                                href="<?php echo URLROOT . '/blog/filteredPosts/' . $post->topic->id ?>"
                                                class="btn category">
                                                <?php echo $post->topic->name; ?>
                                        </a>
                                        <?php endif; ?>

                                        
                                        <a href="<?php echo URLROOT; ?>/blog/singlePost/<?php echo $post->slug; ?>">
                                        <div class="post_info">
                                                <h3><?php echo $post->title; ?></h3>
                                                <div class="info">
                                                <span><?php echo date("F j, Y", strtotime($post->created_at)); ?></span>
                                                <span class="read_more">Read more...</span>
                                                </div>
                                        </div>
                                        </a>
                                </div>
                        <?php endforeach; ?>
                        <!-- more content still to come here ... -->
                </div>
                <!-- // Page content -->

<?php require APPROOT.'/views/blog/footer.php';?>


                