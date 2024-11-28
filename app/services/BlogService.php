<?php

class BlogService {
    private $blogModel;

    public function __construct($blogModel) {
        $this->blogModel = $blogModel;
    }

    public function getHomePageData($page = 1, $category = null) {
        // Get featured posts
        $featuredPosts = $this->blogModel->getFeaturedPosts();
        
        // Process featured posts
        foreach ($featuredPosts as $post) {
            $post->summary = $this->formatSummary($post->summary);
            $post->read_time = $this->calculateReadTime($post->body);
        }

        // Get posts based on category
        $postsPerPage = 6;
        $posts = $category ? 
            $this->blogModel->getPostsByCategory($category, $page, $postsPerPage) :
            $this->blogModel->getAllPublishedPosts($page, $postsPerPage);

        // Process posts
        foreach ($posts as $post) {
            $post->summary = $this->formatSummary($post->summary);
            $post->read_time = $this->calculateReadTime($post->body);
        }

        // Get categories with post counts
        $categories = $this->blogModel->getCategoriesWithCount();

        return [
            'featured_posts' => $featuredPosts,
            'posts' => $posts,
            'categories' => $categories
        ];
    }

    public function formatSummary($summary, $length = 150) {
        if (strlen($summary) <= $length) {
            return $summary;
        }
        
        $summary = substr($summary, 0, $length);
        $lastSpace = strrpos($summary, ' ');
        
        return substr($summary, 0, $lastSpace) . '...';
    }


    public function getPostWithDetails($slug) {
        return $this->blogModel->getPostBySlug($slug);
    }

    public function incrementPostViews($postId) {
        return $this->blogModel->incrementViews($postId);
    }

    public function getRelatedPosts($categoryId, $currentPostId, $limit = 3) {
        return $this->blogModel->getRelatedPosts($categoryId, $currentPostId, $limit);
    }

    public function calculateReadTime($content) {
        $wordsPerMinute = 200;
        $wordCount = str_word_count(strip_tags($content));
        $minutes = ceil($wordCount / $wordsPerMinute);
        
        return $minutes . ' min' . ($minutes === 1 ? '' : 's');
    }

    public function formatPostContent($content) {
        // Add any necessary content formatting
        return $content;
    }
}