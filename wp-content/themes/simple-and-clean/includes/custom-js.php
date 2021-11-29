<?php

/**
 * Sets up and fires our javascript.  It is loaded
 * by wp_footer in footer.php
 *
 *
 * @package WordPress
 * @subpackage Simple
 * @since 1.0.0
 *
 *
 */

	add_action( 'wp_footer', 'organizedthemes_custom_js_hook', 20 );
	
	function organizedthemes_custom_js_hook( ) {   

?>

<script type="text/javascript">

	// Tinynav
		jQuery(function(){
			jQuery("#top-menu").tinyNav({
			  active: 'selected',
			  header: '<?php echo of_get_option('mobile_navigation_name','Menu') ?>'
			});
		});
			
</script>



<?php
}