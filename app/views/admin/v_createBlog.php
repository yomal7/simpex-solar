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
            <img
                
                src="<?php echo URLROOT; ?>/public/assets/profile.png"
                alt="manager profile-picture"
                class="profile-picture"
            />
            <a href="#" class="active">
                <span class="material-icons-sharp">create</span>
                <h3>Create Blog</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp">person</span>
                <h3>Customers</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp">receipt_long</span>
                <h3>Projects</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp">inventory</span>
                <h3>Inventory</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp">group</span>
                <h3>Employees</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp">settings</span>
                <h3>Settings</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>
        <div class="main-content">
        <div class="container">
                <div class="content-wrapper">
                    <div class="page-header">
                        <h1>Create New Blog Post</h1>
                        <button class="preview-btn" onclick="previewPost()">
                            <i class="fas fa-eye"></i> Preview
                        </button>
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