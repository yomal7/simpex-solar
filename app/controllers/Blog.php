<?php
class Blog extends Controller {
    private $blogModel;
    private $blogService;

    public function __construct() {
        $this->blogModel = $this->model('M_Blog');
        require_once '../app/services/BlogService.php';
        $this->blogService = new BlogService($this->blogModel);
    }

    public function index() {
        // Get active category from URL parameter
        $activeCategory = isset($_GET['category']) ? $_GET['category'] : null;
        
        // Get page number
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $postsPerPage = 6;
        
        // Use service to get homepage data
        $blogData = $this->blogService->getHomePageData($page, $activeCategory);

        // Calculate pagination
        if ($activeCategory) {
            $totalPosts = $this->blogModel->getTotalPostsByCategory($activeCategory);
        } else {
            $totalPosts = $this->blogModel->getTotalPublishedPosts();
        }
        
        $totalPages = ceil($totalPosts / $postsPerPage);

        $data = [
            'title' => 'Blog',
            'featured_posts' => $blogData['featured_posts'],
            'categories' => $blogData['categories'],
            'posts' => $blogData['posts'],
            'active_category' => $activeCategory,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'has_previous' => $page > 1,
                'has_next' => $page < $totalPages
            ]
        ];

        $this->view('blog/v_home', $data);
    }

    // Renamed from 'view' to 'showPost'
    public function showPost($slug) {
        // Get post by slug
        $post = $this->blogModel->getPostBySlug($slug);
        
        if ($post) {
            // Increment views
            $this->blogService->incrementPostViews($post->post_id);
            
            // Calculate read time
            $post->read_time = $this->blogService->calculateReadTime($post->body);
            
            // Get related posts from same category
            $relatedPosts = $this->blogModel->getRelatedPosts($post->category_id, $post->post_id, 3);
            
            $data = [
                'title' => $post->title,
                'post' => $post,
                'related_posts' => $relatedPosts
            ];
            
            $this->view('blog/v_post', $data);
        } else {
            redirect('blog');
        }
    }

    public function fetch() {
        if (!isset($_GET['page'])) {
            echo json_encode(['error' => 'Page parameter required']);
            return;
        }

        $page = (int)$_GET['page'];
        $category = isset($_GET['category']) ? $_GET['category'] : null;
        
        // Get posts data from service
        $blogData = $this->blogService->getHomePageData($page, $category);
        
        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode([
            'posts' => $blogData['posts'],
            'has_more' => count($blogData['posts']) === 6
        ]);
    }

    public function filterByCategory() {
        if (!isset($_GET['category'])) {
            echo json_encode(['error' => 'Category parameter required']);
            return;
        }

        $category = $_GET['category'];
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        
        // Get filtered posts from service
        $blogData = $this->blogService->getHomePageData($page, $category);
        
        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode([
            'posts' => $blogData['posts'],
            'total_posts' => $this->blogModel->getTotalPostsByCategory($category)
        ]);
    }
}
