<?php

/**
 * Creates our sidebar area and footer information
 *
 *
 * @package WordPress
 * @subpackage Simple
 * @since 1.0.0
 *
 *
 */
?><div id="sidebar">

	<?php dynamic_sidebar('sidebar') ?>
	
	<footer id="primary" class="clearfix">
		
		<?php if ( of_get_option('footer_text', $single = true) != "" ) : ?>
		
			<p><?php echo of_get_option('footer_text'); ?></p>
			
		<?php else : ?>
		
			<p>
				<a href="<?php echo home_url(); ?>/">&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></a><br />
				<a rel="nofollow" href="http://www.organizedthemes.com" title="Another great theme from Organized Themes">Organized Themes</a>
			</p> 
		
		<?php endif; ?>
		
	</footer><!-- footer#primary -->
	
</div><!-- #sidebar -->