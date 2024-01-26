<?php
/*
    Template Name: Cookie Statement
*/
?>

<?php get_header(); ?>

<div class="section">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<h1><?php the_title(); ?></h1>
				<?php the_content(); ?>
			</div>
		</div>
	</div>
</div>

<div class="section">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<script id="CookieDeclaration" src="https://consent.cookiebot.com/db63ed26-4479-4dec-aa7f-7094a41ed5fe/cd.js" type="text/javascript" async></script>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>