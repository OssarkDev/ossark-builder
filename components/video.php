<?php
/**
 * Block Name: Video Block
 *
 */
?>

<?php 
$video_link = get_field('video_link');
$image = get_field('poster');
$play_button = get_field('play_button', 'option');
?>
<section class="section">
    <div class="video-block">
        <!-- video -->
        <div class="video-block__video">
            <?php if($video_link): ?>
                <div class="video-block__video__container col-12">
                    <video
                        id="video"
                        preload="metadata"
                        poster="<?php echo $image['url']; ?>"
                        width="100%"
                        controls
                        onclick = "this.play()"
                        class = "video-block__video__container__video"
                        >
                        <source
                        src="<?php echo $video_link; ?>"
                        type="video/mp4"
                        >
                    </video>
                    <button class="video-block__play-button" type="button" id="play_button">
                        <img src="<?= $play_button; ?>" alt="img">
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
