<?php
/**
 * Block Name: Image Text
 *
 */

 $image = get_field('image');
 $title = get_field('title');
 $content = get_field('content');
 $cta = get_field('cta');
 $positioning = get_field('positioning');

 ?>

 <section class="image-text">
	<div class="container image-text__container">
		<div class="row image-text__row <?php if($positioning) {echo 'invert';}; ?>">
			<div class="col-6">
				<div class="image-text__inner">
					<div class="image-text__title">
						<h2><?php echo $title; ?></h2>
					</div>
					<div class="image-text__content">
						<?php echo $content; ?>
					</div>
					<div class="image-text__cta">
						<a href="<?php echo $cta['url']; ?>" class="btn"><?php echo $cta['title']; ?></a>
					</div>
				</div>
			</div>
			<div class="col-6">
				<div class="image-text__image">
					<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
				</div>
			</div>
		</div>
	</div>
 </section>