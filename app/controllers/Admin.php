<?php
class Admin extends Controller {
    private $blogModel;
    
    public function __construct() {
        // Check if user is logged in and is admin
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            flash('error_msg', 'Unauthorized access');
            redirect('users/login');
        }
        
        $this->blogModel = $this->model('M_Blog');
    }

    // Dashboard
    public function index() {
        $data = [
            'total_posts' => $this->blogModel->getTotalPosts(),
            'draft_posts' => $this->blogModel->getTotalDraftPosts(),
            'published_posts' => $this->blogModel->getTotalPublishedPosts(),
            'recent_posts' => $this->blogModel->getRecentPosts(5)
        ];
        
        $this->view('admin/v_dashboard', $data);
    }

    // Show create blog form
    public function createBlog() {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $data = [
                'categories' => $this->blogModel->getCategories(),
                'title' => '',
                'summary' => '',
                'body' => '',
                'category_id' => '',
                'title_err' => '',
                'summary_err' => '',
                'body_err' => ''
            ];
            
            $this->view('admin/v_createBlog', $data);
        } else {
            // Handle POST request
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Handle file upload
            $featuredImage = '';
            if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
                $featuredImage = $this->handleImageUpload($_FILES['featured_image']);
            }
            
            $data = [
                'title' => trim($_POST['title']),
                'summary' => trim($_POST['summary']),
                'body' => $_POST['body'], // Don't trim CKEditor content
                'category_id' => trim($_POST['category_id']),
                'featured_image' => $featuredImage,
                'status' => $_POST['status'],
                'categories' => $this->blogModel->getCategories(),
                'title_err' => '',
                'summary_err' => '',
                'body_err' => ''
            ];
            
            // Validate Title
            if (empty($data['title'])) {
                $data['title_err'] = 'Please enter title';
            }
            
            // Validate Summary
            if (empty($data['summary'])) {
                $data['summary_err'] = 'Please enter summary';
            }
            
            // Validate Body
            if (empty($data['body'])) {
                $data['body_err'] = 'Please enter content';
            }
            
            // Make sure no errors
            if (empty($data['title_err']) && empty($data['summary_err']) && empty($data['body_err'])) {
                // Generate slug from title
                $data['slug'] = $this->createSlug($data['title']);
                
                if ($this->blogModel->createPost($data)) {
                    flash('blog_message', 'Post created successfully');
                    redirect('admin/blogs');
                } else {
                    flash('blog_message', 'Something went wrong', 'alert alert-danger');
                    $this->view('admin/v_createBlog', $data);
                }
            } else {
                // Load view with errors
                $this->view('admin/v_createBlog', $data);
            }
        }
    }

    // Handle image upload
    private function handleImageUpload($file) {
        $uploadDir = 'uploads/blog/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileName = uniqid() . '_' . basename($file['name']);
        $targetPath = $uploadDir . $fileName;
        
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return $fileName;
        }
        
        return '';
    }

    public function drafts() {
        $drafts = $this->blogModel->getDraftPosts();
        $data = [
            'drafts' => $drafts
        ];
        
        $this->view('admin/v_drafts', $data);
    }
    
    public function publishDraft($id) {
        // Check if it's an AJAX request
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            
            header('Content-Type: application/json');
            
            if (!$this->blogModel->getPostById($id)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Draft not found'
                ]);
                return;
            }
            
            if ($this->blogModel->publishDraft($id)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Post published successfully'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to publish post'
                ]);
            }
            return;
        }
        
        // Handle regular POST request
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->blogModel->publishDraft($id)) {
                flash('draft_message', 'Post published successfully');
            } else {
                flash('draft_message', 'Failed to publish post', 'alert alert-danger');
            }
        }
        
        redirect('admin/drafts');
    }
    
    public function previewDraft($id) {
        $draft = $this->blogModel->getPostById($id);
        if ($draft && $draft->status == 'draft') {
            $data = ['post' => $draft];
            $this->view('admin/v_previewDraft', $data);
        } else {
            redirect('admin/drafts');
        }
    }

    // Manage blog posts
    public function blogs() {
        $posts = $this->blogModel->getAllPosts();
        $data = [
            'posts' => $posts
        ];
        
        $this->view('admin/v_manageBlog', $data);
    }
    public function published() {
        // Get posts with pagination
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 10;
        
        $data = [
            'posts' => $this->blogModel->getPublishedPosts($page, $perPage),
            'pagination' => $this->blogModel->getPublishedPostsPagination($page, $perPage)
        ];
        
        $this->view('admin/v_publishedPosts', $data);
    }
    
    public function deleteBlog($id) {
        // Check if it's an AJAX request
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            
            header('Content-Type: application/json');
            
            // Get post before deletion for cleanup
            $post = $this->blogModel->getPostById($id);
            
            if (!$post) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Post not found'
                ]);
                return;
            }
    
            // Delete the post
            if ($this->blogModel->deletePost($id)) {
                // Delete associated image if exists
                if ($post->featured_image) {
                    $imagePath = 'public/uploads/blog/' . $post->featured_image;
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
    
                echo json_encode([
                    'success' => true,
                    'message' => 'Post deleted successfully'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to delete post'
                ]);
            }
            return;
        }
    
        // Handle regular POST request
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->blogModel->deletePost($id)) {
                flash('post_message', 'Post deleted successfully');
            } else {
                flash('post_message', 'Failed to delete post', 'alert alert-danger');
            }
        }
    
        redirect('admin/published');
    }

    public function editBlog($id = null) {
        if ($id === null) {
            flash('error_msg', 'No post specified');
            redirect('admin/published');
        }
    
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Check if it's an AJAX request
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                      strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
            
            // Handle POST request
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Get existing post data
            $post = $this->blogModel->getPostById($id);
            if (!$post) {
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Post not found']);
                    return;
                }
                flash('error_msg', 'Post not found');
                redirect('admin/published');
            }
    
            // Handle file upload
            $featuredImage = $post->featured_image; // Keep existing image by default
            
            // Check if image should be removed
            if (isset($_POST['remove_image']) && $_POST['remove_image'] == '1') {
                if ($featuredImage) {
                    $oldImagePath = 'public/uploads/blog/' . $featuredImage;
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                $featuredImage = null;
            }
            // Check for new image upload
            elseif (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
                // Delete old image if exists
                if ($post->featured_image) {
                    $oldImagePath = 'public/uploads/blog/' . $post->featured_image;
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                $featuredImage = $this->handleImageUpload($_FILES['featured_image']);
            }
    
            $data = [
                'post_id' => $id,
                'title' => trim($_POST['title']),
                'summary' => trim($_POST['summary']),
                'body' => $_POST['body'],
                'category_id' => trim($_POST['category_id']),
                'featured_image' => $featuredImage,
                'status' => $_POST['status'],
                'title_err' => '',
                'summary_err' => '',
                'body_err' => ''
            ];
    
            // Validation
            if (empty($data['title'])) {
                $data['title_err'] = 'Please enter title';
            }
    
            if (empty($data['summary'])) {
                $data['summary_err'] = 'Please enter summary';
            }
    
            if (empty($data['body'])) {
                $data['body_err'] = 'Please enter content';
            }
    
            // Handle AJAX requests (draft save or auto-save)
            if ($isAjax) {
                header('Content-Type: application/json');
                
                if (empty($data['title_err']) && empty($data['summary_err']) && empty($data['body_err'])) {
                    // Update slug if title changed
                    if ($data['title'] !== $post->title) {
                        $data['slug'] = $this->createSlug($data['title']);
                        if ($this->blogModel->slugExists($data['slug'], $id)) {
                            $data['slug'] = $data['slug'] . '-' . uniqid();
                        }
                    } else {
                        $data['slug'] = $post->slug;
                    }
    
                    $success = $this->blogModel->updatePost($data);
                    
                    // Check if this is a draft save
                    if ($success && $data['status'] === 'draft') {
                        echo json_encode([
                            'success' => true,
                            'message' => 'Post saved as draft successfully',
                            'redirect' => URLROOT . '/admin/drafts'
                        ]);
                    } else {
                        echo json_encode([
                            'success' => $success,
                            'message' => $success ? 'Post updated successfully' : 'Error updating post'
                        ]);
                    }
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Validation failed',
                        'errors' => [
                            'title' => $data['title_err'],
                            'summary' => $data['summary_err'],
                            'body' => $data['body_err']
                        ]
                    ]);
                }
                return;
            }
    
            // Handle regular form submission
            if (empty($data['title_err']) && empty($data['summary_err']) && empty($data['body_err'])) {
                // Update slug if title changed
                if ($data['title'] !== $post->title) {
                    $data['slug'] = $this->createSlug($data['title']);
                    if ($this->blogModel->slugExists($data['slug'], $id)) {
                        $data['slug'] = $data['slug'] . '-' . uniqid();
                    }
                } else {
                    $data['slug'] = $post->slug;
                }
    
                if ($this->blogModel->updatePost($data)) {
                    flash('post_message', 'Post updated successfully');
                    if ($data['status'] === 'draft') {
                        redirect('admin/drafts');
                    } else {
                        redirect('admin/published');
                    }
                } else {
                    flash('post_message', 'Error updating post', 'alert alert-danger');
                }
            }
    
            // Load view with errors
            $data['categories'] = $this->blogModel->getCategories();
            $data['post'] = $post;
            $this->view('admin/v_editBlog', $data);
            
        } else {
            // Handle GET request
            $post = $this->blogModel->getPostById($id);
            
            if (!$post) {
                flash('post_message', 'Post not found', 'alert alert-danger');
                redirect('admin/published');
            }
            
            $data = [
                'post' => $post,
                'categories' => $this->blogModel->getCategories()
            ];
            
            $this->view('admin/v_editBlog', $data);
        }
    }


    private function createSlug($string) {
        // Convert to lowercase and remove special characters
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
        // Remove multiple dashes
        $slug = preg_replace('/-+/', '-', $slug);
        // Remove leading/trailing dashes
        return trim($slug, '-');
    }
}