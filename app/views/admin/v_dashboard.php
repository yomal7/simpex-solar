<?php require APPROOT.'/views/admin/header.php';?>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/admin/dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

        <!-- ************ -->
        <!-- Sidebar -->
        <!-- ************ -->

        <div class="sidebar" id="sidebar">
            <div class="company-logo">
                <img src="<?php echo URLROOT; ?>/public/assets/simpex-logo-sidebar.png" alt="Simpex Solar Logo">
            </div>
            <!-- User Profile Section -->
            <div class="user-profile">
                <img src="<?php echo isset($_SESSION['user_picture']) && !empty($_SESSION['user_picture']) ? URLROOT . '/public/uploads/profile_pictures/' . $_SESSION['user_picture'] : URLROOT . '/public/assets/profile.png'; ?>" alt="User profile picture" class="profile-picture" />
                <div class="user-info">
                    <h4><?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User Name'; ?></h4>
                    <p>Admin</p>
                </div>
            </div>
            <a href="<?php echo URLROOT; ?>/admin/createBlog" class="active">
                <span class="material-icons-sharp">post_add</span>
                <h3>Create Blog</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/admin/drafts">
                <span class="material-icons-sharp">drafts</span>
                <h3>Draft Blogs</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/admin/published" >
                <span class="material-icons-sharp">article</span>
                <h3>Published Blogs</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/admin/addCoordinator" >
                <span class="material-icons-sharp">supervisor_account</span>
                <h3>Manage Coordinators</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/admin/settings">
                <span class="material-icons-sharp">settings</span>
                <h3>Settings</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/users/logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>
        <div class="main-content">
        <div class="container">
                <div class="content-wrapper">
                    <div class="page-header">
                        <h1 style="margin-bottom: 10px;">Create New Blog Post</h1>
                    </div>

                    <?php flash('blog_message'); ?>

                    <form id="blogForm" action="<?php echo URLROOT; ?>/admin/createBlog" method="POST" enctype="multipart/form-data" class="blog-form">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" id="title" name="title" class="form-control <?php echo (!empty($data['title_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['title'] ?? ''; ?>" required>
                                <span class="invalid-feedback"><?php echo $data['title_err'] ?? ''; ?></span>
                            </div>

                            <div class="form-group">
                                <label for="category">Category</label>
                                <select id="category" name="category_id" class="form-control" required>
                                    <option value="">Select Category</option>
                                    <?php foreach($data['categories'] as $category) : ?>
                                        <option value="<?php echo $category->category_id; ?>">
                                            <?php echo $category->name; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group featured-image">
                                <label for="featured_image">Featured Image</label>
                                <div class="image-upload-container" onclick="document.getElementById('featured_image').click()">
                                    <div id="preview" class="image-preview">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>Click to upload image</span>
                                    </div>
                                    <input type="file" id="featured_image" name="featured_image" accept="image/*" hidden>
                                </div>
                            </div>

                            <div class="form-group full-width">
                                <label for="summary">Summary</label>
                                <textarea id="summary" name="summary" class="form-control" rows="3" required><?php echo $data['summary'] ?? ''; ?></textarea>
                            </div>

                            <div class="form-group full-width">
                                <label for="body">Content</label>
                                <textarea id="body" name="body" class="form-control" required><?php echo $data['body'] ?? ''; ?></textarea>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" name="status" value="draft" class="btn draft-btn">
                                <i class="fas fa-save"></i> Save Draft
                            </button>
                            <button type="submit" name="status" value="published" class="btn publish-btn">
                                <i class="fas fa-paper-plane"></i> Publish
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>    
        </div>
    </div>

    <script src="<?php echo URLROOT; ?>/js/admin/dashboard.js"></script>
<?php require APPROOT.'/views/admin/footer.php';?>