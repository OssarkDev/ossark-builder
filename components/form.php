<?php
/**
 * Block Name: Form
 *
 */

$title = get_field('title');
$subtitle = get_field('subtitle');
$form = get_field('form');
$contact_info = get_field('contact_info');
$add_social = get_field('add_socials');
?>

<?php if($form): ?>
	<section>
		<div class="form">
			<div class="container">
				<div class="row">
					<div class="col-8">
						<div class="form__container">
							<h2 class="form__title"><?php echo $title; ?></h2>
							<p class="form__subtitle"><?php echo $subtitle; ?></p>
							<?php echo $form; ?>
						</div>
					</div>
					<div class="col-4">
						<div class="form__contact-info">
							<?php echo $contact_info; ?>
						</div>
						<?php if($add_social): ?>
							<div class="form__social">
								<div class="form__social__title">Follow us</div>
								<?php $social = get_field('social', 'option'); ?>
								<?php if($social): ?>
									<?php foreach($social as $item): ?>
										<?php $icon = $item['icon']; ?>
										<?php $link = $item['link']; ?>
										<a href="<?= $link['url']; ?>" target="_blank" class="form__social__icon">
											<?php  echo file_get_contents($icon['url']); ?>
										</a>
									<?php endforeach; ?>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>