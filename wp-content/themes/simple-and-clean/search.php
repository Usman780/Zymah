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
		
		<header class="entry-header">
			
			<h1 class="post-title"><?php printf( __( 'Search Results for: %s', 'simple' ), '<span class="querried-term">' . get_search_query() . '</span>' ); ?></h1>
				
		</header><!-- .entry-header -->
		
		<?php while ( have_posts() ) : the_post(); ?>
		
			<?php get_template_part( 'content', 'search' ); ?>
		
		<?php endwhile; ?>
		
	<?php else : ?>
	
		<?php get_template_part( 'content', 'none' ); ?>
	
	<?php endif; ?>
 	
 	<?php get_template_part( 'layouts/pagination' ); ?>
 	
 </div><!-- #content -->
 
 <?php get_sidebar(); ?>
 
 <?php get_footer(); ?>