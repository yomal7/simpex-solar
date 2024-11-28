document.addEventListener('DOMContentLoaded', function() {
    // Add animation order to post cards
    document.querySelectorAll('.post-card').forEach((card, index) => {
        card.style.setProperty('--animation-order', index);
    });

    // Smooth scrolling for category links
    document.querySelectorAll('.category-item').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            
            // Update URL without refresh
            window.history.pushState({}, '', href);
            
            // Fetch and update content
            fetchPosts(href);
        });
    });

    // Infinite scroll
    let loading = false;
    let page = 1;

    window.addEventListener('scroll', () => {
        if (loading) return;

        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 1000) {
            loading = true;
            page++;
            
            // Get current URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const category = urlParams.get('category');
            
            // Construct URL for next page
            let url = `${URLROOT}/blog/fetch?page=${page}`;
            if (category) {
                url += `&category=${category}`;
            }

            // Fetch more posts
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.posts.length > 0) {
                        appendPosts(data.posts);
                        loading = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    loading = false;
                });
        }
    });
});

function fetchPosts(url) {
    const postsGrid = document.querySelector('.posts-grid');
    
    // Show loading skeleton
    postsGrid.innerHTML = getLoadingSkeleton();

    fetch(url)
        .then(response => response.json())
        .then(data => {
            // Update posts
            postsGrid.innerHTML = '';
            appendPosts(data.posts);
            
            // Update active category
            document.querySelectorAll('.category-item').forEach(item => {
                item.classList.remove('active');
                if (item.getAttribute('href') === url) {
                    item.classList.add('active');
                }
            });
        })
        .catch(error => console.error('Error:', error));
}

function appendPosts(posts) {
    const postsGrid = document.querySelector('.posts-grid');
    
    posts.forEach((post, index) => {
        const postElement = createPostElement(post);
        postElement.style.setProperty('--animation-order', index);
        postsGrid.appendChild(postElement);
    });
}

function createPostElement(post) {
    const article = document.createElement('article');
    article.className = 'post-card';
    
    // Add post HTML structure
    article.innerHTML = `
        <div class="post-image">
            <img src="${URLROOT}/public/uploads/blog/${post.featured_image}" 
                 alt="${post.title}">
            <span class="category-badge">${post.category_name}</span>
        </div>
        <div class="post-content">
            <h3>${post.title}</h3>
            <p>${post.summary}</p>
            <div class="post-meta">
                <span class="date">
                    <span class="material-icons-sharp">calendar_today</span>
                    ${new Date(post.created_at).toLocaleDateString()}
                </span>
                <span class="views">
                    <span class="material-icons-sharp">visibility</span>
                    ${post.views}
                </span>
            </div>
            <a href="${URLROOT}/blog/post/${post.slug}" class="read-more">
                Read Article
                <span class="material-icons-sharp">arrow_forward</span>
            </a>
        </div>
    `;
    
    return article;
}

function getLoadingSkeleton() {
    let skeleton = '';
    for (let i = 0; i < 6; i++) {
        skeleton += `
            <article class="post-card skeleton">
                <div class="post-image skeleton"></div>
                <div class="post-content">
                    <div class="skeleton" style="height: 24px; width: 80%;"></div>
                    <div class="skeleton" style="height: 16px; width: 90%; margin: 10px 0;"></div>
                    <div class="skeleton" style="height: 16px; width: 70%;"></div>
                </div>
            </article>
        `;
    }
    return skeleton;
}

document.addEventListener('DOMContentLoaded', function() {
    const wrapper = document.querySelector('.categories-wrapper');
    const prevBtn = document.querySelector('.nav-arrow.prev');
    const nextBtn = document.querySelector('.nav-arrow.next');
    
    // Initial check for arrow visibility
    checkArrowsVisibility();
    
    // Check arrow visibility on window resize
    window.addEventListener('resize', checkArrowsVisibility);
});

function scrollCategories(direction) {
    const wrapper = document.querySelector('.categories-wrapper');
    const scrollAmount = wrapper.offsetWidth * 0.8; // 80% of visible width
    
    if (direction === 'left') {
        wrapper.scrollBy({
            left: -scrollAmount,
            behavior: 'smooth'
        });
    } else {
        wrapper.scrollBy({
            left: scrollAmount,
            behavior: 'smooth'
        });
    }
    
    // Check arrows after scrolling
    setTimeout(checkArrowsVisibility, 100);
}

