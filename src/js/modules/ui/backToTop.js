export function backToTop() {
    const backToTopButton = document.querySelector('.back-to-top-button');
    
    // Early return if button doesn't exist
    if (!backToTopButton) return;
    
    // Smooth scroll to top
    const scrollToTop = (e) => {
        e.preventDefault();
        
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    };
    
    // Event listener
    backToTopButton.addEventListener('click', scrollToTop);
}
