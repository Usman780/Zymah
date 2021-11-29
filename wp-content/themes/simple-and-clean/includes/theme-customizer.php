<?php

/**
 * This file attaches the theme customizer to
 * the options in the options framework
 *
 * @package WordPress
 * @subpackage Simple
 * @since 1.0.0
 *
 */


add_action( 'customize_register', 'organizedthemes_customizer_register' );

function organizedthemes_customizer_register($wp_customize) {


	// Change section names
		$wp_customize->get_section('title_tagline')->title = __( 'Logo', 'simple' );
		$wp_customize->get_section('colors')->title = __( 'Background Color', 'simple' );
	
	// Remove tagline field
		$wp_customize->remove_control('blogdescription');
	

	
	/**
	 * This is optional, but if you want to reuse some of the defaults
	 * or values you already have built in the options panel, you
	 * can load them into $options for easy reference
	 */
	 
	$options = optionsframework_options();
	

// Title & Tagline
		
		$wp_customize->add_setting( 'simple[logo]', array(
			'type' => 'option',
			'sanitize_callback' => 'absint'
		) );
		
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'logo', array(
			'label' => $options['logo']['name'],
			'section' => 'title_tagline',
			'settings' => 'simple[logo]',
			'priority' => 2
		) ) );
	
	// Title font face
		$wp_customize->add_setting( 'simple[site_title_font][face]', array(
			'default' => $options['site_title_font']['std']['face'],
			'type' => 'option',
			'sanitize_callback' => 'absint'
		) );
		
		$wp_customize->add_control( 'site_title_font', array(
				'label' => $options['site_title_font']['name'],
				'section' => 'title_tagline',
				'settings' => 'simple[site_title_font][face]',
				'type' => 'select',
				'choices' => $options['site_title_font']['options']['faces'],
				'priority' => 3
		) );
	

	
	// Title Color
		$wp_customize->add_setting( 'simple[logo_color]', array(
			'default' => $options['logo_color']['std'],
			'type' => 'option',
			'sanitize_callback' => 'sanitize_hex_color'
		) );
		
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'logo_color', array(
			'label'   => $options['logo_color']['name'],
			'section' => 'title_tagline',
			'settings'   => 'simple[logo_color]',
			'priority' => 5,
			'sanitize_callback' => 'sanitize_hex_color'
		) ) );
	
	// Title Color Hover
		$wp_customize->add_setting( 'simple[logo_color_hover]', array(
			'default' => $options['logo_color_hover']['std'],
			'type' => 'option',
			'sanitize_callback' => 'sanitize_hex_color'
		) );
		
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'logo_color_hover', array(
			'label'   => $options['logo_color_hover']['name'],
			'section' => 'title_tagline',
			'settings'   => 'simple[logo_color_hover]',
			'priority' => 6
		) ) );
			


	
	
