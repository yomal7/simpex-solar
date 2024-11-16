<?php require APPROOT.'/views/inc/header.php';?>
    <!-- Top Navbar -->
     <?php require APPROOT.'/views/inc/components/topnavbar.php'; ?>
    <h1>Post create</h1>

    <div class="post-container" >
        <center><h2>Create a post</h2></center>
        <form action="<?php echo URLROOT; ?>/posts/create" method="POST">
            <input type="text" name="title" id="title" placeholder="title" value="<?php $data['title'];?>">
            <span class="form-invalid"><?php echo isset($data['title_err']) ? $data['title_err'] : ''; ?></span>
            <br>
            <textarea name="body" id="body" placeholder="content" rows="10" cols="30"></textarea>
            <span><?php echo isset($data['body_err']) ? $data['body_err'] : ''; ?></span>
            <input type="submit" value="Post" class="post-btn">
        </form>
    </div>
<?php require APPROOT.'/views/inc/footer.php';?>