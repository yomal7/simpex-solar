<?php
class Blog extends Controller {
    private $blogModel;

    public function __construct() {
        $this->blogModel = $this->model('M_Blog');
    }

    public function index() {
        $posts = $this->blogModel->getPublishedPosts();
        $data = [
            'posts' => $posts,
            'topics' => $this->blogModel->getAllTopics()
            // 'title' => 'LifeBlog | Home'
        ];
        $this->view('blog/v_home', $data);
    }



    public function filteredPosts($topicId = null) {
        // Initialize variables
        $posts = [];
        $topicName = null;
        $singlePost = null;
        $topics = $this->blogModel->getAllTopics();
        
        // Check if we're looking for a specific post
        if (isset($_GET['post-slug'])) {
            $singlePost = $this->blogModel->getPost($_GET['post-slug']);
            
            if ($singlePost) {
                // If post found, prepare data for single post view
                $data = [
                    'post' => $singlePost,
                    'topics' => $topics,
                    'title' => 'LifeBlog | ' . $singlePost->title
                ];
                
                $this->view('blog/v_single_post', $data);
                return;
            } else {
                // If post not found, set flash message and continue to show filtered posts
                flash('post_message', 'Post not found', 'alert alert-danger');
            }
        }
        
        // Handle topic filtering
        if (!isset($topicId)) {
            $topicId = isset($_GET['topic']) ? (int)$_GET['topic'] : null;
        }
        
        if ($topicId) {
            // Get posts for specific topic
            $posts = $this->blogModel->getPublishedPostsByTopic($topicId);
            $topicName = $this->blogModel->getTopicNameById($topicId);
            
            if (!$topicName) {
                $topicName = 'Unknown Topic';
            }
        } else {
            // Get all published posts if no topic specified
            $posts = $this->blogModel->getAllPublishedPosts();
            $topicName = 'All Posts';
        }
        
        // Prepare data for view
        $data = [
            'posts' => $posts,
            'topics' => $topics,
            'topicName' => $topicName,
            'currentTopicId' => $topicId,
            'title' => 'LifeBlog | ' . $topicName
        ];
        
        // Load the view
        $this->view('blog/v_filtered_posts', $data);
    }
    

    public function singlePost($slug = null) {
        // Debug incoming slug
        error_log("Incoming slug: " . print_r($slug, true));

        if (!$slug) {
            $slug = $_GET['post-slug'] ?? null;
            error_log("Slug from GET: " . print_r($slug, true));
        }

        if (!$slug) {
            error_log("No slug provided - redirecting to blog index");
            header('Location: ' . URLROOT . '/blog');
            return;
        }

        // Get the post and debug the result
        $post = $this->blogModel->getPost($slug);
        error_log("Post data returned from model: " . print_r($post, true));

        // Get topics and debug the result
        $topics = $this->blogModel->getAllTopics();
        error_log("Topics data returned from model: " . print_r($topics, true));

        if (!$post) {
            error_log("Post not found for slug: " . $slug);
            flash('post_message', 'Post not found', 'alert alert-danger');
            header('Location: ' . URLROOT . '/blog');
            return;
        }

        // Debug the data array being passed to view
        $data = [
            'post' => $post,
            'topics' => $topics,
            'title' => $post->title . ' | LifeBlog'
        ];
        error_log("Data array being passed to view: " . print_r($data, true));

        // Debug before calling view
        error_log("About to load view: blog/v_single_post");
        $this->view('blog/v_single_post', $data);
    }

    public function getPost($slug) {
        if (empty($slug)) {
            return null;
        }
        
        // Get the post using the blog model
        $post = $this->blogModel->getPost($slug);
        
        if ($post) {
            // Get the post's topic using the blog model
            $post->topic = $this->blogModel->getPostTopic($post->id);
            
            // Get next and previous posts using the blog model
            $post->next_post = $this->blogModel->getAdjacentPost($post->created_at, 'next');
            $post->prev_post = $this->blogModel->getAdjacentPost($post->created_at, 'prev');
            
            // Convert body from stored HTML to display-safe HTML
            $post->body = html_entity_decode($post->body);
        }
        
        return $post;
    }
    
    
    
    
    public function getAdjacentPost($currentDate, $direction = 'next') {
        $operator = $direction === 'next' ? '>' : '<';
        $order = $direction === 'next' ? 'ASC' : 'DESC';
        
        $this->blogModel->query("
            SELECT id, title, slug 
            FROM posts 
            WHERE published = true 
            AND created_at {$operator} :current_date
            ORDER BY created_at {$order}
            LIMIT 1
        ");
        
        $this->blogModel->bind(':current_date', $currentDate);
        return $this->blogModel->single();
    }
    
    public function getAllTopics() {
        $this->blogModel->query("
            SELECT 
                t.*,
                COUNT(DISTINCT pt.post_id) as post_count
            FROM topics t
            LEFT JOIN post_topic pt ON t.id = pt.topic_id
            LEFT JOIN posts p ON pt.post_id = p.id AND p.published = true
            GROUP BY t.id
            ORDER BY t.name ASC
        ");
        
        return $this->blogModel->resultSet();
    }

    public function topics() {
        $topics = $this->blogModel->getAllTopics();
        $data = [
            'topics' => $topics,
            'title' => 'LifeBlog | Topics'
        ];
        $this->view('blog/topics', $data);
    }

    
}
?>