// Content
	
	$wp_customize->add_section( 'organizedthemes_content', array(
		'title' => __( 'Content', 'organizedthemes' ),
		'priority' => 30
	) );
	
		// Body font face
			$wp_customize->add_setting( 'simple[body_font][face]', array(
				'default' => $options['body_font']['std']['face'],
				'type' => 'option',
				'sanitize_callback' => 'absint'
			) );
			
			$wp_customize->add_control( 'body_font', array(
					'label' => $options['body_font']['name'],
					'section' => 'organizedthemes_content',
					'settings' => 'simple[body_font][face]',
					'type' => 'select',
					'choices' => $options['body_font']['options']['faces'],
					'priority' => 5
			) );
		
		// Body font color
			$wp_customize->add_setting( 'simple[content_text]', array(
				'default' => $options['content_text']['std'],
				'type' => 'option',
				'sanitize_callback' => 'sanitize_hex_color'
			) );
			
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'content_text', array(
				'label'   => $options['content_text']['name'],
				'section' => 'organizedthemes_content',
				'settings'   => 'simple[content_text]',
				'priority' => 7
			) ) );
		
		// Heading font face
			$wp_customize->add_setting( 'simple[heading_font][face]', array(
				'default' => $options['heading_font']['std']['face'],
				'type' => 'option',
				'sanitize_callback' => 'absint'
			) );
			
			$wp_customize->add_control( 'heading_font', array(
					'label' => $options['heading_font']['name'],
					'section' => 'organizedthemes_content',
					'settings' => 'simple[heading_font][face]',
					'type' => 'select',
					'choices' => $options['heading_font']['options']['faces'],
					'priority' => 15
			) );
		
		// Heading color
			$wp_customize->add_setting( 'simple[heading_color]', array(
				'default' => $options['heading_color']['std'],
				'type' => 'option',
				'sanitize_callback' => 'sanitize_hex_color'
			) );
			
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'heading_color', array(
				'label'   => $options['heading_color']['name'],
				'section' => 'organizedthemes_content',
				'settings'   => 'simple[heading_color]',
				'priority' => 17
			) ) );
		
		// Link color
			$wp_customize->add_setting( 'simple[link_color]', array(
				'default' => $options['link_color']['std'],
				'type' => 'option',
				'sanitize_callback' => 'sanitize_hex_color'
			) );
			
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'link_color', array(
				'label'   => $options['link_color']['name'],
				'section' => 'organizedthemes_content',
				'settings'   => 'simple[link_color]',
				'priority' => 20
			) ) );
		
		// Link Hover color
			$wp_customize->add_setting( 'simple[link_color_hover]', array(
				'default' => $options['link_color_hover']['std'],
				'type' => 'option',
				'sanitize_callback' => 'sanitize_hex_color'
			) );
			
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'link_color_hover', array(
				'label'   => $options['link_color_hover']['name'],
				'section' => 'organizedthemes_content',
				'settings'   => 'simple[link_color_hover]',
				'priority' => 25
			) ) );
		
		// Content Text
			$wp_customize->add_setting( 'simple[content_text]', array(
				'default' => $options['content_text']['std'],
				'type' => 'option',
				'sanitize_callback' => 'sanitize_hex_color'
			) );
			
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'content_text', array(
				'label'   => $options['content_text']['name'],
				'section' => 'organizedthemes_content',
				'settings'   => 'simple[content_text]',
				'priority' => 50
			) ) );
	
		// Content Background
			$wp_customize->add_setting( 'simple[content_background]', array(
				'default' => $options['content_background']['std'],
				'type' => 'option',
				'sanitize_callback' => 'sanitize_hex_color'
			) );
			
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'content_background', array(
				'label'   => $options['content_background']['name'],
				'section' => 'organizedthemes_content',
				'settings'   => 'simple[content_background]',
				'priority' => 50
			) ) );
		
		// Excerpt or Content
			$wp_customize->add_setting( 'simple[content_excerpt]', array(
				'default' => $options['content_excerpt']['std'],
				'type' => 'option',
				'sanitize_callback' => 'absint'
			) );
		
			$wp_customize->add_control( 'simple_content_excerpt', array(
					'label' => $options['content_excerpt']['name'],
					'section' => 'organizedthemes_content',
					'settings' => 'simple[content_excerpt]',
					'type' => $options['content_excerpt']['type'],
					'choices' => $options['content_excerpt']['options'],
					'priority' => 60
			) );
		
		
		
