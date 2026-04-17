document.querySelectorAll('.accordion-header').forEach(item => {
    item.addEventListener('click', () => {
        const content = item.nextElementSibling;

        content.style.display =
            content.style.display === 'block' ? 'none' : 'block';
    });
});