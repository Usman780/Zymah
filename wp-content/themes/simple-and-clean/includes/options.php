<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 * 
 */

function optionsframework_option_name() {

	$optionsframework_settings = get_option('optionsframework');
	
	// Edit 'options-theme-customizer' and set your own theme name instead
	$optionsframework_settings['id'] = 'simple';
	update_option('optionsframework', $optionsframework_settings);
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the "id" fields, make sure to use all lowercase and no spaces.
 *  
 */

function optionsframework_options() {
	
	// Graphic logo or dynamic text
		$logo_type = array(
			"text" => __( 'Text', 'simple' ),
			"image" => __( 'Image', 'simple' )
		);
	
	// Yes or No
		$yes = array(
			"yes" => __( 'Yes', 'simple' ),
			"no" => __( 'No', 'simple' )
		);
	
	// True or False
		$tf = array(
			"true" => __( 'Yes', 'simple' ),
			"false" => __( 'No', 'simple' )
		);
		
	// excerpt or full content
		$content_type = array(
			"excerpt" => __( 'Excerpt', 'simple' ),
			"content" => __( 'Full Content', 'simple' )
			);
		
		
	$options = array();
		
	$options[] = array( "name" => __( 'Header', 'simple' ),
						"type" => "heading");
	
	$options['logo'] = array( "name" => __( 'Upload Logo', 'simple' ),
						"desc" => __( 'Upload a graphic logo here.', 'simple' ),
						"id" => "logo",
						"type" => "upload");
	
	$options['site_title_font'] = array( 'name' => __( 'Text Logo Font', 'simple' ),
						'desc' => __( 'Choose the type for your text logo.', 'simple' ),
						'id' => 'site_title_font',
						'std' => array( 'size' => '50px', 'face' => 'Open Sans, sans-serif', 'color' => '#ffffff'),
						'type' => 'typography',
						'options' => array(
							'faces' => options_typography_get_google_fonts(),
							'styles' => false,
							'color' => false )
						);
						
	$options['logo_color'] = array( "name" => __( 'Text Logo Color', 'simple' ),
						"desc" => __( 'The color of a text based logo.', 'simple' ),
						"id" => "logo_color",
						"std" => "",
						"type" => "color");
	
	$options['logo_color_hover'] = array( "name" => __( 'Text Logo Hover Color', 'simple' ),
						"desc" => __( 'The color of a text based logo while hovering.', 'simple' ),
						"id" => "logo_color_hover",
						"std" => "",
						"type" => "color");
	
	$options['navigation_font'] = array( 'name' => __( 'Navigation Item Font', 'simple' ),
						'desc' => __( 'Sets the type for your navigation menu.', 'simple' ),
						'id' => 'navigation_font',
						'std' => array( 'size' => '20px', 'face' => 'Raleway, cursive', 'color' => '#ffffff'),
						'type' => 'typography',
						'options' => array(
							'faces' => options_typography_get_google_fonts(),
							'styles' => false,
							'color' => false )
						);
	
	$options['navigation_background_color'] = array( "name" => __( 'Navigation Bar Background Color', 'simple' ),
						"desc" => __( 'Sets the background color for navigation bar.', 'simple' ),
						"id" => "navigation_background_color",
						"std" => "",
						"type" => "color");
						
	
	$options['navigation_link_color'] = array( "name" => __( 'Navigation Link Color', 'simple' ),
						"desc" => __( 'Sets the link color for navigation bar.', 'simple' ),
						"id" => "navigation_link_color",
						"std" => "",
						"type" => "color");
	
	$options['navigation_link_color_hover'] = array( "name" => __( 'Navigation Link Hover', 'simple' ),
						"desc" => __( 'Sets the link color for navigation bar while hovering.', 'simple' ),
						"id" => "navigation_link_color_hover",
						"std" => "",
						"type" => "color");
	
	$options['navigation_link_color_hover_background'] = array( "name" => __( 'Navigation Link Hover Background', 'simple' ),
						"desc" => __( 'Sets the background link color for navigation bar while hovering.', 'simple' ),
						"id" => "navigation_link_color_hover_background",
						"std" => "",
						"type" => "color");

	
	$options[] = array( "name" => __( 'Favicon', 'simple' ),
						"desc" => __( 'Upload a favicon (small icon that sits beside your websites address in a browser navigation bar here.', 'simple' ),
						"id" => "favicon",
						"std" => "",
						"type" => "upload");

	
	$options[] = array( "name" => __( 'Mobile Navigation Label', 'simple' ),
						"desc" => __( 'Sets the text for the mobile navigation menu', 'simple' ),
						"id" => "mobile_navigation_name",
						"std" => "Navigation",
						"type" => "text");



$options[] = array( "name" => __( 'Content', 'simple' ),
					"type" => "heading");

	$options['content_excerpt'] = array( "name" => __( 'Excerpt Or Full Content', 'simple' ),
						"desc" => __( 'You can choose to display your full post content or only an excerpt on archive pages.', 'simple' ),
						"id" => "content_excerpt",
						"std" => "yes",
						"type" => "select",
						"class" => "mini",
						"options" => $content_type);
	
	$options['body_font'] = array( 'name' => __( 'Body Font', 'simple' ),
						'desc' => __( 'This is used for the main conent and widgets.', 'simple' ),
						'id' => 'body_font',
						'std' => array( 'size' => '15px', 'face' => 'Open Sans, sans-serif', 'color' => '#000000'),
						'type' => 'typography',
						'options' => array(
							'faces' => options_typography_get_google_fonts(),
							'styles' => false,
							'color' => false )
						);
	
	$options['link_color'] = array( "name" => __( 'Link Color', 'simple' ),
						"desc" => __( 'Sets the default color for links.', 'simple' ),
						"id" => "link_color",
						"std" => "",
						"type" => "color");	
	
	$options['link_color_hover'] = array( "name" => __( 'Link Hover Color', 'simple' ),
						"desc" => __( 'Sets the hover state color for links.', 'simple' ),
						"id" => "link_color_hover",
						"std" => "",
						"type" => "color");
		
	$options['content_text'] = array( "name" => __( 'Text Color', 'simple' ),
						"desc" => __( 'The color of your main text', 'simple' ),
						"id" => "content_text",
						"std" => "",
						"type" => "color");	
	
	$options['content_background'] = array( "name" => __( 'Content Background Color', 'simple' ),
						"desc" => __( 'The background color of the main content area.', 'simple' ),
						"id" => "content_background",
						"std" => "",
						"type" => "color");	
	
	$options['heading_font'] = array( 'name' => __( 'Heading Font', 'simple' ),
						'desc' => __( 'Choose a font for your page titles and other headings (h1, h2, h3, h4, h5, h6)', 'simple' ),
						'id' => 'heading_font',
						'std' => array( 'size' => '24px', 'face' => 'Raleway, cursive', 'color' => '#000000'),
						'type' => 'typography',
						'options' => array(
							'faces' => options_typography_get_google_fonts(),
							'styles' => false,
							'sizes' => false,
							'color' => false )
						);
	
	$options['heading_color'] = array( "name" => __( 'Heading Color', 'simple' ),
						"desc" => __( 'The color of your headings', 'simple' ),
						"id" => "heading_color",
						"std" => "",
						"type" => "color");	

$options[] = array( "name" => __( 'Footer', 'simple' ),
					"type" => "heading");

						
	$options['footer_text'] = array( "name" => __( 'Custom Footer Text', 'simple' ),
						"desc" => __( 'The text you enter here will be displayed in the footer.', 'simple' ),
						"id" => "footer_text",
						"std" => "",
						"type" => "text");
	
	$options['footer_color'] = array( "name" => __( 'Footer Text Color', 'simple' ),
						"desc" => __( 'The color the text in your footer.', 'simple' ),
						"id" => "footer_color",
						"std" => "",
						"type" => "color");
						
	
	$options['footer_background'] = array( "name" => __( 'Footer background Color', 'simple' ),
						"desc" => __( 'The background color the text in your footer.', 'simple' ),
						"id" => "footer_background",
						"std" => "",
						"type" => "color");

	$options[] = array( "name" => __( 'Widgets', 'simple' ),
						"type" => "heading");

	
	$options['widget_title_font'] = array( 'name' => __( 'Widget Title Font', 'simple' ),
						'desc' => __( 'Choose a font for your widget titles.', 'simple' ),
						'id' => 'widget_title_font',
						'std' => array( 'size' => '24px', 'face' => 'Raleway, cursive', 'color' => '#ffffff'),
						'type' => 'typography',
						'options' => array(
							'faces' => options_typography_get_google_fonts(),
							'styles' => false,
							'color' => false )
						);
		
	$options['widget_title'] = array( "name" => __( 'Widget Title Color', 'simple' ),
						"desc" => __( 'Text color for widget titles', 'simple' ),
						"id" => "widget_title",
						"std" => "",
						"type" => "color");
	
	$options['widget_title_background'] = array( "name" => __( 'Widget Title Background', 'simple' ),
						"desc" => __( 'Background color for widget titles.', 'simple' ),
						"id" => "widget_title_background",
						"std" => "",
						"type" => "color");
	
	$options['widget_text'] = array( "name" => __( 'Widget Text Color', 'simple' ),
						"desc" => __( 'Sets the color of text in widgets.', 'simple' ),
						"id" => "widget_text",
						"std" => "",
						"type" => "color");
	
	$options['widget_background'] = array( "name" => __( 'Widget Background Color', 'simple' ),
						"desc" => __( 'Sets the background color in widgets.', 'simple' ),
						"id" => "widget_background",
						"std" => "",
						"type" => "color");
	
$options[] = array( "name" => __( 'Buttons', 'simple' ),
					"type" => "heading");
	
	$options['button'] = array( "name" => __( 'Button Text', 'simple' ),
						"desc" => __( 'Text color for buttons', 'simple' ),
						"id" => "button",
						"std" => "",
						"type" => "color");
	
	$options['button_background'] = array( "name" => __( 'Button Background', 'simple' ),
						"desc" => __( 'Background color for buttons', 'simple' ),
						"id" => "button_background",
						"std" => "",
						"type" => "color");
						
	$options['button_hover'] = array( "name" => __( 'Button Text (hover)', 'simple' ),
						"desc" => __( 'Text color for buttons while hovering', 'simple' ),
						"id" => "button_hover",
						"std" => "",
						"type" => "color");
	
	$options['button_background_hover'] = array( "name" => __( 'Button Background (hover)', 'simple' ),
						"desc" => __( 'Background color for buttons while hovering', 'simple' ),
						"id" => "button_background_hover",
						"std" => "",
						"type" => "color");
								
$options[] = array( "name" => __( 'Advanced', 'simple' ),
					"type" => "heading");
	
	$options[] = array( 'name' => __( 'Enable Google Fonts', 'simple' ),
						'desc' => __( 'Turns on the output of Google fonts.', 'simple' ),
						'id' => 'disable_fonts',
						'std' => true,
						'type' => 'checkbox' );
										
	$options[] = array( "name" => __( 'Custom CSS', 'simple' ),
						"desc" => __( 'Add any custom CSS you would like to use here.', 'simple' ),
						"id" => "custom_css",
						"std" => "",
						"type" => "textarea"); 
							
	return $options;
}