// Buttons
	
	$wp_customize->add_section( 'organizedthemes_buttons', array(
		'title' => __( 'Buttons', 'organizedthemes' ),
		'priority' => 35
	) );
		
		// Button color
			$wp_customize->add_setting( 'simple[button]', array(
				'default' => $options['button']['std'],
				'type' => 'option',
				'sanitize_callback' => 'sanitize_hex_color'
			) );
			
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'button', array(
				'label'   => $options['button']['name'],
				'section' => 'organizedthemes_buttons',
				'settings'   => 'simple[button]',
				'priority' => 30
			) ) );
		
		// Button background
			$wp_customize->add_setting( 'simple[button_background]', array(
				'default' => $options['button_background']['std'],
				'type' => 'option',
				'sanitize_callback' => 'sanitize_hex_color'
			) );
			
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'button_background', array(
				'label'   => $options['button_background']['name'],
				'section' => 'organizedthemes_buttons',
				'settings'   => 'simple[button_background]',
				'priority' => 35
			) ) );
		
		// Button color hover
			$wp_customize->add_setting( 'simple[button_hover]', array(
				'default' => $options['button_hover']['std'],
				'type' => 'option',
				'sanitize_callback' => 'sanitize_hex_color'
			) );
			
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'button_hover', array(
				'label'   => $options['button_hover']['name'],
				'section' => 'organizedthemes_buttons',
				'settings'   => 'simple[button_hover]',
				'priority' => 40
			) ) );
		
		// Button background hover
			$wp_customize->add_setting( 'simple[button_background_hover]', array(
				'default' => $options['button_background_hover']['std'],
				'type' => 'option',
				'sanitize_callback' => 'sanitize_hex_color'
			) );
			
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'button_background_hover', array(
				'label'   => $options['button_background_hover']['name'],
				'section' => 'organizedthemes_buttons',
				'settings'   => 'simple[button_background_hover]',
				'priority' => 45
			) ) );
		
	
// Widgets
	
	$wp_customize->add_section( 'organizedthemes_widgets', array(
		'title' => __( 'Widgets', 'organizedthemes' ),
		'priority' => 40
	) );
	
			
		// Widget Title face
			$wp_customize->add_setting( 'simple[widget_title_font][face]', array(
				'default' => $options['widget_title_font']['std']['face'],
				'type' => 'option',
				'sanitize_callback' => 'absint'
			) );
			
			$wp_customize->add_control( 'widget_title_font', array(
					'label' => $options['widget_title_font']['name'],
					'section' => 'organizedthemes_widgets',
					'settings' => 'simple[widget_title_font][face]',
					'type' => 'select',
					'choices' => $options['widget_title_font']['options']['faces'],
					'priority' => 1
			) );
			
		// Widget Title color		
			$wp_customize->add_setting( 'simple[widget_title]', array(
				'default' => $options['widget_title']['std'],
				'type' => 'option',
				'sanitize_callback' => 'sanitize_hex_color'
			) );
			
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'widget_title', array(
				'label'   => $options['widget_title']['name'],
				'section' => 'organizedthemes_widgets',
				'settings'   => 'simple[widget_title]',
				'priority' => 5
			) ) );
			
		// Widget Text		
			$wp_customize->add_setting( 'simple[widget_text]', array(
				'default' => $options['widget_text']['std'],
				'type' => 'option',
				'sanitize_callback' => 'sanitize_hex_color'
			) );
			
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'widget_text', array(
				'label'   => $options['widget_text']['name'],
				'section' => 'organizedthemes_widgets',
				'settings'   => 'simple[widget_text]',
				'priority' => 10
			) ) );	
		
		// Widget Background		
			$wp_customize->add_setting( 'simple[widget_background]', array(
				'default' => $options['widget_background']['std'],
				'type' => 'option',
				'sanitize_callback' => 'sanitize_hex_color'
			) );
			
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'widget_background', array(
				'label'   => $options['widget_background']['name'],
				'section' => 'organizedthemes_widgets',
				'settings'   => 'simple[widget_background]',
				'priority' => 15
			) ) );
		
	
			
	
				
			
	// Footer
		
		$wp_customize->add_section( 'organizedthemes_footer', array(
			'title' => __( 'Footer', 'organizedthemes' ),
			'priority' => 50
		) );	
		
		// Footer Text
			$wp_customize->add_setting( 'simple[footer_text]', array(
				'default' => $options['footer_text']['std'],
				'type' => 'option',
				'sanitize_callback' => 'absint'
			) );
			
			$wp_customize->add_control( 'footer_text', array(
				'label'   => $options['footer_text']['name'],
				'section' => 'organizedthemes_footer',
				'settings'   => 'simple[footer_text]',
				'type' => $options['footer_text']['type']
			 ) );
		
		// Footer Color
			$wp_customize->add_setting( 'simple[footer_color]', array(
				'default' => $options['footer_color']['std'],
				'type' => 'option',
				'sanitize_callback' => 'sanitize_hex_color'
			) );
			
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_color', array(
				'label'   => $options['footer_color']['name'],
				'section' => 'organizedthemes_footer',
				'settings'   => 'simple[footer_color]',
				'priority' => 65
			) ) );
			
		// Footer Background Color
			$wp_customize->add_setting( 'simple[footer_background]', array(
				'default' => $options['footer_background']['std'],
				'type' => 'option',
				'sanitize_callback' => 'sanitize_hex_color'
			) );
			
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_background', array(
				'label'   => $options['footer_background']['name'],
				'section' => 'organizedthemes_footer',
				'settings'   => 'simple[footer_background]',
				'priority' => 67
			) ) );
			
