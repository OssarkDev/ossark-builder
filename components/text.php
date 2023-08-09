<?php
/**
 * Block Name: Text
 *
 */

$title = get_field('title');
$content = get_field('content');
?>

<section class="text">
	<div class="container">
		<div class="row">
			<div class="col-6">
				<div class="text__title">
					<h2>
						<?= $title; ?>
					</h2>
				</div>
			</div>
			<div class="col-8">
				<div class="text__content">
					<?= $content ?>
				</div>
			</div>
		</div>
	</div>
</section>