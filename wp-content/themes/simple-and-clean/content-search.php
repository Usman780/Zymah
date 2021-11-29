<?php 
/**
 * Default Content
 *
 * This file is used to display content that isn't a video or image format
 *
 * @package WordPress
 * @package Simple
 * @since   1.0.0
 */
?><article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		
		<h2 class="post-title">
	
			<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
	
		</h2>

		<?php get_template_part( 'layouts/post-meta' ); ?>
		
	</header><!-- .entry-header -->
	
	<div class="entry-content">
	
		<?php the_excerpt(); ?>
		
		<p class="more-tag"><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'simple' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php _e('Read More', 'simple'); ?></a></p>
				
		
	</div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->