// Navigation

	// Nav bar color
		$wp_customize->add_setting( 'simple[navigation_background_color]', array(
			'default' => $options['navigation_background_color']['std'],
			'type' => 'option',
			'sanitize_callback' => 'sanitize_hex_color'
		) );
		
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'navigation_background_color', array(
			'label'   => $options['navigation_background_color']['name'],
			'section' => 'nav',
			'settings'   => 'simple[navigation_background_color]',
			'priority' => 10
		) ) );	
	
	
	// Navigation item font
		$wp_customize->add_setting( 'simple[navigation_font][face]', array(
			'default' => $options['navigation_font']['std']['face'],
			'type' => 'option',
			'sanitize_callback' => 'absint'
		) );
		
		$wp_customize->add_control( 'navigation_font', array(
				'label' => $options['navigation_font']['name'],
				'section' => 'nav',
				'settings' => 'simple[navigation_font][face]',
				'type' => 'select',
				'choices' => $options['navigation_font']['options']['faces'],
				'priority' => 15
		) );
	
	// Nav Item Color
		$wp_customize->add_setting( 'simple[navigation_link_color]', array(
			'default' => $options['navigation_link_color']['std'],
			'type' => 'option',
			'sanitize_callback' => 'sanitize_hex_color'
		) );
		
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'navigation_link_color', array(
			'label'   => $options['navigation_link_color']['name'],
			'section' => 'nav',
			'settings'   => 'simple[navigation_link_color]',
			'priority' => 20
		) ) );
	
	// Nav Item Color Hover
		$wp_customize->add_setting( 'simple[navigation_link_color_hover]', array(
			'default' => $options['navigation_link_color_hover']['std'],
			'type' => 'option',
			'sanitize_callback' => 'sanitize_hex_color'
		) );
		
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'navigation_link_color_hover', array(
			'label'   => $options['navigation_link_color_hover']['name'],
			'section' => 'nav',
			'settings'   => 'simple[navigation_link_color_hover]',
			'priority' => 25
		) ) );
	
	// Nav Item Background Color Hover
		$wp_customize->add_setting( 'simple[navigation_link_color_hover_background]', array(
			'default' => $options['navigation_link_color_hover_background']['std'],
			'type' => 'option',
			'sanitize_callback' => 'sanitize_hex_color'
		) );
		
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'navigation_link_color_hover_background', array(
			'label'   => $options['navigation_link_color_hover_background']['name'],
			'section' => 'nav',
			'settings'   => 'simple[navigation_link_color_hover_background]',
			'priority' => 25
		) ) );
		
}

