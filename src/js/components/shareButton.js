export function shareButton() {
    let shareButton = document.querySelector('.share-button');

    if (shareButton) {
        shareButton.addEventListener('click', () => {
            let tempInput = document.createElement('textarea');

            tempInput.value = window.location.href;

            shareButton.parentNode.appendChild(tempInput);

            tempInput.select();
            tempInput.setSelectionRange(0, 99999);

            document.execCommand('copy');

            tempInput.parentNode.removeChild(tempInput);
        });
    }
}