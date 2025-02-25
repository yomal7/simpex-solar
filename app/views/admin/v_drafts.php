<?php require APPROOT.'/views/admin/header.php';?>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/admin/draft.css">
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
            <a href="<?php echo URLROOT; ?>/admin/createBlog">
                <span class="material-icons-sharp">post_add</span>
                <h3>Create Blog</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/admin/drafts" class="active">
                <span class="material-icons-sharp">drafts</span>
                <h3>Draft Blogs</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/admin/published" >
                <span class="material-icons-sharp">article</span>
                <h3>Published Blogs</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/admin/addCoordinator">
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
                <h1>Draft Posts</h1>
                <a style="background-color: #4CAF50;" href="<?php echo URLROOT; ?>/admin/createBlog" class="btn add-btn" >
                    <i class="fas fa-plus"></i> New Post
                </a>
            </div>

            <?php flash('draft_message'); ?>

            <div class="table-responsive">
                <table class="draft-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Created Date</th>
                            <th>Last Modified</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($data['drafts'])) : ?>
                            <?php foreach($data['drafts'] as $draft) : ?>
                                <tr data-draft-id="<?php echo $draft->post_id; ?>">
                                    <td data-label="Title">
                                        <div class="draft-title">
                                            <?php if($draft->featured_image) : ?>
                                                <img src="<?php echo URLROOT. '/uploads/blog/' . $draft->featured_image; ?>" alt="Post thumbnail">
                                            <?php endif; ?>
                                            <span><?php echo $draft->title; ?></span>
                                        </div>
                                    </td>
                                    <td data-label="Category"><?php echo $draft->category_name; ?></td>
                                    <td data-label="Created"><?php echo date('M d, Y', strtotime($draft->created_at)); ?></td>
                                    <td data-label="Modified"><?php echo date('M d, Y', strtotime($draft->updated_at)); ?></td>
                                    <td data-label="Actions" class="action-buttons">
                                        <button onclick="previewDraft(<?php echo $draft->post_id; ?>)" class="btn preview-btn">
                                            <i class="fas fa-eye"></i>
                                            <span class="btn-text">Preview</span>
                                        </button>
                                        <a href="<?php echo URLROOT; ?>/admin/editBlog/<?php echo $draft->post_id; ?>" class="btn edit-btn">
                                            <i class="fas fa-edit"></i>
                                            <span class="btn-text">Edit</span>
                                        </a>
                                        <button onclick="publishDraft(<?php echo $draft->post_id; ?>)" class="btn publish-btn">
                                            <i class="fas fa-paper-plane"></i>
                                            <span class="btn-text">Publish</span>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="5" class="no-drafts">No draft posts found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        </div>    
        </div>
    </div>

    <div id="publishModal" class="modal">
    <div class="modal-content">
        <h2>Publish Draft</h2>
        <p>Are you sure you want to publish this draft?</p>
        <div class="modal-actions">
            <button onclick="confirmPublish()" class="btn publish-btn">Publish</button>
            <button onclick="closeModal()" class="btn cancel-btn">Cancel</button>
        </div>
    </div>
    </div>
    <script>
    // Define URLROOT for JavaScript
        const URLROOT = '<?php echo URLROOT; ?>';
    </script>
    <script src="<?php echo URLROOT; ?>/js/admin/draft.js"></script>
<?php require APPROOT.'/views/admin/footer.php';?>