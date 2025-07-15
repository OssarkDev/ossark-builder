export function scroll() {

    // data-call-scroll="functionName" to call a function when the section is in view. 
    // Class name is passed to the function where this exists
    // data-scroll-switch to remove the class when the section is not in view.

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
