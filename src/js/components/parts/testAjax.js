export function testAjax() {
    const block = document.querySelector('.ajax-example');

    if (!block) return;

    const container = block.querySelector('.ajax-container');
    const buttons = block.querySelectorAll('.ajax-button');
    let id = 0;

    function getAjaxData() {
        container.classList.add('loading');

        const formData = new FormData();
        formData.append('action', 'test_ajax');
        formData.append('id', id);

        fetch(customjs_ajax_object.ajax_url, {
            method: 'POST',
            body: formData,
        })
        .then(response => response.text())
        .then(html => {
            container.innerHTML = html;
            setTimeout(() => {
                container.classList.remove('loading');
            }, 500);
        })
        .catch(error => {
            console.error(error);
        });
    }

    buttons.forEach(button => {
        button.addEventListener('click', () => {
            id = button.dataset.id;
            getAjaxData();
        });
    });
}
