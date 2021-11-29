<?php 
/**
 * Content Not Found
 *
 * This is the content that is returned when WordPress can't find
 * what you're looking for
 *
 * @package WordPress
 * @package Simple
 * @since   1.0.0
 */
?><article id="not-found">
	
	<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
		
		<h1><?php _e( 'Start Adding Content', 'simple' ); ?></h1>
		
		<p><?php printf( __( 'It looks like the site needs some content. <a href="%1$s">Get started here</a>.', 'simple' ), admin_url( 'post-new.php' ) ); ?></p>
	
	<?php else : ?>
		
		<h1><?php _e( 'Not Found', 'simple' ); ?></h1>
		
		<p><?php _e( 'We could not find the content you\'re looking for.  You can try searching for it here.', 'simple' ); ?></p>
		
		<?php get_search_form(); ?>
	
	<?php endif; ?>

</article><!-- #not-found -->