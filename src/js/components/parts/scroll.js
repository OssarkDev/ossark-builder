export function scroll() {

    // data-call-scroll="functionName" to call a function when the section is in view. 
    // Class name is passed to the function where this exists

    const $mobileThreshold = 0.5;
    const $desktopThreshold = 0.2;
    const $mobileMargin = '40px';
    const $desktopMargin = '-200px';

    // Check if the device is a mobile device
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

    // Adjust the threshold and rootMargin based on the device type
    const threshold = isMobile ? $mobileThreshold : $desktopThreshold;
    const rootMargin = isMobile ? $mobileMargin : $desktopMargin;

    // Create an Intersection Observer instance
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
            // If the section is not in view remove the class
            // else {
            //     const section = entry.target;
            //     section.classList.remove('in-view');
            // }
        });
    }, {
        threshold: threshold,
        rootMargin: rootMargin,
        root: null
    });

    // Get all sections with the 'data-scroll' attribute
    const sections = document.querySelectorAll('[data-scroll]');

    // Observe each section
    sections.forEach((section) => {
        observer.observe(section);
    });
}
