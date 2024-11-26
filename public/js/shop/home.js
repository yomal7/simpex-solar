        // Category filtering
        const categoryButtons = document.querySelectorAll('.category-btn');
        const productCards = document.querySelectorAll('.product-card');
        let cartCount = 0;
        const cartCountElement = document.querySelector('.cart-count');

        categoryButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all buttons
                categoryButtons.forEach(btn => btn.classList.remove('active'));
                // Add active class to clicked button
                button.classList.add('active');

                const category = button.dataset.category;

                // Filter products
                productCards.forEach(card => {
                    if (category === 'all' || card.dataset.category === category) {
                        card.style.display = 'block';
                        card.style.opacity = '0';
                        card.style.transform = 'translateY(20px)';
                        // Trigger reflow
                        card.offsetHeight;
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                        card.style.transition = 'all 0.5s ease';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        // Add to cart functionality
        const addToCartButtons = document.querySelectorAll('.add-to-cart');
        addToCartButtons.forEach(button => {
            button.addEventListener('click', () => {
                cartCount++;
                cartCountElement.textContent = cartCount;
                
                // Animation for button
                button.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    button.style.transform = 'scale(1)';
                }, 100);

                // Optional: Show added to cart message
                const originalText = button.textContent;
                button.textContent = 'Added!';
                button.style.background = '#27ae60';
                setTimeout(() => {
                    button.textContent = originalText;
                    button.style.background = '';
                }, 1000);
            });
        });

        let currentSlide = 0;
        const slides = document.querySelector('.slides');
        const dots = document.querySelectorAll('.dot');
        const totalSlides = document.querySelectorAll('.slide').length;

        // Initialize slide position
        updateSlidePosition();

        // Next button click
        document.querySelector('.next').addEventListener('click', () => {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateSlidePosition();
        });

        // Previous button click
        document.querySelector('.prev').addEventListener('click', () => {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            updateSlidePosition();
        });

        // Dot navigation
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                currentSlide = index;
                updateSlidePosition();
            });
        });

        // Auto slide
        setInterval(() => {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateSlidePosition();
        }, 5000); // Change slide every 5 seconds

        function updateSlidePosition() {
            slides.style.transform = `translateX(-${currentSlide * 33.333}%)`;
            
            // Update dots
            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentSlide);
            });

            // Reset and trigger animations for current slide content
            const currentSlideContent = document.querySelectorAll('.slide-content')[currentSlide];
            const elements = currentSlideContent.children;
            
            for(let element of elements) {
                element.style.animation = 'none';
                element.offsetHeight; // Trigger reflow
                element.style.animation = null;
            }
        }