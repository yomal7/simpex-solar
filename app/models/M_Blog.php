<?php
class M_Blog {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getPublishedPosts() {
        $this->db->query("SELECT * FROM posts WHERE published=true");
        $posts = $this->db->resultSet();
    
        foreach ($posts as &$post) {
            $post->topic = $this->getPostTopic($post->id);
        }
    
        return $posts;
    }

    public function getPostTopic($postId) {
        if (!is_numeric($postId)) {
            error_log("Invalid post ID: " . print_r($postId, true));
            return null;
        }
    
        $this->db->query("
            SELECT t.* 
            FROM topics t
            INNER JOIN post_topic pt ON t.id = pt.topic_id
            WHERE pt.post_id = :post_id 
            LIMIT 1
        ");
    
        $this->db->bind(':post_id', $postId);
        $topic = $this->db->single();
    
        if (!$topic) {
            error_log("No topic found for post ID: $postId");
            return null;
        }
    
        return $topic;
    }


    public function getPublishedPostsByTopic($topicId) {
        if (!is_numeric($topicId)) {
            error_log("Invalid topic ID provided: " . print_r($topicId, true));
            return [];
        }
        
        // First verify the topic exists
        $this->db->query("SELECT id, name FROM topics WHERE id = :topic_id");
        $this->db->bind(':topic_id', $topicId);
        
        $topic = $this->db->single();
        if (!$topic) {
            error_log("Topic not found with ID: $topicId");
            return [];
        }
        
        // Get posts for the topic
        $sql = "
            SELECT 
                p.*,
                t.name as topic_name,
                t.slug as topic_slug
            FROM posts p
            INNER JOIN post_topic pt ON p.id = pt.post_id
            INNER JOIN topics t ON pt.topic_id = t.id
            WHERE pt.topic_id = :topic_id 
            AND p.published = true
            ORDER BY p.created_at DESC
        ";
        
        $this->db->query($sql);
        $this->db->bind(':topic_id', $topicId);
        $posts = $this->db->resultSet();
        
        if (!empty($posts)) {
            foreach ($posts as &$post) {
                $post->topic = (object)[
                    'id' => $topicId,
                    'name' => $topic->name,
                    'slug' => $post->topic_slug ?? null
                ];
            }
        }
        
        return $posts;
    }

    public function getTopicNameById($id) {
        if (!is_numeric($id)) {
            error_log("Invalid topic ID format: " . print_r($id, true));
            return null;
        }
        
        $this->db->query("SELECT name FROM topics WHERE id = :id");
        $this->db->bind(':id', $id);
        $result = $this->db->single();
        
        return $result ? $result->name : null;
    }



    public function getAdjacentPost($currentDate, $direction = 'next') {
        $operator = $direction === 'next' ? '>' : '<';
        $order = $direction === 'next' ? 'ASC' : 'DESC';
        
        $this->db->query("
            SELECT id, title, slug 
            FROM posts 
            WHERE published = true 
            AND created_at {$operator} :current_date
            ORDER BY created_at {$order}
            LIMIT 1
        ");
        
        $this->db->bind(':current_date', $currentDate);
        return $this->db->single();
    }

    public function getAllPublishedPosts() {
        $this->db->query("
            SELECT 
                p.*,
                t.name as topic_name,
                t.slug as topic_slug,
                t.id as topic_id
            FROM posts p
            LEFT JOIN post_topic pt ON p.id = pt.post_id
            LEFT JOIN topics t ON pt.topic_id = t.id
            WHERE p.published = true
            ORDER BY p.created_at DESC
        ");
        
        $posts = $this->db->resultSet();
        
        if (!empty($posts)) {
            foreach ($posts as &$post) {
                $post->topic = (object)[
                    'id' => $post->topic_id,
                    'name' => $post->topic_name,
                    'slug' => $post->topic_slug
                ];
                unset($post->topic_id);
                unset($post->topic_name);
                unset($post->topic_slug);
            }
        }
        
        return $posts;
    }

    public function getAllTopics() {
        $this->db->query("
            SELECT 
                t.*,
                COUNT(pt.post_id) as post_count
            FROM topics t
            LEFT JOIN post_topic pt ON t.id = pt.topic_id
            LEFT JOIN posts p ON pt.post_id = p.id AND p.published = true
            GROUP BY t.id
            ORDER BY t.name ASC
        ");
        
        return $this->db->resultSet();
    }

    public function getPost($slug) {
        if (empty($slug)) {
            return null;
        }
        
        $this->db->query("SELECT * FROM posts WHERE slug=:slug AND published=true");
        $this->db->bind(':slug', $slug);
        $post = $this->db->single();

        if ($post) {
            $post->topic = $this->getPostTopic($post->id);
            $post->next_post = $this->getAdjacentPost($post->created_at, 'next');
            $post->prev_post = $this->getAdjacentPost($post->created_at, 'prev');
            $post->body = html_entity_decode($post->body);
        }

        return $post;
    }

    // public function getPostTopic($postId) {
    //     $this->query("
    //         SELECT t.name AS topic_name 
    //         FROM topics t 
    //         INNER JOIN post_topic pt ON t.id = pt.topic_id 
    //         WHERE pt.post_id = :postId
    //     ");
    //     $this->bind(':postId', $postId);
    //     return $this->single();
    // }
}
?>
