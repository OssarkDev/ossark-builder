export function shareButton() {
	const button = document.querySelector('.share-button');

	if (button) {
		button.addEventListener('click', () => {
			const tempInput = document.createElement('textarea');

			tempInput.value = window.location.href;

			button.parentNode.appendChild(tempInput);

			tempInput.select();
			tempInput.setSelectionRange(0, 99999);

			document.execCommand('copy');

			tempInput.parentNode.removeChild(tempInput);
		});
	}
}
