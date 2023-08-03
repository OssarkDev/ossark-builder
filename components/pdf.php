<?php
/**
 * Block Name: PDF
 *
 */

$title = get_field('title');
$pdf_icon = get_field('pdf_icon');
$download_icon = get_field('download_icon');
$documents = 'documents';
?>

<section>
	<div class="pdf">
		<div class="container">
			<div class="row">

				<?php if($title): ?>
					<div class="col-6">
						<div class="pdf__title">
							<h2><?php echo $title; ?></h2>
						</div>
					</div>
				<?php endif; ?>
				
				<?php
				if( have_rows($documents) ):
					while ( have_rows($documents) ) : the_row();
						$name = get_sub_field('name');
						$file = get_sub_field('file'); ?>
					<div class="col-12">
						<a href="<?= $file['url']; ?>" download="<?= $name; ?>" class="pdf__item">
							<div class="pdf__item__icon">
								<?= file_get_contents($pdf_icon['url']); ?>
							</div>
							<div class="pdf__item__title">
								<?= $name; ?>
							</div>
							<div class="pdf__item__download">
								<?= file_get_contents($download_icon['url']); ?>
							</div>
						</a>
					</div>
				<?php endwhile; endif; ?>
			</div>
		</div>
	</div>
</section>