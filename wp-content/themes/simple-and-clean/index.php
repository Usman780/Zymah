<?php 
/**
 * Index template
 *
 * Sets the display of our default index file
 *
 * @package WordPress
 * @package Simple
 * @since   1.0.0
 */
 
 get_header(); ?>
 
 <div id="content" class="clearfix" role="main">
 	
	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : the_post(); ?>
		
			<?php get_template_part( 'content', get_post_format() ); ?>
		
		<?php endwhile; ?>
		
	<?php else : ?>
	
		<?php get_template_part( 'content', 'none' ); ?>
	
	<?php endif; ?>
 	
 	<?php get_template_part( 'layouts/pagination' ); ?>
 	
 </div><!-- #content -->
 
 <?php get_sidebar(); ?>
 
 <?php get_footer(); ?>