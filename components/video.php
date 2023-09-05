<?php
/**
 * Block Name: Video Block
 *
 */

$video_src = get_field('video_src');
$video_poster = get_field('video_poster');
$video_title = get_field('video_title');
$video_subtitle = get_field('video_subtitle');
$video_duration = get_field('video_duration');
$autoplay = get_field('autoplay');

// if video is youtube or vimeo embed, return the proper URL
$isEmbed = str_contains($video_src, 'youtube') || str_contains($video_src, 'youtu.be') || str_contains($video_src, 'vimeo')? true : false;
if(str_contains($video_src, 'youtu.be') || str_contains($video_src, 'youtube')){
    $video_src = returnYoutubeUrl($video_src);
}
?>

<section>
    <div class="video">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <?php if($isEmbed): ?>
                        <div class="video__container">
                            <iframe width="560" height="315" src="<?= $video_src; ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                        </div>
                    <?php else: ?>
                        <video poster="<?= $video_poster['url']; ?>" <? if ($autoplay) echo 'autoplay'; ?> controls playsinline preload="metadata">
                            <source src="<?= $video_src; ?>" type="video/mp4">
                        </video>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
