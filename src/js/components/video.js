export function video() {
	var playButton = document.getElementById("play_button");
	var videoClick = document.getElementById("video");

	var videoPlayButton = function(event) {
	if (video.paused == true) {
		// Play the video
		video.play();
		// hide the play button
		playButton.classList.toggle('hide-button');
	} else {
		// Pause the video
		video.pause();
		// show the play button
		playButton.classList.toggle('hide-button');
	}
	};

		//keep at the end

	//check is it's mobile/touch instead of click
	// remove thse extra if statements after you have this on DOM load
	if('ontouchstart' in window) {
		if(videoClick) {
		videoClick.addEventListener("touchstart", videoPlayButton, false);
		}
		if(playButton){
		playButton.addEventListener("touchstart", videoPlayButton, false);
		}
	} else {
		if(playButton){
		playButton.addEventListener("click", videoPlayButton, false);
		}
		if(videoClick){
		videoClick.addEventListener("click", videoPlayButton, false);
		}
	}
}