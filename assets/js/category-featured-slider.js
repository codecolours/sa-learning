export function initCategoryFeaturedSlider() {
    const sliderWrapper = document.querySelector('.category-slider-wrapper');
    
    if (!sliderWrapper) {
        return;
    }

    const container = sliderWrapper.querySelector('.category-slider-container');
    const flexContainer = sliderWrapper.querySelector('.flex-container');
    const items = Array.from(flexContainer.querySelectorAll('.featured-item'));
    const prevBtn = sliderWrapper.querySelector('.slider-prev');
    const nextBtn = sliderWrapper.querySelector('.slider-next');
    
    if (items.length === 0) {
        return;
    }
    
    let currentIndex = 0;
    let itemsPerView = 4;
    
    function getSlidesPerView() {
        const width = window.innerWidth;
        if (width < 768) return 1;
        if (width < 992) return 2;
        if (width < 1300) return 3;
        return 4;
    }
    
    function isMobile() {
        return window.innerWidth < 768;
    }
    
    function updateSlider() {
        itemsPerView = getSlidesPerView();
        
        if (isMobile()) {
            // Mobile: show current, previous, and next with transforms
            const gap = 10;
            
            items.forEach((item, index) => {
                item.style.display = 'flex';
                
                const diff = index - currentIndex;
                const adjustedDiff = diff > items.length / 2 ? diff - items.length : diff < -items.length / 2 ? diff + items.length : diff;
                
                if (adjustedDiff === 0) {
                    // Current slide - centered
                    item.style.transform = 'translateX(0)';
                    item.style.opacity = '1';
                    item.style.zIndex = '2';
                    item.style.pointerEvents = 'auto';
                } else if (adjustedDiff === -1) {
                    // Previous slide - left side with gap
                    item.style.transform = `translateX(calc(-100% - ${gap}px))`;
                    item.style.opacity = '0.6';
                    item.style.zIndex = '1';
                    item.style.pointerEvents = 'none';
                } else if (adjustedDiff === 1) {
                    // Next slide - right side with gap
                    item.style.transform = `translateX(calc(100% + ${gap}px))`;
                    item.style.opacity = '0.6';
                    item.style.zIndex = '1';
                    item.style.pointerEvents = 'none';
                } else {
                    // Hide others
                    item.style.transform = 'translateX(0)';
                    item.style.opacity = '0';
                    item.style.zIndex = '0';
                    item.style.pointerEvents = 'none';
                }
            });
        } else {
            // Desktop: hide/show based on items per view
            items.forEach(item => {
                item.style.display = 'none';
                item.style.transform = '';
                item.style.opacity = '';
                item.style.zIndex = '';
                item.style.pointerEvents = '';
            });
            
            // Show items for current view
            for (let i = 0; i < itemsPerView; i++) {
                const itemIndex = (currentIndex + i) % items.length;
                items[itemIndex].style.display = 'flex';
            }
        }
        
        // Always enable both buttons for infinite scrolling
        prevBtn.disabled = false;
        nextBtn.disabled = false;
    }
    
    function goToNext() {
        currentIndex = (currentIndex + 1) % items.length;
        updateSlider();
    }
    
    function goToPrev() {
        currentIndex = (currentIndex - 1 + items.length) % items.length;
        updateSlider();
    }
    
    prevBtn.addEventListener('click', goToPrev);
    nextBtn.addEventListener('click', goToNext);
    
    // Touch/Swipe functionality
    let touchStartX = 0;
    let touchEndX = 0;
    let isDragging = false;
    
    function handleSwipe() {
        const swipeThreshold = 50; // minimum distance for a swipe
        const diff = touchStartX - touchEndX;
        
        if (Math.abs(diff) > swipeThreshold) {
            if (diff > 0) {
                // Swiped left - go to next
                goToNext();
            } else {
                // Swiped right - go to previous
                goToPrev();
            }
        }
    }
    
    // Touch events for mobile
    container.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });
    
    container.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    }, { passive: true });
    
    // Mouse events for desktop
    container.addEventListener('mousedown', (e) => {
        isDragging = true;
        touchStartX = e.screenX;
        container.style.cursor = 'grabbing';
    });
    
    container.addEventListener('mousemove', (e) => {
        if (!isDragging) return;
    });
    
    container.addEventListener('mouseup', (e) => {
        if (!isDragging) return;
        isDragging = false;
        touchEndX = e.screenX;
        container.style.cursor = 'grab';
        handleSwipe();
    });
    
    container.addEventListener('mouseleave', () => {
        if (isDragging) {
            isDragging = false;
            container.style.cursor = 'grab';
        }
    });
    
    // Set cursor style
    container.style.cursor = 'grab';
    
    // Handle window resize
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            const oldItemsPerView = itemsPerView;
            const newItemsPerView = getSlidesPerView();
            
            if (oldItemsPerView !== newItemsPerView) {
                updateSlider();
            }
        }, 250);
    });
    
    // Initialize
    updateSlider();
}
