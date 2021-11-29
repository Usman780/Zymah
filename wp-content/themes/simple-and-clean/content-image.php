<?php 
/**
 * Image Format
 *
 * This file controls how the individual image posts are displayed
 *
 * @package WordPress
 * @package Simple
 * @since   1.0.0
 */
?><article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">

		<div class="entry-thumbnail">
		
			<?php the_post_thumbnail(); ?>
		
		</div>

		<?php if ( is_single() ) : ?>
			
			<h1 class="post-title"><?php the_title(); ?></h1>
		
		<?php else : ?>
		
			<h2 class="post-title">
		
				<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
		
			</h2>
		
		<?php endif; // is_single() ?>

		<?php get_template_part( 'layouts/post-meta' ); ?>
		
	</header><!-- .entry-header -->
	
	<div class="entry-content">
	
		<?php if ( is_single() || is_page() ) : ?>
		
			<?php the_content(__('<span class="more-link">Read more</span>', 'simple')); ?>
			 
			<?php get_template_part( 'layouts/page-pagination' ); ?>
		
		<?php else : ?>	
		
			<?php if (of_get_option('content_excerpt') == 'excerpt') : ?>
			
				<?php the_excerpt(); ?>
				
				<p class="more-tag"><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'simple' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php _e('Read More', 'simple'); ?></a></p>
				
			<?php else : ?>
			
				<?php if ( ! has_excerpt() ) : ?>
				
				     <?php the_content(__('<span class="more-tag">Read more</span>', 'simple')); ?>
				      
				     <?php get_template_part( 'layouts/page-pagination' ); ?>
				      
				<?php else : ?>
				 
				    <?php the_excerpt(); ?>
				      
				    <p class="more-tag"><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'simple' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php _e('Read More', 'simple'); ?></a></p>
				
				<?php endif; ?>
			
			<?php endif; // of_get_option('content_excerpt') ?>
		
		<?php endif; // is_single() ?>
		
	</div><!-- .entry-content -->
	
	<footer class="entry-meta">

		<?php if ( is_single() && get_the_author_meta( 'description' ) ) : ?>
		
			<?php get_template_part( 'layouts/author-bio' ); ?>
		
		<?php endif; ?>
	
	</footer><!-- .entry-meta -->

</article><!-- #post-<?php the_ID(); ?> -->