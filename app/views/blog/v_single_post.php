<?php require APPROOT . '/views/blog/header.php'; ?>
<div class="container">
    <?php require APPROOT . '/views/blog/navbar.php'; ?>
    <div class="content">
        <!-- Page wrapper -->
        <div class="post-wrapper">
            <!-- Full post div -->
            <div class="full-post-div">
                <?php if (isset($data['post']) && !$data['post']->published): ?>
                    <h2 class="post-title">Sorry... This post has not been published</h2>
                <?php elseif (isset($data['post'])): ?>
                    <h2 class="post-title"><?php echo htmlspecialchars($data['post']->title); ?></h2>
                    
                    <?php if (isset($data['post']->topic)): ?>
                        <div class="post-topic">
                            Topic: <a href="<?php echo URLROOT; ?>/blog/filteredPosts/<?php echo $data['post']->topic->id; ?>">
                                <?php echo htmlspecialchars($data['post']->topic->name); ?>
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="post-body-div">
                        <?php echo $data['post']->body; ?>
                    </div>

                    <!-- Post Navigation -->
                    <!-- <div class="post-navigation">
                        <?php if (isset($data['post']->prev_post)): ?>
                            <a href="<?php echo URLROOT; ?>/blog/singlePost/<?php echo $data['post']->prev_post->slug; ?>" class="prev-post">
                                &larr; Previous: <?php echo htmlspecialchars($data['post']->prev_post->title); ?>
                            </a>
                        <?php endif; ?>

                        <?php if (isset($data['post']->next_post)): ?>
                            <a href="<?php echo URLROOT; ?>/blog/singlePost/<?php echo $data['post']->next_post->slug; ?>" class="next-post">
                                Next: <?php echo htmlspecialchars($data['post']->next_post->title); ?> &rarr;
                            </a>
                        <?php endif; ?> -->

                        <div class="post-navigation">
                            <?php if (isset($data['post']->prev_post) && $data['post']->prev_post): ?>
                                <a href="<?php echo URLROOT; ?>/blog/singlePost/<?php echo $data['post']->prev_post->slug; ?>" class="prev-post">
                                    &larr; Previous: <?php echo htmlspecialchars($data['post']->prev_post->title); ?>
                                </a>
                            <?php endif; ?>

                            <?php if (isset($data['post']->next_post) && $data['post']->next_post): ?>
                                <a href="<?php echo URLROOT; ?>/blog/singlePost/<?php echo $data['post']->next_post->slug; ?>" class="next-post">
                                    Next: <?php echo htmlspecialchars($data['post']->next_post->title); ?> &rarr;
                                </a>
                            <?php endif; ?>
                        </div>

                    </div>
                <?php else: ?>
                    <h2 class="post-title">Post not found</h2>
                <?php endif; ?>
            </div>
            <!-- // Full post div -->
        </div>
        <!-- // Page wrapper -->

        <!-- Post sidebar -->
        <div class="post-sidebar">
            <div class="card">
                <div class="card-header">
                    <h2>Topics</h2>
                </div>
                <div class="card-content">
                    <?php if (isset($data['topics']) && !empty($data['topics'])): ?>
                        <?php foreach ($data['topics'] as $topic): ?>
                            <a 
                                href="<?php echo URLROOT; ?>/blog/filteredPosts/<?php echo $topic->id; ?>"
                                class="topic-link <?php echo (isset($data['post']->topic) && $data['post']->topic->id === $topic->id) ? 'active' : ''; ?>"
                            >
                                <?php echo htmlspecialchars($topic->name); ?>
                                <span class="post-count">(<?php echo $topic->post_count; ?>)</span>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No topics available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- // Post sidebar -->
    </div>
</div>

<?php require APPROOT . '/views/blog/footer.php'; ?>