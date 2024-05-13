<?php
/*
    Template Name: Cookie Statement
*/
?>

<?php get_header(); ?>

<?php $cookie_statement = get_field('cookie_statement', 'option'); ?>

<section class="section">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<h1><?php the_title(); ?></h1>
				<?php the_content(); ?>
			</div>
		</div>
	</div>
</section>

<section class="section">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<?= $cookie_statement; ?>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>