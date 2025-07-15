export function activeMenuItem() {
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.header__nav__list a');
    
    // Remove any existing active classes first
    document.querySelectorAll('.header__nav__list__item.active')
        .forEach(item => item.classList.remove('active'));
    
    // Find the best matching link
    let bestMatch = null;
    let longestMatch = 0;
    
    navLinks.forEach(link => {
        const linkPath = new URL(link.href).pathname;
        
        // Check for exact match first
        if (currentPath === linkPath) {
            bestMatch = link;
            longestMatch = linkPath.length;
        }
        // Check for partial match (current path starts with link path)
        else if (currentPath.startsWith(linkPath) && linkPath.length > 1 && linkPath.length > longestMatch) {
            bestMatch = link;
            longestMatch = linkPath.length;
        }
    });
    
    // Add active class to the best match
    if (bestMatch) {
        const listItem = bestMatch.closest('.header__nav__list__item');
        if (listItem) {
            listItem.classList.add('active');
        }
    }
}
