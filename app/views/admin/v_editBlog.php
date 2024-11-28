<?php require APPROOT.'/views/admin/header.php';?>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/admin/editBlog.css">
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
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
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
            <a href="<?php echo URLROOT; ?>/users/logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>
        <div class="main-content">
            <div class="container">
            <div class="content-wrapper">
                    <div class="page-header">
                        <h1>Edit Blog Post</h1>
                        <div class="header-actions">
                            <button class="btn preview-btn" onclick="previewPost()">
                                <span class="material-icons-sharp">visibility</span> Preview
                            </button>
                        </div>
                    </div>

                    <?php flash('post_message'); ?>

                    <div class="edit-form-container">
                        <form id="editBlogForm" action="<?php echo URLROOT; ?>/admin/editBlog/<?php echo $data['post']->post_id; ?>" 
                              method="POST" enctype="multipart/form-data">
                            
                            <div class="form-grid">
                                <!-- Title Field -->
                                <div class="form-group">
                                    <label for="title">Title <span class="required">*</span></label>
                                    <input type="text" 
                                           id="title" 
                                           name="title" 
                                           class="form-control <?php echo (!empty($data['title_err'])) ? 'is-invalid' : ''; ?>"
                                           value="<?php echo $data['post']->title; ?>" 
                                           required>
                                    <span class="error"><?php echo $data['title_err'] ?? ''; ?></span>
                                </div>

                                <!-- Category Field -->
                                <div class="form-group">
                                    <label for="category">Category <span class="required">*</span></label>
                                    <select id="category" 
                                            name="category_id" 
                                            class="form-control" 
                                            required>
                                        <option value="">Select Category</option>
                                        <?php foreach($data['categories'] as $category) : ?>
                                            <option value="<?php echo $category->category_id; ?>" 
                                                    <?php echo ($data['post']->category_id == $category->category_id) ? 'selected' : ''; ?>>
                                                <?php echo $category->name; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Featured Image Field -->
                                <div class="form-group featured-image">
                                    <label for="featured_image">Featured Image</label>
                                    <div class="image-upload-container" onclick="document.getElementById('featured_image').click()">
                                        <div id="preview" class="image-preview">
                                            <?php if($data['post']->featured_image) : ?>
                                                <img src="<?php echo URLROOT; ?>/public/uploads/blog/<?php echo $data['post']->featured_image; ?>" 
                                                     alt="Featured image" 
                                                     class="current-image">
                                                <span class="remove-image" onclick="removeImage(event)">×</span>
                                            <?php else : ?>
                                                <span class="material-icons-sharp upload-icon">cloud_upload</span>
                                                <span>Click to upload image</span>
                                            <?php endif; ?>
                                        </div>
                                        <input type="file" 
                                               id="featured_image" 
                                               name="featured_image" 
                                               accept="image/*" 
                                               hidden>
                                        <input type="hidden" 
                                               name="remove_image" 
                                               id="remove_image" 
                                               value="0">
                                    </div>
                                </div>

                                <!-- Summary Field -->
                                <div class="form-group full-width">
                                    <label for="summary">Summary <span class="required">*</span></label>
                                    <textarea id="summary" 
                                              name="summary" 
                                              class="form-control" 
                                              rows="3" 
                                              required><?php echo $data['post']->summary; ?></textarea>
                                    <span class="error"><?php echo $data['summary_err'] ?? ''; ?></span>
                                </div>

                                <!-- Content Field -->
                                <div class="form-group full-width">
                                    <label for="body">Content <span class="required">*</span></label>
                                    <textarea id="body" 
                                              name="body" 
                                              class="form-control" 
                                              required><?php echo $data['post']->body; ?></textarea>
                                    <span class="error"><?php echo $data['body_err'] ?? ''; ?></span>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="form-actions">
                                <a href="<?php echo URLROOT; ?>/admin/published" class="btn cancel-btn">
                                    <span class="material-icons-sharp">close</span> Cancel
                                </a>
                                <button type="submit" name="status" value="draft" class="btn draft-btn">
                                    <span class="material-icons-sharp">save</span> Save as Draft
                                </button>
                                <button type="submit" name="status" value="published" class="btn publish-btn">
                                    <span class="material-icons-sharp">publish</span> Update & Publish
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>    
        </div>
    </div>

    <script>
        const URLROOT = '<?php echo URLROOT; ?>';
    </script>
    <script src="<?php echo URLROOT; ?>/js/editBlog.js"></script>
<?php require APPROOT.'/views/admin/footer.php';?>