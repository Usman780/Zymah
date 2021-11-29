<?php
/**
 * The custom css generator.
 *
 * This file takes the custom styling from
 * the theme options page and adds it to the
 * header.php file. 
 *
 * @package 	Simple
 * @since		1.0.0
 */


add_action( 'wp_head', 'organizedthemes_custom_css_hook' );

if ( ! function_exists( 'organizedthemes_custom_css_hook' ) ):

	function organizedthemes_custom_css_hook( ) {
	   
	?>
 
<style type='text/css'>
	
	<?php 
		
		if (of_get_option('disable_fonts', $single = true) != "" ) {
		
			$output = '';
			$input = '';
			
			if ( of_get_option( 'body_font' ) ) {
				$output .= options_typography_font_styles( of_get_option( 'body_font' ) , 'body');
			}
			
			if ( of_get_option( 'site_title_font' ) ) {
				$output .= options_typography_font_styles( of_get_option( 'site_title_font' ) , 'h1.text-logo');
			}
			if ( of_get_option( 'heading_font' ) ) {
				$output .= options_typography_font_styles( of_get_option( 'heading_font' ) , 'h1, h2, h3, h4, h5, h6');
			}

			if ( of_get_option( 'navigation' ) ) {
				$output .= options_typography_font_styles( of_get_option( 'navigation' ) , 'nav#main-menu ul li');
			}
	
			if ( of_get_option( 'widget_title_font' ) ) {
				$output .= options_typography_font_styles( of_get_option( 'widget_title_font' ) , 'h3.widget-title');
			}
					
			if ( $output != '' ) {
				$output =  $output ;
				echo $output;
			}
		
		}
	
	 ?>
	    

	<?php if(of_get_option('logo_color', $single = true) != ""){ ?>
		.text-logo a, .text-logo a:visited { color: <?php echo of_get_option('logo_color', '' ); ?>; }
	<?php } ?>
	<?php if(of_get_option('logo_color_hover', $single = true) != ""){ ?>
		.text-logo a:hover { color: <?php echo of_get_option('logo_color_hover', '' ); ?>; }
	<?php } ?>

	<?php if(of_get_option('link_color', $single = true) != ""){ ?>
		a, a:visited {color: <?php echo of_get_option('link_color', '' ); ?> }
	<?php } ?>
	<?php if(of_get_option('link_color_hover', $single = true) != ""){ ?>
		a:hover {color: <?php echo of_get_option('link_color_hover', '' ); ?> }
	<?php } ?>
	<?php if(of_get_option('navigation_background_color', $single = true) != ""){ ?>
		#header,
		nav#main-menu li.current-menu-item li a,
		nav#main-menu li ul { background-color: <?php echo of_get_option('navigation_background_color', '' ); ?> }
	<?php } ?>	
	<?php if(of_get_option('navigation_link_color', $single = true) != ""){ ?>
		nav#main-menu ul li a,
		nav#main-menu ul li a:visited { color: <?php echo of_get_option('navigation_link_color', '' ); ?> }
	<?php } ?>
	<?php if(of_get_option('navigation_link_color_hover', $single = true) != ""){ ?>
		nav#main-menu ul li.current-menu-item a,
		nav#main-menu ul li.current-menu-item a:visited,
		nav#main-menu li:hover,
		nav#main-menu ul li a:hover,
		nav#main-menu li:hover.current-menu-item,
		nav#main-menu ul li.current-menu-item li a:hover { color: <?php echo of_get_option('navigation_link_color_hover', '' ); ?> }
	<?php } ?>
	<?php if(of_get_option('navigation_link_color_hover_background', $single = true) != ""){ ?>
		nav#main-menu li.current-menu-item a,
		nav#main-menu li:hover,
		nav#main-menu li a:hover,
		nav#main-menu li:hover.current-menu-item,
		nav#main-menu li.current-menu-item li a:hover { background-color: <?php echo of_get_option('navigation_link_color_hover_background', '' ); ?> }
	<?php } ?>
	<?php if(of_get_option('heading_color', $single = true) != ""){ ?>
		h1, h2, h3, h4, h5, h6 { color: <?php echo of_get_option('heading_color', '' ); ?> }
	<?php } ?>
	<?php if(of_get_option('content_background', $single = true) != ""){ ?>
		#content { background-color: <?php echo of_get_option('content_background', '' ); ?> }
	<?php } ?>
	<?php if(of_get_option('content_text', $single = true) != ""){ ?>
		#content { color: <?php echo of_get_option('content_text', '' ); ?> }
	<?php } ?>
	<?php if(of_get_option('button', $single = true) != ""){ ?>
		a.button,
		input.button,
		input[type="button"],
		input[type="submit"] { color: <?php echo of_get_option('button', '' ); ?>; }
	<?php } ?>
	<?php if(of_get_option('button_background', $single = true) != ""){ ?>
		a.button,
		input.button,
		input[type="button"],
		input[type="submit"] { background-color: <?php echo of_get_option('button_background', '' ); ?>; }
	<?php } ?>
	<?php if(of_get_option('button_hover', $single = true) != ""){ ?>
		a:hover.button,
		input:hover.button,
		input:hover[type="button"],
		input:hover[type="submit"]  { color: <?php echo of_get_option('button_hover', '' ); ?>; }
	<?php } ?>
	<?php if(of_get_option('button_background_hover', $single = true) != ""){ ?>
		a:hover.button,
		input:hover.button,
		input:hover[type="button"],
		input:hover[type="submit"]  { background-color: <?php echo of_get_option('button_background_hover', '' ); ?>; }
	<?php } ?>
	<?php if(of_get_option('widget_title', $single = true) != ""){ ?>
		h3.widget-title { color: <?php echo of_get_option('widget_title', '' ); ?>; border-color: <?php echo of_get_option('widget_title', '' ); ?>; }
	<?php } ?>
	<?php if(of_get_option('widget_text', $single = true) != ""){ ?>
		.widget { color: <?php echo of_get_option('widget_text', '' ); ?>; }
	<?php } ?>
	<?php if(of_get_option('widget_background', $single = true) != ""){ ?>
		aside.widget { background-color: <?php echo of_get_option('widget_background', '' ); ?>; }
	<?php } ?>
	<?php if(of_get_option('footer_color', $single = true) != ""){ ?>
		footer#primary, footer#primary p, footer#primary a, footer#primary a:visited { color: <?php echo of_get_option('footer_color', '' ); ?> }
	<?php } ?>
	<?php if(of_get_option('footer_background', $single = true) != ""){ ?>
		footer#primary { background-color: <?php echo of_get_option('footer_background', '' ); ?> }
	<?php } ?>	
	<?php if(of_get_option('custom_css', $single = true) != ""){ ?>
		<?php echo of_get_option('custom_css', '' ); ?>
	<?php } ?>

</style>
 
<?php
}
	
endif; // organizedthemes_custom_css_hook