/**
 * Parallax Effect Function
 * 
 * Applies parallax scrolling effects to elements with the .parallax class.
 * Uses Intersection Observer for performance optimization.
 * 
 * Usage:
 * Add the 'parallax' class to any element and customize with data attributes:
 * 
 * Basic usage:
 * <div class="parallax">Content</div>
 * 
 * With custom options:
 * <div class="parallax" 
 *      data-parallax-speed="0.3" 
 *      data-parallax-direction="up" 
 *      data-parallax-root="200px">
 *   Content
 * </div>
 * 
 * Data Attributes:
 * 
 * data-parallax-speed (default: 0.5)
 *   - Controls movement speed (0.1 = slow, 1.0 = fast)
 *   - Examples: "0.2", "0.5", "0.8"
 * 
 * data-parallax-direction (default: "up")
 *   - "up" = moves up as user scrolls down
 *   - "down" = moves down as user scrolls down  
 *   - "left" = moves left as user scrolls down
 *   - "right" = moves right as user scrolls down
 * 
 * data-parallax-root (default: "200px")
 *   - When to start parallax relative to viewport
 *   - "0px" = starts when element enters viewport
 *   - "200px" = starts 200px before element enters viewport
 *   - "-100px" = starts 100px after element enters viewport
 * 
 * Examples:
 * 
 * Slow upward parallax starting early:
 * <div class="parallax" data-parallax-speed="0.2" data-parallax-direction="up" data-parallax-root="300px">
 * 
 * Fast rightward parallax starting on viewport entry:
 * <div class="parallax" data-parallax-speed="0.8" data-parallax-direction="right" data-parallax-root="0px">
 * 
 * Gentle downward parallax with delayed start:
 * <div class="parallax" data-parallax-speed="0.3" data-parallax-direction="down" data-parallax-root="-50px">
 */
export function parallax() {
    const parallaxElements = document.querySelectorAll('.parallax');
    
    if (parallaxElements.length === 0) return;
    
    let activeElements = [];
    let elementStartPositions = new Map(); // Track when each element first becomes visible
    
    // Group elements by their custom root margin
    const observerConfigs = new Map();
    
    parallaxElements.forEach(element => {
        // Get custom root margin from data attribute or use default
        const customRootMargin = element.dataset.parallaxRoot || '200px';
        
        // Create a key for this configuration
        const configKey = customRootMargin;
        
        // Add element to the appropriate config group
        if (!observerConfigs.has(configKey)) {
            observerConfigs.set(configKey, {
                rootMargin: customRootMargin,
                elements: []
            });
        }
        observerConfigs.get(configKey).elements.push(element);
    });
    
    // Create observers for each unique root margin configuration
    observerConfigs.forEach((config) => {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                const element = entry.target;
                if (entry.isIntersecting) {
                    if (!activeElements.includes(element)) {
                        activeElements.push(element);
                        // Record the scroll position when this element first becomes active
                        if (!elementStartPositions.has(element)) {
                            elementStartPositions.set(element, window.pageYOffset);
                        }
                    }
                } else {
                    activeElements = activeElements.filter(el => el !== element);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: config.rootMargin
        });
        
        // Observe each element with this configuration
        config.elements.forEach(element => {
            observer.observe(element);
        });
    });
    
    function updateParallax() {
        if (activeElements.length === 0) return;
        
        const scrolled = window.pageYOffset;
        
        activeElements.forEach(element => {
            // Get parallax speed from data attribute or use default
            const speed = parseFloat(element.dataset.parallaxSpeed) || 0.5;
            
            // Get parallax direction from data attribute (default: 'up')
            const direction = element.dataset.parallaxDirection || 'up';
            
            // Get the scroll position when this element first became active
            const startPosition = elementStartPositions.get(element) || 0;
            
            // Calculate parallax relative to when the element first became visible
            const relativeScroll = scrolled - startPosition;
            
            // For absolutely positioned elements, we need to handle positioning differently
            const elementRect = element.getBoundingClientRect();
            const windowHeight = window.innerHeight;
            
            // Check if element is in viewport (visible)
            const isVisible = elementRect.top < windowHeight && elementRect.bottom > 0;
            
            if (isVisible && relativeScroll >= 0) {
                let xPos = 0;
                let yPos = 0;
                
                switch (direction) {
                    case 'down':
                        yPos = relativeScroll * speed;
                        break;
                    case 'up':
                        yPos = -(relativeScroll * speed);
                        break;
                    case 'left':
                        xPos = -(relativeScroll * speed);
                        break;
                    case 'right':
                        xPos = relativeScroll * speed;
                        break;
                    default:
                        yPos = -(relativeScroll * speed);
                        break;
                }
                
                element.style.transform = `translate3d(${xPos}px, ${yPos}px, 0)`;
            }
        });
    }
    
    // Throttle scroll events for better performance
    let ticking = false;
    function requestTick() {
        if (!ticking) {
            requestAnimationFrame(() => {
                updateParallax();
                ticking = false;
            });
            ticking = true;
        }
    }
    
    // Add scroll event listener
    window.addEventListener('scroll', requestTick);
    
    // Initialize parallax positions
    updateParallax();
}