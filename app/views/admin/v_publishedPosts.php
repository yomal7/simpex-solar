<?php require APPROOT.'/views/admin/header.php';?>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/admin/published.css">
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
            <a href="<?php echo URLROOT; ?>/admin/drafts">
                <span class="material-icons-sharp">drafts</span>
                <h3>Draft Blogs</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/admin/published" class="active">
                <span class="material-icons-sharp">article</span>
                <h3>Published Blogs</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/admin/addCoordinator">
                <span class="material-icons-sharp">supervisor_account</span>
                <h3>Manage Coordinators</h3>
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
                <h1>Published Posts</h1>
                <a style="background-color: #4CAF50; margin-bottom: 1rem; text-decoration: none;" href="<?php echo URLROOT; ?>/admin/createBlog" class="btn add-btn"  >
                    <i class="fas fa-plus"></i> New Post
                </a>
            </div>

            <?php flash('post_message'); ?>

            <div class="table-responsive">
                <table class="posts-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Views</th>
                            <th>Published Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($data['posts'])) : ?>
                            <?php foreach($data['posts'] as $post) : ?>
                                <tr data-post-id="<?php echo $post->post_id; ?>">
                                    <td data-label="Title">
                                        <div class="post-title">
                                            <?php if($post->featured_image) : ?>
                                                <img src="<?php echo URLROOT; ?>/public/uploads/blog/<?php echo $post->featured_image; ?>" 
                                                     alt="<?php echo $post->title; ?>" class="post-thumbnail">
                                            <?php endif; ?>
                                            <div class="title-info">
                                                <span class="title"><?php echo $post->title; ?></span>
                                                <span class="post-slug"><?php echo $post->slug; ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="Category"><?php echo $post->category_name; ?></td>
                                    <td data-label="Views"><?php echo $post->views; ?></td>
                                    <td data-label="Published"><?php echo date('M d, Y', strtotime($post->created_at)); ?></td>
                                    <td data-label="Actions" class="action-buttons">
                                        <a href="<?php echo URLROOT; ?>/blog/showPost/<?php echo $post->slug; ?>"" 
                                           target="_blank" 
                                           class="btn view-btn" 
                                           title="View Post">
                                           <!-- <span style="font-size: 1rem" class="material-icons-sharp">remove_red_eye</span> -->
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo URLROOT; ?>/admin/editBlog/<?php echo $post->post_id; ?>" 
                                           class="btn edit-btn" 
                                           title="Edit Post">
                                           <!-- <span style="font-size: 1rem" class="material-icons-sharp">edit</span> -->
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="confirmDelete(<?php echo $post->post_id; ?>, '<?php echo $post->title; ?>')" 
                                                class="btn delete-btn" 
                                                title="Delete Post">
                                            <!-- <span style="font-size: 1rem" class="material-icons-sharp">delete</span> -->
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="5" class="no-posts">No published posts found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if(isset($data['pagination'])) : ?>
                <div class="pagination">
                    <?php echo $data['pagination']; ?>
                </div>
            <?php endif; ?>
                
            </div>    
        </div>
    </div>

    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <h2>Delete Post</h2>
            <p>Are you sure you want to delete "<span id="deletePostTitle"></span>"?</p>
            <p class="warning">This action cannot be undone.</p>
            <div class="modal-actions">
                <button onclick="confirmDeletePost()" class="btn delete-btn">Delete</button>
                <button onclick="closeModal()" class="btn cancel-btn">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        const URLROOT = '<?php echo URLROOT; ?>';
    </script>
    <script src="<?php echo URLROOT; ?>/js/admin/published.js"></script>
<?php require APPROOT.'/views/admin/footer.php';?>