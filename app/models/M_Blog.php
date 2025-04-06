<?php
class M_Blog {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Create new blog post
    public function createPost($data) {
        $this->db->query('INSERT INTO blog_posts (user_id, category_id, title, slug, summary, body, featured_image, status) 
                         VALUES (:user_id, :category_id, :title, :slug, :summary, :body, :featured_image, :status)');
        
        // Bind values
        $this->db->bind(':user_id', $_SESSION['user_id']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':slug', $data['slug']);
        $this->db->bind(':summary', $data['summary']);
        $this->db->bind(':body', $data['body']);
        $this->db->bind(':featured_image', $data['featured_image']);
        $this->db->bind(':status', $data['status']);

        return $this->db->execute();
    }

    // Get all blog posts
    public function getAllPosts() {
        $this->db->query('SELECT p.*, c.name as category_name, u.name as author_name 
                         FROM blog_posts p 
                         LEFT JOIN blog_categories c ON p.category_id = c.category_id 
                         LEFT JOIN users u ON p.user_id = u.user_id 
                         ORDER BY p.created_at DESC');
        
        return $this->db->resultSet();
    }

    // Get post by ID
    public function getPostById($id) {
        $this->db->query('SELECT p.*, c.name as category_name, u.name as author_name 
                         FROM blog_posts p 
                         LEFT JOIN blog_categories c ON p.category_id = c.category_id 
                         LEFT JOIN users u ON p.user_id = u.user_id 
                         WHERE p.post_id = :id');
        
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }

    // Get all categories
    public function getCategories() {
        $this->db->query('SELECT * FROM blog_categories ORDER BY name');
        return $this->db->resultSet();
    }

    // Get total posts count
    public function getTotalPosts() {
        $this->db->query('SELECT COUNT(*) as total FROM blog_posts');
        $row = $this->db->single();
        return $row->total;
    }

    // Get total draft posts count
    public function getTotalDraftPosts() {
        $this->db->query("SELECT COUNT(*) as total FROM blog_posts WHERE status = 'draft'");
        $row = $this->db->single();
        return $row->total;
    }

    // Get total published posts count
    public function getTotalPublishedPosts() {
        $this->db->query("SELECT COUNT(*) as total FROM blog_posts WHERE status = 'published'");
        $row = $this->db->single();
        return $row->total;
    }

    // Get recent posts
    public function getRecentPosts($limit = 5) {
        $this->db->query('SELECT p.*, c.name as category_name, u.name as author_name 
                         FROM blog_posts p 
                         LEFT JOIN blog_categories c ON p.category_id = c.category_id 
                         LEFT JOIN users u ON p.user_id = u.user_id 
                         ORDER BY p.created_at DESC LIMIT :limit');
        
        $this->db->bind(':limit', $limit);
        
        return $this->db->resultSet();
    }

    // Increment view count
    public function incrementViews($id) {
        $this->db->query('UPDATE blog_posts SET views = views + 1 WHERE post_id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getDraftPosts() {
        $this->db->query('SELECT p.*, c.name as category_name 
                         FROM blog_posts p 
                         LEFT JOIN blog_categories c ON p.category_id = c.category_id 
                         WHERE p.status = "draft" 
                         ORDER BY p.updated_at DESC');
        
        return $this->db->resultSet();
    }
    
    public function publishDraft($id) {
        $this->db->query('UPDATE blog_posts SET status = "published" WHERE post_id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }

    public function getPublishedPosts($page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        
        $this->db->query('SELECT p.*, c.name as category_name 
                         FROM blog_posts p 
                         LEFT JOIN blog_categories c ON p.category_id = c.category_id 
                         WHERE p.status = "published" 
                         ORDER BY p.created_at DESC 
                         LIMIT :limit OFFSET :offset');
        
        $this->db->bind(':limit', $perPage);
        $this->db->bind(':offset', $offset);
        
        return $this->db->resultSet();
    }
    
    public function getPublishedPostsPagination($currentPage = 1, $perPage = 10) {
        $this->db->query('SELECT COUNT(*) as total FROM blog_posts WHERE status = "published"');
        $row = $this->db->single();
        $total = $row->total;
        
        $totalPages = ceil($total / $perPage);
        
        if ($totalPages <= 1) {
            return '';
        }
        
        $pagination = '<div class="pagination">';
        
        // Previous button
        if ($currentPage > 1) {
            $pagination .= '<a href="?page=' . ($currentPage - 1) . '" class="page-link">&laquo; Previous</a>';
        }
        
        // Page numbers
        for ($i = 1; $i <= $totalPages; $i++) {
            if ($i == $currentPage) {
                $pagination .= '<span class="page-link active">' . $i . '</span>';
            } else {
                $pagination .= '<a href="?page=' . $i . '" class="page-link">' . $i . '</a>';
            }
        }
        
        // Next button
        if ($currentPage < $totalPages) {
            $pagination .= '<a href="?page=' . ($currentPage + 1) . '" class="page-link">Next &raquo;</a>';
        }
        
        $pagination .= '</div>';
        
        return $pagination;
    }
    
    public function updatePost($data) {
        $this->db->query('UPDATE blog_posts SET 
            title = :title,
            slug = :slug,
            summary = :summary,
            body = :body,
            category_id = :category_id,
            featured_image = :featured_image,
            status = :status,
            updated_at = CURRENT_TIMESTAMP
            WHERE post_id = :id');
        
        $this->db->bind(':id', $data['post_id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':slug', $data['slug']);
        $this->db->bind(':summary', $data['summary']);
        $this->db->bind(':body', $data['body']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':featured_image', $data['featured_image']);
        $this->db->bind(':status', $data['status']);

        try {
            return $this->db->execute();
        } catch (PDOException $e) {
            // Log error
            error_log("Error updating post: " . $e->getMessage());
            return false;
        }
    }
    
    public function deletePost($id) {
        $this->db->query('DELETE FROM blog_posts WHERE post_id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }
    
    public function slugExists($slug, $excludeId = null) {
        $sql = 'SELECT post_id FROM blog_posts WHERE slug = :slug';
        if ($excludeId) {
            $sql .= ' AND post_id != :id';
        }
        
        $this->db->query($sql);
        $this->db->bind(':slug', $slug);
        
        if ($excludeId) {
            $this->db->bind(':id', $excludeId);
        }
        
        try {
            $this->db->execute();
            return $this->db->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error checking slug: " . $e->getMessage());
            return true; // Return true to force unique slug generation
        }
    }

    public function getPostRevisions($postId) {
        $this->db->query('SELECT * FROM post_revisions 
                         WHERE post_id = :post_id 
                         ORDER BY created_at DESC');
        
        $this->db->bind(':post_id', $postId);
        
        try {
            return $this->db->resultSet();
        } catch (PDOException $e) {
            error_log("Error getting post revisions: " . $e->getMessage());
            return [];
        }
    }

    public function createRevision($postData) {
        $this->db->query('INSERT INTO post_revisions 
                         (post_id, title, summary, body, category_id, status) 
                         VALUES (:post_id, :title, :summary, :body, :category_id, :status)');
        
        $this->db->bind(':post_id', $postData['post_id']);
        $this->db->bind(':title', $postData['title']);
        $this->db->bind(':summary', $postData['summary']);
        $this->db->bind(':body', $postData['body']);
        $this->db->bind(':category_id', $postData['category_id']);
        $this->db->bind(':status', $postData['status']);

        try {
            return $this->db->execute();
        } catch (PDOException $e) {
            error_log("Error creating revision: " . $e->getMessage());
            return false;
        }
    }

    public function getFeaturedPosts($limit = 3) {
        $this->db->query('SELECT p.*, c.name as category_name 
                         FROM blog_posts p 
                         LEFT JOIN blog_categories c ON p.category_id = c.category_id 
                         WHERE p.status = "published" 
                         ORDER BY p.views DESC, p.created_at DESC 
                         LIMIT :limit');
        
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    public function getAllPublishedPosts($page = 1, $postsPerPage = 6) {
        $offset = ($page - 1) * $postsPerPage;
        
        $this->db->query('SELECT p.*, c.name as category_name, c.slug as category_slug 
                         FROM blog_posts p 
                         LEFT JOIN blog_categories c ON p.category_id = c.category_id 
                         WHERE p.status = "published" 
                         ORDER BY p.created_at DESC 
                         LIMIT :limit OFFSET :offset');
        
        $this->db->bind(':limit', $postsPerPage);
        $this->db->bind(':offset', $offset);
        
        return $this->db->resultSet();
    }

    public function getPostsByCategory($categorySlug, $page = 1, $postsPerPage = 6) {
        $offset = ($page - 1) * $postsPerPage;
        
        $this->db->query('SELECT p.*, c.name as category_name, c.slug as category_slug 
                         FROM blog_posts p 
                         LEFT JOIN blog_categories c ON p.category_id = c.category_id 
                         WHERE p.status = "published" AND c.slug = :category_slug 
                         ORDER BY p.created_at DESC 
                         LIMIT :limit OFFSET :offset');
        
        $this->db->bind(':category_slug', $categorySlug);
        $this->db->bind(':limit', $postsPerPage);
        $this->db->bind(':offset', $offset);
        
        return $this->db->resultSet();
    }

    public function getTotalPostsByCategory($categorySlug) {
        $this->db->query('SELECT COUNT(*) as total 
                         FROM blog_posts p 
                         LEFT JOIN blog_categories c ON p.category_id = c.category_id 
                         WHERE p.status = "published" AND c.slug = :category_slug');
        
        $this->db->bind(':category_slug', $categorySlug);
        $row = $this->db->single();
        return $row->total;
    }


    // Get categories with post count
    public function getCategoriesWithCount() {
        $this->db->query('SELECT c.*, 
                         COUNT(CASE WHEN p.status = "published" THEN 1 END) as post_count 
                         FROM blog_categories c 
                         LEFT JOIN blog_posts p ON c.category_id = p.category_id 
                         GROUP BY c.category_id 
                         ORDER BY c.name');
        
        return $this->db->resultSet();
    }


    // Get post by slug
    public function getPostBySlug($slug) {
        $this->db->query('SELECT p.*, c.name as category_name, c.slug as category_slug 
                         FROM blog_posts p 
                         LEFT JOIN blog_categories c ON p.category_id = c.category_id 
                         WHERE p.slug = :slug AND p.status = "published"');
        
        $this->db->bind(':slug', $slug);
        return $this->db->single();
    }

    // Get related posts
    public function getRelatedPosts($categoryId, $currentPostId, $limit = 3) {
        $this->db->query('SELECT p.*, c.name as category_name, c.slug as category_slug 
                         FROM blog_posts p 
                         LEFT JOIN blog_categories c ON p.category_id = c.category_id 
                         WHERE p.status = "published" 
                         AND p.category_id = :category_id 
                         AND p.post_id != :current_post_id 
                         ORDER BY p.views DESC 
                         LIMIT :limit');
        
        $this->db->bind(':category_id', $categoryId);
        $this->db->bind(':current_post_id', $currentPostId);
        $this->db->bind(':limit', $limit);
        
        return $this->db->resultSet();
    }

}

?>