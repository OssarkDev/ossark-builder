// FAQ function to toggle active class on click, and close all other items

export function faq() {
    const items = document.querySelectorAll('.faq__list__item');

    items.forEach(item => {
        item.addEventListener('click', () => {
            item.classList.toggle('active');
            items.forEach(otherItem => {
                if (otherItem !== item) {
                    otherItem.classList.remove('active');
                }
            });
        });
    });
}