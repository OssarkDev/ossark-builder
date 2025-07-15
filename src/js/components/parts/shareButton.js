export function shareButton() {
	const buttons = document.querySelectorAll('.share-button');

	buttons.forEach(button => {
		button.addEventListener('click', async () => {
			// Get share data from button attributes or defaults
			const shareData = {
				title: button.dataset.title || document.title || 'Check this out!',
				text: button.dataset.text || document.querySelector('meta[name="description"]')?.content || 'I thought you might find this interesting',
				url: button.dataset.url || window.location.href
			};

			// Check if Web Share API is supported
			if (navigator.share && (!navigator.canShare || navigator.canShare(shareData))) {
				try {
					await navigator.share(shareData);
				} catch (error) {
					// User cancelled sharing or error occurred
					if (error.name !== 'AbortError') {
						console.log('Error sharing:', error);
						fallbackCopy(shareData.url, button);
					}
				}
			} else {
				// Fallback to clipboard copy for unsupported browsers
				fallbackCopy(shareData.url, button);
			}
		});
	});
}

// Fallback function for browsers that don't support Web Share API
function fallbackCopy(url, button) {
	// Try modern clipboard API first
	if (navigator.clipboard && window.isSecureContext) {
		navigator.clipboard.writeText(url).then(() => {
			showFeedback(button, 'Link copied to clipboard!');
		}).catch(() => {
			// Fallback to the old method
			legacyCopy(url, button);
		});
	} else {
		legacyCopy(url, button);
	}
}

// Legacy copy method for older browsers
function legacyCopy(url, button) {
	const tempInput = document.createElement('textarea');
	tempInput.value = url;
	tempInput.style.position = 'absolute';
	tempInput.style.left = '-9999px';
	
	document.body.appendChild(tempInput);
	tempInput.select();
	tempInput.setSelectionRange(0, 99999);
	
	try {
		document.execCommand('copy');
		showFeedback(button, 'Link copied to clipboard!');
	} catch (error) {
		showFeedback(button, 'Unable to copy link');
	}
	
	document.body.removeChild(tempInput);
}

// Show user feedback
function showFeedback(button, message) {
	// Create or update feedback element
	let feedback = button.querySelector('.share-feedback') || document.createElement('span');
	
	if (!button.querySelector('.share-feedback')) {
		feedback.className = 'share-feedback';
		feedback.style.cssText = `
			position: absolute;
			background: #333;
			color: white;
			padding: 4px 8px;
			border-radius: 4px;
			font-size: 12px;
			white-space: nowrap;
			z-index: 1000;
			opacity: 0;
			transition: opacity 0.3s ease;
			pointer-events: none;
			top: -30px;
			left: 50%;
			transform: translateX(-50%);
		`;
		
		// Ensure button has relative positioning for absolute feedback
		const buttonStyle = window.getComputedStyle(button);
		if (buttonStyle.position === 'static') {
			button.style.position = 'relative';
		}
		
		button.appendChild(feedback);
	}
	
	feedback.textContent = message;
	feedback.style.opacity = '1';
	
	// Hide feedback after 2 seconds
	setTimeout(() => {
		feedback.style.opacity = '0';
	}, 2000);
}