function checkArrowsVisibility() {
    const wrapper = document.querySelector('.categories-wrapper');
    const prevBtn = document.querySelector('.nav-arrow.prev');
    const nextBtn = document.querySelector('.nav-arrow.next');
    
    // Show/hide prev arrow
    if (wrapper.scrollLeft <= 0) {
        prevBtn.classList.add('hidden');
    } else {
        prevBtn.classList.remove('hidden');
    }
    
    // Show/hide next arrow
    if (wrapper.scrollLeft + wrapper.offsetWidth >= wrapper.scrollWidth) {
        nextBtn.classList.add('hidden');
    } else {
        nextBtn.classList.remove('hidden');
    }
    
    // Hide both arrows if all content is visible
    if (wrapper.scrollWidth <= wrapper.offsetWidth) {
        prevBtn.classList.add('hidden');
        nextBtn.classList.add('hidden');
    }
}

// Add scroll event listener to update arrows
document.querySelector('.categories-wrapper').addEventListener('scroll', () => {
    requestAnimationFrame(checkArrowsVisibility);
});

//for post loading

document.addEventListener('DOMContentLoaded', function() {
    // Initialize post cards animation
    initializePostCards();
    
    // Initialize category navigation
    initializeCategoryNavigation();
    
    // Initialize infinite scroll
    initializeInfiniteScroll();
    
    // Initialize category arrows
    initializeCategoryArrows();
});

function initializePostCards() {
    document.querySelectorAll('.post-card').forEach((card, index) => {
        card.style.setProperty('--animation-order', index);
    });
}

function initializeCategoryNavigation() {
    document.querySelectorAll('.category-item').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            
            // Update active state immediately
            document.querySelectorAll('.category-item').forEach(item => {
                item.classList.remove('active');
            });
            this.classList.add('active');
            
            // Show loading state
            const postsGrid = document.querySelector('.posts-grid');
            postsGrid.innerHTML = getLoadingSkeleton();
            
            // Update URL without refresh
            window.history.pushState({}, '', href);
            
            // Fetch posts for the category
            fetch(href)
                .then(response => response.text())
                .then(html => {
                    // Create a temporary container
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = html;
                    
                    // Extract the posts grid content
                    const newPostsGrid = tempDiv.querySelector('.posts-grid');
                    const newPagination = tempDiv.querySelector('.pagination-wrapper');
                    
                    if (newPostsGrid) {
                        // Replace the current posts grid
                        postsGrid.innerHTML = newPostsGrid.innerHTML;
                        
                        // Initialize animation for new cards
                        initializePostCards();
                        
                        // Update pagination if it exists
                        const currentPagination = document.querySelector('.pagination-wrapper');
                        if (currentPagination && newPagination) {
                            currentPagination.innerHTML = newPagination.innerHTML;
                        } else if (currentPagination) {
                            currentPagination.remove();
                        }
                    } else {
                        // Show no posts message
                        postsGrid.innerHTML = `
                            <div class="no-posts-message">
                                <span class="material-icons-sharp">article</span>
                                <h3>No posts found</h3>
                                <p>There are no posts available in this category.</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    postsGrid.innerHTML = `
                        <div class="error-message">
                            <span class="material-icons-sharp">error</span>
                            <h3>Something went wrong</h3>
                            <p>Please try again later.</p>
                        </div>
                    `;
                });
        });
    });
}

// Loading skeleton HTML
function getLoadingSkeleton() {
    let skeleton = '';
    for (let i = 0; i < 6; i++) {
        skeleton += `
            <article class="post-card skeleton">
                <div class="post-image skeleton-image"></div>
                <div class="post-content">
                    <div class="skeleton-line" style="width: 80%;"></div>
                    <div class="skeleton-line" style="width: 90%;"></div>
                    <div class="skeleton-line" style="width: 60%;"></div>
                    <div class="skeleton-meta">
                        <div class="skeleton-line" style="width: 30%;"></div>
                        <div class="skeleton-line" style="width: 30%;"></div>
                    </div>
                </div>
            </article>
        `;
    }
    return skeleton;
}