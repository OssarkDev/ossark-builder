/**
 * Scroll Intersection Observer
 * 
 * This function automatically detects when elements come into view and adds the 'in-view' class.
 * It can also trigger custom functions when elements become visible.
 * 
 * BASIC USAGE:
 * Add the data-scroll attribute to any element you want to observe:
 * <section data-scroll>Content here</section>
 * 
 * When the element comes into view, it will automatically get the 'in-view' class added:
 * <section data-scroll class="in-view">Content here</section>
 * 
 * CALLING CUSTOM FUNCTIONS:
 * To trigger a custom function when an element comes into view, add data-scroll-call:
 * <section data-scroll data-scroll-call="myFunction">Content here</section>
 * 
 * Your function must be available on the window object:
 * window.myFunction = function(sectionClass) {
 *     console.log('Element is in view!', sectionClass);
 * };
 * 
 * The function receives the element's class attribute as a parameter.
 * 
 * IMPORTANT: MAKING FUNCTIONS AVAILABLE
 * For data-scroll-call to work, your function MUST be attached to the window object:
 * 
 * ✅ CORRECT:
 * window.myFunction = function(sectionClass) { ... };
 * window.processAnimation = () => { ... };
 * 
 * ❌ INCORRECT:
 * function myFunction() { ... }  // Not accessible to scroll.js
 * const myFunction = () => { ... };  // Not accessible to scroll.js
 * 
 * Example in your component file:
 * // Make your animation function globally available
 * window.processAnimation = startStepAnimation;
 * 
 * EXAMPLES:
 * 
 * 1. Simple animation trigger:
 * <div data-scroll class="fade-in">Animated content</div>
 * 
 * 2. Custom function call:
 * <section data-scroll data-scroll-call="processAnimation" class="process">Process steps</section>
 * 
 * 
 * 3. Multiple animations:
 * <div data-scroll data-scroll-call="slideIn" class="slider">Slider content</div>
 * 
 * RESPONSIVE BEHAVIOR:
 * - Mobile devices: 50% threshold, 40px margin
 * - Desktop devices: 20% threshold, -200px margin
 * 
 * This means on mobile, 50% of the element must be visible before triggering.
 * On desktop, only 20% needs to be visible, and it triggers 200px before entering viewport.
 */

export function scroll() {

    const $mobileThreshold = 0.5;
    const $laptopThreshold = 0.2;
    const $mobileMargin = '40px';
    const $laptopMargin = '-200px';

    // Check if the device is a mobile device
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

    // Default threshold and rootMargin based on the device type
    const defaultThreshold = isMobile ? $mobileThreshold : $laptopThreshold;
    const defaultRootMargin = isMobile ? $mobileMargin : $laptopMargin;

    // Get all sections with the 'data-scroll' attribute
    const sections = document.querySelectorAll('[data-scroll]');

    // Group sections by their custom threshold and rootMargin values
    const observerConfigs = new Map();

    sections.forEach((section) => {
        // Check for custom offset on the section
        const customOffset = section.getAttribute('data-scroll-offset');
        
        // Use custom offset to adjust rootMargin if provided, otherwise use defaults
        let threshold = defaultThreshold;
        let rootMargin = defaultRootMargin;
        
        if (customOffset) {
            // Convert offset to rootMargin (positive offset = trigger earlier = positive margin)
            rootMargin = `${customOffset}px`;
        }
        
        // Create a key for this configuration
        const configKey = `${threshold}-${rootMargin}`;
        
        // Add section to the appropriate config group
        if (!observerConfigs.has(configKey)) {
            observerConfigs.set(configKey, {
                threshold: threshold,
                rootMargin: rootMargin,
                sections: []
            });
        }
        observerConfigs.get(configKey).sections.push(section);
    });

    // Create observers for each unique configuration
    observerConfigs.forEach((config) => {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    const section = entry.target;
                    section.classList.add('in-view');
                    let dataCall = section.getAttribute('data-scroll-call');
                    let sectionClass = section.getAttribute('class'); // set class name as a variable to pass to the function
                    if (dataCall) {
                        window[dataCall](sectionClass);
                    }
                }
                // If the section is not in view, remove the class only if data-scroll-switch is present
                else {
                    const section = entry.target;
                    if (section.hasAttribute('data-scroll-switch')) {
                        section.classList.remove('in-view');
                    }
                }
            });
        }, {
            threshold: config.threshold,
            rootMargin: config.rootMargin,
            root: null
        });

        // Observe each section with this configuration
        config.sections.forEach((section) => {
            observer.observe(section);
        });
    });
}
