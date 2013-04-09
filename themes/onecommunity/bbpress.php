<?php get_header(); ?>

	<div id="content" class="bbpress-page">

	<div class="page-title"><?php the_title(); ?></div>

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

						<?php the_content(); ?>

			<?php endwhile; endif; ?>

	</div><!-- #content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>