<?php
/**
 * Polmo Theme Customizer
 *
 * @package Polmo
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 *
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */

function jeweltheme_polmo_customize_register( $wp_customize ) {
	
	if ( ! class_exists( 'WP_Customize_Control' ) )
    return NULL;
	
	class Jewel_Theme_Polmo_Category_Dropdown_Customize_Control extends WP_Customize_Control {
	    private $cats = false;

	    public function __construct($manager, $id, $args = array(), $options = array())
	    {
	        $this->cats = get_categories($options);

	        parent::__construct( $manager, $id, $args );
	    }

	    /**
	     * Render the content of the category dropdown
	     *
	     * @return HTML
	     */
	    public function render_content()
	       {
	            if(!empty($this->cats))
	            {
	                ?>
	                    <label>
	                      <span class="customize-category-select-control"><?php echo esc_html( $this->label ); ?></span>
	                      <select <?php $this->link(); ?>>
	                           <?php
	                                foreach ( $this->cats as $cat )
	                                {
	                                    printf('<option value="%s" %s>%s</option>', $cat->term_id, selected($this->value(), $cat->term_id, false), $cat->name);
	                                }
	                           ?>
	                      </select>
	                    </label>
	                <?php
	            }
	       }
	 }

	class jeweltheme_polmo_Customize_Textarea_Control extends WP_Customize_Control {
		public $type = 'textarea';
	 
		public function render_content() {
			?>
			<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
			</label>
			<?php
		}
	}

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';


/**************	General Settings *******************/

if ( class_exists( 'WP_Customize_Panel' ) ){
	
		$wp_customize->add_panel( 'panel_general', array(
			'priority' => 30,
			'capability' => 'edit_theme_options',
			'theme_supports' => '',
			'title' => __( 'General Settings', 'jeweltheme_polmo' )
		) );
		
		$wp_customize->add_section( 'jeweltheme_polmo_general_section' , array(
				'title'       => __( 'General', 'jeweltheme_polmo' ),
				'priority'    => 30,
				'panel' => 'panel_general'
		));
		/* LOGO	*/
		$wp_customize->add_setting( 'jeweltheme_polmo_logo', array('sanitize_callback' => 'esc_url_raw'));
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'jeweltheme_polmo_logo', array(
				'label'    => __( 'Logo', 'jeweltheme_polmo' ),
				'section'  => 'title_tagline',
				'settings' => 'jeweltheme_polmo_logo',
				'priority'    => 1,
		)));


		
		/* Blog Section */
		$wp_customize->add_section( 'jeweltheme_polmo_general_blog_section' , array(
				'title'       => __( 'Blog Section', 'jeweltheme_polmo' ),
				'priority'    => 31,
				'panel' => 'panel_general'
		));

		/* Blog Title */
		$wp_customize->add_setting( 'jeweltheme_polmo_general_blog_title', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => 'Welcome To <span>Polmo</span> Blog'));
		$wp_customize->add_control( 'jeweltheme_polmo_general_blog_title', array(
			'label'    => __( 'Blog Title', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_general_blog_section',
			'settings' => 'jeweltheme_polmo_general_blog_title',
			'priority'    => 1,
			));		

		/* Blog Description */
		$wp_customize->add_setting( 'jeweltheme_polmo_general_blog_desc', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => 'Our Creative Blog Will keep you always Updated'));
		$wp_customize->add_control( 'jeweltheme_polmo_general_blog_desc', array(
			'label'    => __( 'Blog Title', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_general_blog_section',
			'settings' => 'jeweltheme_polmo_general_blog_desc',
			'priority'    => 1,
			));

		/* Banner Image	*/
		$wp_customize->add_setting( 'jeweltheme_polmo_banner_image', array('sanitize_callback' => 'esc_url_raw', 'default' => get_template_directory_uri() . '/images/background/blog.jpg'));
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'jeweltheme_polmo_banner_image', array(
				'label'    => __( 'Blog Banner', 'jeweltheme_polmo' ),
				'section'  => 'jeweltheme_polmo_general_blog_section',
				'settings' => 'jeweltheme_polmo_banner_image',
				'priority'    => 2,
		)));





		// Socials Section		
		$wp_customize->add_section( 'jeweltheme_polmo_general_socials_section' , array(
				'title'       => __( 'Socials', 'jeweltheme_polmo' ),
				'priority'    => 32,
				'panel' => 'panel_general'
		));
		
		/* facebook */
		$wp_customize->add_setting( 'jeweltheme_polmo_socials_facebook', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => '1,203'));
		$wp_customize->add_control( 'jeweltheme_polmo_socials_facebook', array(
				'label'    => __( 'Facebook Likes', 'jeweltheme_polmo' ),
				'section'  => 'jeweltheme_polmo_general_socials_section',
				'settings' => 'jeweltheme_polmo_socials_facebook',
				'priority'    => 1,
		));
		/* twitter */
		$wp_customize->add_setting( 'jeweltheme_polmo_socials_twitter', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => '2,305'));
		$wp_customize->add_control( 'jeweltheme_polmo_socials_twitter', array(
				'label'    => __( 'Twitter Followers', 'jeweltheme_polmo' ),
				'section'  => 'jeweltheme_polmo_general_socials_section',
				'settings' => 'jeweltheme_polmo_socials_twitter',
				'priority'    => 2,
		));
		/* dribbble */
		$wp_customize->add_setting( 'jeweltheme_polmo_socials_dribbble', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => '1,101'));
		$wp_customize->add_control( 'jeweltheme_polmo_socials_dribbble', array(
				'label'    => __( 'Dribbble Fans', 'jeweltheme_polmo' ),
				'section'  => 'jeweltheme_polmo_general_socials_section',
				'settings' => 'jeweltheme_polmo_socials_dribbble',
				'priority'    => 3,
		));

	}



    /**************	Slider Section ***************/	
	if ( class_exists( 'WP_Customize_Panel' ) ){
		$wp_customize->add_panel( 'panel_slider', array(
			'priority' => 31,
			'capability' => 'edit_theme_options',
			'theme_supports' => '',
			'title' => __( 'Slider Section', 'jeweltheme_polmo' )
			) );
		
		$wp_customize->add_section( 'jeweltheme_polmo_slider_1' , array(
			'title'       => __( 'Slider 1', 'jeweltheme_polmo' ),
			'priority'    => 1,
			'panel' => 'panel_slider'
			));

	
		/* Slider Title 1 */
		$wp_customize->add_setting( 'jeweltheme_polmo_slider_title1', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('Welcome to <span>Polmo</span>','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_slider_title1', array(
			'label'    => __( 'Title 1', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_slider_1',
			'settings' => 'jeweltheme_polmo_slider_title1',
			'priority'    => 1,
		));		

		/* Slider Description 1 */
		$wp_customize->add_setting( 'jeweltheme_polmo_slider_desc1', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('POLMO theme is absolute free for your personal or business use','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_slider_desc1', array(
			'label'    => __( 'Description 1', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_slider_1',
			'settings' => 'jeweltheme_polmo_slider_desc1',
			'priority'    => 2,
		));

		/* Slider Image 1 */
		$wp_customize->add_setting( 'jeweltheme_polmo_slider_image1', array('sanitize_callback' => 'esc_url_raw', 'default' => get_template_directory_uri() . '/images/slider/1.jpg'));
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'polmo_image_slider1', array(
			'label'    => __( 'Slider Image 1', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_slider_1',
			'settings' => 'jeweltheme_polmo_slider_image1',
			'priority'    => 3,
			)));


		/* Button 1 */
		$wp_customize->add_setting( 'jeweltheme_polmo_slider_button1', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('Download Polmo','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_slider_button1', array(
			'label'    => __( 'Button 1', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_slider_1',
			'settings' => 'jeweltheme_polmo_slider_button1',
			'priority'    => 4,
			));		

		/* Button URL 1 */
		$wp_customize->add_setting( 'jeweltheme_polmo_slider_button_url1', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('http://jeweltheme.com/product/polmo-lite','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_slider_button_url1', array(
			'label'    => __( 'Button URL 1', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_slider_1',
			'settings' => 'jeweltheme_polmo_slider_button_url1',
			'priority'    => 5,
			));		
	


		/* Slider 2 */
		$wp_customize->add_section( 'jeweltheme_polmo_slider_2' , array(
			'title'       => __( 'Slider 2', 'jeweltheme_polmo' ),
			'priority'    => 2,
			'panel' => 'panel_slider'
			));

		/* Slider Title 2 */
		$wp_customize->add_setting( 'jeweltheme_polmo_slider_title2', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('Premium <span>Quality WordPress</span> Theme','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_slider_title2', array(
			'label'    => __( 'Title 2', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_slider_2',
			'settings' => 'jeweltheme_polmo_slider_title2',
			'priority'    => 6,
		));		
		/* Slider Description 2 */
		$wp_customize->add_setting( 'jeweltheme_polmo_slider_desc2', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('POLMO is a Premium Wordpress html5 Template with clean, modern and unique styles','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_slider_desc2', array(
			'label'    => __( 'Description 2', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_slider_2',
			'settings' => 'jeweltheme_polmo_slider_desc2',
			'priority'    => 7,
		));

		/* Slider Image 2 */
		$wp_customize->add_setting( 'jeweltheme_polmo_slider_image2', array('sanitize_callback' => 'esc_url_raw', 'default' => get_template_directory_uri() . '/images/slider/3.jpg'));
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'polmo_image_slider2', array(
			'label'    => __( 'Slider Image 2', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_slider_2',
			'settings' => 'jeweltheme_polmo_slider_image2',
			'priority'    => 8,
			)));

		/* Button 2 */
		$wp_customize->add_setting( 'jeweltheme_polmo_slider_button2', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('Download Polmo','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_slider_button2', array(
			'label'    => __( 'Button 2', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_slider_2',
			'settings' => 'jeweltheme_polmo_slider_button2',
			'priority'    => 9,
			));		

		/* Button URL 2 */
		$wp_customize->add_setting( 'jeweltheme_polmo_slider_button_url2', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('http://jeweltheme.com/product/polmo-lite','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_slider_button_url2', array(
			'label'    => __( 'Button URL 2', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_slider_2',
			'settings' => 'jeweltheme_polmo_slider_button_url2',
			'priority'    => 10,
			));	
		
	}




    /**************	Service Section ***************/	
	if ( class_exists( 'WP_Customize_Panel' ) ){
		$wp_customize->add_panel( 'panel_service', array(
			'priority' => 32,
			'capability' => 'edit_theme_options',
			'theme_supports' => '',
			'title' => __( 'Services Section', 'jeweltheme_polmo' )
			) );
		
		$wp_customize->add_section( 'jeweltheme_polmo_service_section' , array(
			'title'       => __( 'Heading', 'jeweltheme_polmo' ),
			'priority'    => 32,
			'panel' => 'panel_service'
			));
	

		/* Service Title */
		$wp_customize->add_setting( 'jeweltheme_polmo_service_heading_title', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('<span>Polmo</span> Core Services','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_service_heading_title', array(
			'label'    => __( 'Title 1', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_service_section',
			'settings' => 'jeweltheme_polmo_service_heading_title',
			'priority'    => 1,
		));		

		/* Service Description */
		$wp_customize->add_setting( 'jeweltheme_polmo_service_desc', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('Matter is made up of atoms having dimensions approximately determined to be in the neighbourhood of the one fifty-millionth of an inch in diameter.','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_service_desc', array(
			'label'    => __( 'Description 1', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_service_section',
			'settings' => 'jeweltheme_polmo_service_desc',
			'priority'    => 2,
		));



		/* Service 1 */
		$wp_customize->add_section( 'jeweltheme_polmo_service1' , array(
			'title'       => __( 'Service 1', 'jeweltheme_polmo' ),
			'priority'    => 32,
			'panel' => 'panel_service'
			));

		/* Service 1 Icon */
		$wp_customize->add_setting( 'jeweltheme_polmo_service_icon_1', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('fa-android','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_service_icon_1', array(
			'label'    => __( 'Font Awesome Icon', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_service1',
			'settings' => 'jeweltheme_polmo_service_icon_1',
			'priority'    => 1,
		));				

		/* Service 1 Title */
		$wp_customize->add_setting( 'jeweltheme_polmo_service_title_1', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('Android Apps Developement','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_service_title_1', array(
			'label'    => __( 'Title', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_service1',
			'settings' => 'jeweltheme_polmo_service_title_1',
			'priority'    => 2,
		));		

		/* Service 1 Description */
		$wp_customize->add_setting( 'jeweltheme_polmo_service_desc_1', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('Atoms dimensions approximately determined with one fifty-millionth an inch in diameter.','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_service_desc_1', array(
			'label'    => __( 'Description', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_service1',
			'settings' => 'jeweltheme_polmo_service_desc_1',
			'priority'    => 3,
		));	




		/* Service 2 */
		$wp_customize->add_section( 'jeweltheme_polmo_service2' , array(
			'title'       => __( 'Service 2', 'jeweltheme_polmo' ),
			'priority'    => 32,
			'panel' => 'panel_service'
			));

		/* Service 2 Icon */
		$wp_customize->add_setting( 'jeweltheme_polmo_service_icon_2', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('fa-html5','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_service_icon_2', array(
			'label'    => __( 'Font Awesome Icon', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_service2',
			'settings' => 'jeweltheme_polmo_service_icon_2',
			'priority'    => 1,
		));				

		/* Service 2 Title */
		$wp_customize->add_setting( 'jeweltheme_polmo_service_title_2', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('HTML5 Modern Technology','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_service_title_2', array(
			'label'    => __( 'Title', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_service2',
			'settings' => 'jeweltheme_polmo_service_title_2',
			'priority'    => 2,
		));		

		/* Service 2 Description */
		$wp_customize->add_setting( 'jeweltheme_polmo_service_desc_2', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('Continuity as distinguished from may be considering what would be visible by magnification.','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_service_desc_2', array(
			'label'    => __( 'Description', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_service2',
			'settings' => 'jeweltheme_polmo_service_desc_2',
			'priority'    => 3,
		));	


		/* Service 3 */
		$wp_customize->add_section( 'jeweltheme_polmo_service3' , array(
			'title'       => __( 'Service 3', 'jeweltheme_polmo' ),
			'priority'    => 32,
			'panel' => 'panel_service'
			));

		/* Service 3 Icon */
		$wp_customize->add_setting( 'jeweltheme_polmo_service_icon_3', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('fa-maxcdn','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_service_icon_3', array(
			'label'    => __( 'Font Awesome Icon', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_service3',
			'settings' => 'jeweltheme_polmo_service_icon_3',
			'priority'    => 1,
		));				

		/* Service 3 Title */
		$wp_customize->add_setting( 'jeweltheme_polmo_service_title_3', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('Latest Max CDN Service','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_service_title_3', array(
			'label'    => __( 'Title', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_service3',
			'settings' => 'jeweltheme_polmo_service_title_3',
			'priority'    => 2,
		));		

		/* Service 3 Description */
		$wp_customize->add_setting( 'jeweltheme_polmo_service_desc_3', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('Definite amount of matter in visible universe, a number of molecules and atoms in huge number.','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_service_desc_3', array(
			'label'    => __( 'Description', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_service3',
			'settings' => 'jeweltheme_polmo_service_desc_3',
			'priority'    => 3,
		));	



		/* Service 4 */
		$wp_customize->add_section( 'jeweltheme_polmo_service4' , array(
			'title'       => __( 'Service 4', 'jeweltheme_polmo' ),
			'priority'    => 32,
			'panel' => 'panel_service'
			));

		/* Service 4 Icon */
		$wp_customize->add_setting( 'jeweltheme_polmo_service_icon_4', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('fa-umbrella','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_service_icon_4', array(
			'label'    => __( 'Font Awesome Icon', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_service4',
			'settings' => 'jeweltheme_polmo_service_icon_4',
			'priority'    => 1,
		));				

		/* Service 4 Title */
		$wp_customize->add_setting( 'jeweltheme_polmo_service_title_4', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('Latest Umbrella Server','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_service_title_4', array(
			'label'    => __( 'Title', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_service4',
			'settings' => 'jeweltheme_polmo_service_title_4',
			'priority'    => 2,
		));		

		/* Service 4 Description */
		$wp_customize->add_setting( 'jeweltheme_polmo_service_desc_4', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('Phenomena of light and waves of all lengths are found in velocity of 186,000 miles in a second.','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_service_desc_4', array(
			'label'    => __( 'Description', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_service4',
			'settings' => 'jeweltheme_polmo_service_desc_4',
			'priority'    => 3,
		));	

	}




    /**************	About Us Section ***************/	
	if ( class_exists( 'WP_Customize_Panel' ) ){
		$wp_customize->add_panel( 'panel_about_us', array(
			'priority' => 33,
			'capability' => 'edit_theme_options',
			'theme_supports' => '',
			'title' => __( 'About Us Section', 'jeweltheme_polmo' )
			) );
		
		$wp_customize->add_section( 'jeweltheme_polmo_about_us_section' , array(
			'title'       => __( 'About Agency', 'jeweltheme_polmo' ),
			'priority'    => 33,
			'panel' => 'panel_about_us'
			));
	

		/* About Us Title */
		$wp_customize->add_setting( 'jeweltheme_polmo_about_us_title', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('<span>We</span> are Polmo Limited Creative Web Agency','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_about_us_title', array(
			'label'    => __( 'Title', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_about_us_section',
			'settings' => 'jeweltheme_polmo_about_us_title',
			'priority'    => 1,
		));		

		/* About Us Description */
		$wp_customize->add_setting( 'jeweltheme_polmo_about_us_desc', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('There is so considerable a body of knowledge bearing upon the similarities and dissimilarities of these two entities that it will be well to compare them. After such comparison one will be better able to judge of the propriety of assuming them to be subject to identical laws','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_about_us_desc', array(
			'label'    => __( 'Description', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_about_us_section',
			'settings' => 'jeweltheme_polmo_about_us_desc',
			'priority'    => 2,
		));



		/* About Us Expertise */
		$wp_customize->add_section( 'jeweltheme_polmo_about_us_expertise_section' , array(
			'title'       => __( 'Expertise', 'jeweltheme_polmo' ),
			'priority'    => 33,
			'panel' => 'panel_about_us'
			));

		/* About Us Expertise Title */
		$wp_customize->add_setting( 'jeweltheme_polmo_about_us_expertise_title', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('<span>Our</span> areas of expertise','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_about_us_expertise_title', array(
			'label'    => __( 'Title', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_about_us_expertise_section',
			'settings' => 'jeweltheme_polmo_about_us_expertise_title',
			'priority'    => 1,
		));		

		/* About Us Expertise Percantage Title */
		$wp_customize->add_setting( 'jeweltheme_polmo_about_us_progress_title_1', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('WordPress Plugin Dev','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_about_us_progress_title_1', array(
			'label'    => __( 'Progresss Title 1', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_about_us_expertise_section',
			'settings' => 'jeweltheme_polmo_about_us_progress_title_1',
			'priority'    => 2,
		));		

		/* About Us Expertise Percantage */
		$wp_customize->add_setting( 'jeweltheme_polmo_about_us_progress_percantage_1', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('89','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_about_us_progress_percantage_1', array(
			'label'    => __( 'Progresss Title 1', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_about_us_expertise_section',
			'settings' => 'jeweltheme_polmo_about_us_progress_percantage_1',
			'priority'    => 3,
		));

		/* About Us Expertise Percantage Title */
		$wp_customize->add_setting( 'jeweltheme_polmo_about_us_progress_title_2', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('Web Design','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_about_us_progress_title_2', array(
			'label'    => __( 'Progresss Title 2', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_about_us_expertise_section',
			'settings' => 'jeweltheme_polmo_about_us_progress_title_2',
			'priority'    => 4,
		));		

		/* About Us Expertise Percantage */
		$wp_customize->add_setting( 'jeweltheme_polmo_about_us_progress_percantage_2', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('70','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_about_us_progress_percantage_2', array(
			'label'    => __( 'Progresss Title 2', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_about_us_expertise_section',
			'settings' => 'jeweltheme_polmo_about_us_progress_percantage_2',
			'priority'    => 5,
		));

		/* About Us Expertise Percantage Title */
		$wp_customize->add_setting( 'jeweltheme_polmo_about_us_progress_title_3', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('PHP Programming','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_about_us_progress_title_3', array(
			'label'    => __( 'Progresss Title 3', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_about_us_expertise_section',
			'settings' => 'jeweltheme_polmo_about_us_progress_title_3',
			'priority'    => 6,
		));		

		/* About Us Expertise Percantage */
		$wp_customize->add_setting( 'jeweltheme_polmo_about_us_progress_percantage_3', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('60','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_about_us_progress_percantage_3', array(
			'label'    => __( 'Progresss Title 3', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_about_us_expertise_section',
			'settings' => 'jeweltheme_polmo_about_us_progress_percantage_3',
			'priority'    => 7,
		));


		/* Button */
		$wp_customize->add_section( 'jeweltheme_polmo_about_us_button' , array(
			'title'       => __( 'Button', 'jeweltheme_polmo' ),
			'priority'    => 33,
			'panel' => 'panel_about_us'
			));

		/* About Us Button Text */
		$wp_customize->add_setting( 'jeweltheme_polmo_about_us_button_text', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('Learn More','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_about_us_button_text', array(
			'label'    => __( 'Button Text', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_about_us_button',
			'settings' => 'jeweltheme_polmo_about_us_button_text',
			'priority'    => 1,
		));		

		/* About Us Button URL */
		$wp_customize->add_setting( 'jeweltheme_polmo_about_us_button_url', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('#','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_about_us_button_url', array(
			'label'    => __( 'Button URL', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_about_us_button',
			'settings' => 'jeweltheme_polmo_about_us_button_url',
			'priority'    => 2,
		));		


		/* About Us Counter 1*/
		$wp_customize->add_section( 'jeweltheme_polmo_about_us_counter_section_1' , array(
			'title'       => __( 'Counter 1', 'jeweltheme_polmo' ),
			'priority'    => 33,
			'panel' => 'panel_about_us'
			));

		/* About Us Counter 1 */
		$wp_customize->add_setting( 'jeweltheme_polmo_about_us_counter_icon_1', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('fa-heart-o','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_about_us_counter_icon_1', array(
			'label'    => __( 'Font Awesome Icon', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_about_us_counter_section_1',
			'settings' => 'jeweltheme_polmo_about_us_counter_icon_1',
			'priority'    => 1,
		));		

		/* About Us Counter */
		$wp_customize->add_setting( 'jeweltheme_polmo_about_us_counter_number_1', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('8,236','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_about_us_counter_number_1', array(
			'label'    => __( 'Counter', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_about_us_counter_section_1',
			'settings' => 'jeweltheme_polmo_about_us_counter_number_1',
			'priority'    => 2,
		));		

		/* About Us Counter Text*/
		$wp_customize->add_setting( 'jeweltheme_polmo_about_us_counter_text_1', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('Love Bites','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_about_us_counter_text_1', array(
			'label'    => __( 'Counter Text', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_about_us_counter_section_1',
			'settings' => 'jeweltheme_polmo_about_us_counter_text_1',
			'priority'    => 3,
		));	



		/* About Us Counter 2*/
		$wp_customize->add_section( 'jeweltheme_polmo_about_us_counter_section_2' , array(
			'title'       => __( 'Counter 2', 'jeweltheme_polmo' ),
			'priority'    => 33,
			'panel' => 'panel_about_us'
			));

		/* About Us Counter 1 */
		$wp_customize->add_setting( 'jeweltheme_polmo_about_us_counter_icon_2', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('fa-wifi','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_about_us_counter_icon_2', array(
			'label'    => __( 'Font Awesome Icon', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_about_us_counter_section_2',
			'settings' => 'jeweltheme_polmo_about_us_counter_icon_2',
			'priority'    => 1,
		));		

		/* About Us Counter */
		$wp_customize->add_setting( 'jeweltheme_polmo_about_us_counter_number_2', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('1,203','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_about_us_counter_number_2', array(
			'label'    => __( 'Counter', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_about_us_counter_section_2',
			'settings' => 'jeweltheme_polmo_about_us_counter_number_2',
			'priority'    => 2,
		));		

		/* About Us Counter Text*/
		$wp_customize->add_setting( 'jeweltheme_polmo_about_us_counter_text_2', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('Wifi Zones','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_about_us_counter_text_2', array(
			'label'    => __( 'Counter Text', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_about_us_counter_section_2',
			'settings' => 'jeweltheme_polmo_about_us_counter_text_2',
			'priority'    => 3,
		));		


		/* About Us Counter 3*/
		$wp_customize->add_section( 'jeweltheme_polmo_about_us_counter_section_3' , array(
			'title'       => __( 'Counter 3', 'jeweltheme_polmo' ),
			'priority'    => 33,
			'panel' => 'panel_about_us'
			));

		/* About Us Counter 1 */
		$wp_customize->add_setting( 'jeweltheme_polmo_about_us_counter_icon_3', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('fa-map-marker','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_about_us_counter_icon_3', array(
			'label'    => __( 'Font Awesome Icon', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_about_us_counter_section_3',
			'settings' => 'jeweltheme_polmo_about_us_counter_icon_3',
			'priority'    => 1,
		));		

		/* About Us Counter */
		$wp_customize->add_setting( 'jeweltheme_polmo_about_us_counter_number_3', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('3,679','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_about_us_counter_number_3', array(
			'label'    => __( 'Counter', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_about_us_counter_section_3',
			'settings' => 'jeweltheme_polmo_about_us_counter_number_3',
			'priority'    => 2,
		));		

		/* About Us Counter Text*/
		$wp_customize->add_setting( 'jeweltheme_polmo_about_us_counter_text_3', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('Customer Centers','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_about_us_counter_text_3', array(
			'label'    => __( 'Counter Text', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_about_us_counter_section_3',
			'settings' => 'jeweltheme_polmo_about_us_counter_text_3',
			'priority'    => 3,
		));	


		/* About Us Counter 4*/
		$wp_customize->add_section( 'jeweltheme_polmo_about_us_counter_section_4' , array(
			'title'       => __( 'Counter 4', 'jeweltheme_polmo' ),
			'priority'    => 33,
			'panel' => 'panel_about_us'
			));

		/* About Us Counter 1 */
		$wp_customize->add_setting( 'jeweltheme_polmo_about_us_counter_icon_4', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('fa-cogs','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_about_us_counter_icon_4', array(
			'label'    => __( 'Font Awesome Icon', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_about_us_counter_section_4',
			'settings' => 'jeweltheme_polmo_about_us_counter_icon_4',
			'priority'    => 1,
		));		

		/* About Us Counter */
		$wp_customize->add_setting( 'jeweltheme_polmo_about_us_counter_number_4', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('2,310','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_about_us_counter_number_4', array(
			'label'    => __( 'Counter', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_about_us_counter_section_4',
			'settings' => 'jeweltheme_polmo_about_us_counter_number_4',
			'priority'    => 2,
		));		

		/* About Us Counter Text*/
		$wp_customize->add_setting( 'jeweltheme_polmo_about_us_counter_text_4', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('Mechanical Cogs','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_about_us_counter_text_4', array(
			'label'    => __( 'Counter Text', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_about_us_counter_section_4',
			'settings' => 'jeweltheme_polmo_about_us_counter_text_4',
			'priority'    => 3,
		));		


		/* About Us Work With Us*/
		$wp_customize->add_section( 'jeweltheme_polmo_about_us_work_with_us' , array(
			'title'       => __( 'Work With Us', 'jeweltheme_polmo' ),
			'priority'    => 33,
			'panel' => 'panel_about_us'
			));

		$wp_customize->add_setting( 'jeweltheme_polmo_about_us_work_with_us_image', array('sanitize_callback' => 'esc_url_raw', 'default' => get_template_directory_uri() . '/images/tab.jpg'));
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'jeweltheme_polmo_about_us_work_with_us_image', array(
			'label'    => __( 'Left Image', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_about_us_work_with_us',
			'settings' => 'jeweltheme_polmo_about_us_work_with_us_image',
			'priority'    => 1,
			)));		

		/* About Us Work With Us Title*/
		$wp_customize->add_setting( 'jeweltheme_polmo_about_us_work_with_us_title', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('<span>Why</span> Work With Us','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_about_us_work_with_us_title', array(
			'label'    => __( 'Title', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_about_us_work_with_us',
			'settings' => 'jeweltheme_polmo_about_us_work_with_us_title',
			'priority'    => 2,
		));		

		/* About Us Work With Us Desctiption*/
		$wp_customize->add_setting( 'jeweltheme_polmo_about_us_work_with_us_desc', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('One part of the ether is precisely like any other part everywhere and always, and there are no such distinctions in it as correspond with the elemental forms of matter.           
              There is an ultimate particle of each one of the elements which is practically absolute and known as an atom. The atom retains its identity through all combinations and processes. It may be here or there, move fast or slow, but its atomic form persists  <br> <br> 
              One might infer already that if the ether were structureless, physical laws operative upon such material substances as atoms could not be applicable to it, and so indeed all the evidence we have shows that gravitation is not one of its properties.','jeweltheme_polmo')));
		$wp_customize->add_control(new jeweltheme_polmo_Customize_Textarea_Control( $wp_customize, 'jeweltheme_polmo_about_us_work_with_us_desc', array(
				'label'   => __( 'Description', 'jeweltheme_polmo' ),
				'section' => 'jeweltheme_polmo_about_us_work_with_us',
				'settings'   => 'jeweltheme_polmo_about_us_work_with_us_desc',
				'priority' => 3
		)) );

	}




    /**************	Subscriber Section ***************/	
	if ( class_exists( 'WP_Customize_Panel' ) ){
	
		$wp_customize->add_section( 'jeweltheme_polmo_subscriber_section' , array(
			'title'       => __( 'Subscribe Section', 'jeweltheme_polmo' ),
			'priority'    => 34,
			'capability' => 'edit_theme_options',
			));

		/* Subscribe Title */
		$wp_customize->add_setting( 'jeweltheme_polmo_subscriber_title', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('Polmo WordPress Version Is Live!','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_subscriber_title', array(
			'label'    => __( 'Title', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_subscriber_section',
			'settings' => 'jeweltheme_polmo_subscriber_title',
			'priority'    => 1,
		));		

		/* Subscribe Description */
		$wp_customize->add_setting( 'jeweltheme_polmo_subscriber_desc', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('To get Polmo WordPress version subscribe now. Polmo theme is ablolutely free for your business or personal use.','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_subscriber_desc', array(
			'label'    => __( 'Description', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_subscriber_section',
			'settings' => 'jeweltheme_polmo_subscriber_desc',
			'priority'    => 2,
		));

		/* Subscribe Parallax Image */
		$wp_customize->add_setting( 'jeweltheme_polmo_subscriber_image', array('sanitize_callback' => 'esc_url_raw', 'default' => get_template_directory_uri() . '/images/background/1.jpg'));
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'jeweltheme_polmo_subscriber_image', array(
			'label'    => __( 'Parallax Image', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_subscriber_section',
			'settings' => 'jeweltheme_polmo_subscriber_image',
			'priority'    => 3,
			)));

		/* Subscriber Button Text */
		$wp_customize->add_setting( 'jeweltheme_polmo_subscriber_button', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('Subscribe Now','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_subscriber_button', array(
			'label'    => __( 'Subscriber Button Text', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_subscriber_section',
			'settings' => 'jeweltheme_polmo_subscriber_button',
			'priority'    => 4,
			));		

		/* Subscriber URL */
		$wp_customize->add_setting( 'jeweltheme_polmo_subscriber_url', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('http://jeweltheme.com/product/polmo-lite','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_subscriber_url', array(
			'label'    => __( 'Subscriber URL', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_subscriber_section',
			'settings' => 'jeweltheme_polmo_subscriber_url',
			'priority'    => 5,
			));				
	}




    /**************	Portfolio Section ***************/	
	if ( class_exists( 'WP_Customize_Panel' ) ){
	
		$wp_customize->add_section( 'jeweltheme_polmo_portfolio_section' , array(
			'title'       => __( 'Portfolio Section', 'jeweltheme_polmo' ),
			'priority'    => 34,
			'capability' => 'edit_theme_options',
			));

		/* Portfolio Heading Title */
		$wp_customize->add_setting( 'jeweltheme_polmo_portfolio_title', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('<span>Featured</span> Projects','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_portfolio_title', array(
			'label'    => __( 'Heading Title', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_portfolio_section',
			'settings' => 'jeweltheme_polmo_portfolio_title',
			'priority'    => 1,
		));		

		/* Portfolio Description */
		$wp_customize->add_setting( 'jeweltheme_polmo_portfolio_desc', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('Suppose then that such rings were produced in a medium without friction as the ether is believed to be, they would be permanent structures with a variety of properties.','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_portfolio_desc', array(
			'label'    => __( 'Description', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_portfolio_section',
			'settings' => 'jeweltheme_polmo_portfolio_desc',
			'priority'    => 2,
		));

		$wp_customize->add_setting( 'jeweltheme_polmo_portfolio_posts', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('8','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_portfolio_posts', array(
			'label'    => __( 'No. of Portfolio\'s', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_portfolio_section',
			'settings' => 'jeweltheme_polmo_portfolio_posts',
			'priority' => 3,
			));		
			
	}


    /**************	Sponsors Section ***************/	
	if ( class_exists( 'WP_Customize_Panel' ) ){
		$wp_customize->add_panel( 'panel_sponsors', array(
			'priority' => 36,
			'capability' => 'edit_theme_options',
			'theme_supports' => '',
			'title' => __( 'Sponsors Section', 'jeweltheme_polmo' )
			) );

		$wp_customize->add_section( 'jeweltheme_polmo_sponsors_section' , array(
			'title'       => __( 'Heading', 'jeweltheme_polmo' ),
			'priority'    => 36,
			'panel' => 'panel_sponsors'
			));


		/* Sponsors Heading */
		$wp_customize->add_setting( 'jeweltheme_polmo_sponsors_title', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('<span>Our</span> Elite Sponsors','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_sponsors_title', array(
			'label'    => __( 'Heading Title', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_sponsors_section',
			'settings' => 'jeweltheme_polmo_sponsors_title',
			'priority'    => 1,
			));		


		/* Sponsor 1 */
		$wp_customize->add_section( 'jeweltheme_polmo_sponsors_1' , array(
			'title'       => __( 'Sponsor 1', 'jeweltheme_polmo' ),
			'priority'    => 36,
			'capability' => 'edit_theme_options',
			'panel' => 'panel_sponsors'
			));

		/* Sponsors Image */
		$wp_customize->add_setting( 'jeweltheme_polmo_sponsors_image_1', array('sanitize_callback' => 'esc_url_raw', 'default' => get_template_directory_uri() . '/images/sponsors/1.png'));
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'jeweltheme_polmo_sponsors_image_1', array(
			'label'    => __( 'Image', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_sponsors_1',
			'settings' => 'jeweltheme_polmo_sponsors_image_1',
			'priority'    => 1,
			)));
		$wp_customize->add_setting( 'jeweltheme_polmo_sponsors_url_1', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('http://jeweltheme.com','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_sponsors_url_1', array(
			'label'    => __( 'URL', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_sponsors_1',
			'settings' => 'jeweltheme_polmo_sponsors_url_1',
			'priority'    => 2,
			));		


		/* Sponsor 2 */
		$wp_customize->add_section( 'jeweltheme_polmo_sponsors_2' , array(
			'title'       => __( 'Sponsor 2', 'jeweltheme_polmo' ),
			'priority'    => 36,
			'capability' => 'edit_theme_options',
			'panel' => 'panel_sponsors'
			));

		/* Sponsors Image */
		$wp_customize->add_setting( 'jeweltheme_polmo_sponsors_image_2', array('sanitize_callback' => 'esc_url_raw', 'default' => get_template_directory_uri() . '/images/sponsors/2.png'));
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'jeweltheme_polmo_sponsors_image_2', array(
			'label'    => __( 'Image', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_sponsors_2',
			'settings' => 'jeweltheme_polmo_sponsors_image_2',
			'priority'    => 1,
			)));
		$wp_customize->add_setting( 'jeweltheme_polmo_sponsors_url_2', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('http://jeweltheme.com','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_sponsors_url_2', array(
			'label'    => __( 'URL', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_sponsors_2',
			'settings' => 'jeweltheme_polmo_sponsors_url_2',
			'priority'    => 2,
			));		


		/* Sponsor 3 */
		$wp_customize->add_section( 'jeweltheme_polmo_sponsors_3' , array(
			'title'       => __( 'Sponsor 3', 'jeweltheme_polmo' ),
			'priority'    => 36,
			'capability' => 'edit_theme_options',
			'panel' => 'panel_sponsors'
			));

		/* Sponsors Image */
		$wp_customize->add_setting( 'jeweltheme_polmo_sponsors_image_3', array('sanitize_callback' => 'esc_url_raw', 'default' => get_template_directory_uri() . '/images/sponsors/3.png'));
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'jeweltheme_polmo_sponsors_image_3', array(
			'label'    => __( 'Image', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_sponsors_3',
			'settings' => 'jeweltheme_polmo_sponsors_image_3',
			'priority'    => 1,
			)));
		$wp_customize->add_setting( 'jeweltheme_polmo_sponsors_url_3', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('http://jeweltheme.com','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_sponsors_url_3', array(
			'label'    => __( 'URL', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_sponsors_3',
			'settings' => 'jeweltheme_polmo_sponsors_url_3',
			'priority'    => 2,
			));		


		/* Sponsor 4 */
		$wp_customize->add_section( 'jeweltheme_polmo_sponsors_4' , array(
			'title'       => __( 'Sponsor 4', 'jeweltheme_polmo' ),
			'priority'    => 36,
			'capability' => 'edit_theme_options',
			'panel' => 'panel_sponsors'
			));

		/* Subscribe Image 4*/
		$wp_customize->add_setting( 'jeweltheme_polmo_sponsors_image_4', array('sanitize_callback' => 'esc_url_raw', 'default' => get_template_directory_uri() . '/images/sponsors/4.png'));
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'jeweltheme_polmo_sponsors_image_4', array(
			'label'    => __( 'Image', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_sponsors_4',
			'settings' => 'jeweltheme_polmo_sponsors_image_4',
			'priority'    => 1,
			)));
		$wp_customize->add_setting( 'jeweltheme_polmo_sponsors_url_4', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('http://jeweltheme.com','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_sponsors_url_4', array(
			'label'    => __( 'URL', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_sponsors_4',
			'settings' => 'jeweltheme_polmo_sponsors_url_4',
			'priority'    => 2,
			));		
		
	}


	/**************	Blog Section ***************/	
	if ( class_exists( 'WP_Customize_Panel' ) ){
		$wp_customize->add_panel( 'panel_blog', array(
			'priority' => 36,
			'capability' => 'edit_theme_options',
			'theme_supports' => '',
			'title' => __( 'Blog Section', 'jeweltheme_polmo' )
			) );

		$wp_customize->add_section( 'jeweltheme_polmo_blog_section' , array(
			'title'       => __( 'Heading', 'jeweltheme_polmo' ),
			'priority'    => 36,
			'panel' => 'panel_blog'
			));

		$wp_customize->add_setting( 'jeweltheme_polmo_blog_heading', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('<span>Our</span> Latest Blog Posts','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_blog_heading', array(
			'label'    => __( 'Heading Title', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_blog_section',
			'settings' => 'jeweltheme_polmo_blog_heading',
			'priority'    => 2,
			));		
	}



	/**************	Testimonial Section ***************/	
	if ( class_exists( 'WP_Customize_Panel' ) ){
		$wp_customize->add_section( 'jeweltheme_polmo_testimonial_section' , array(
			'title'       => __( 'Testimonial Section', 'jeweltheme_polmo' ),
			'priority'    => 37,
			'capability' => 'edit_theme_options',
			));

		$wp_customize->add_setting( 'jeweltheme_polmo_testimonial_heading', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('Our Testimonials','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_testimonial_heading', array(
			'label'    => __( 'Heading Title', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_testimonial_section',
			'settings' => 'jeweltheme_polmo_testimonial_heading',
			'priority'    => 1,
			));		

		$wp_customize->add_setting( 'jeweltheme_polmo_testimonial_heading_category', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('','jeweltheme_polmo')));
		$wp_customize->add_control(new Jewel_Theme_Polmo_Category_Dropdown_Customize_Control( $wp_customize, 'jeweltheme_polmo_testimonial_heading_category', array(
			'label'   => __( 'Catogory', 'jeweltheme_polmo' ),
			'section' => 'jeweltheme_polmo_testimonial_section',
			'settings'   => 'jeweltheme_polmo_testimonial_heading_category',
			'priority' => 2
			)) );
		$wp_customize->add_setting( 'jeweltheme_polmo_testimonial_posts', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('3','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_testimonial_posts', array(
			'label'    => __( 'No. of Testimonials', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_testimonial_section',
			'settings' => 'jeweltheme_polmo_testimonial_posts',
			'priority'    => 3,
			));		
	}



	/**************	MAP & Contact Section ***************/	
	if ( class_exists( 'WP_Customize_Panel' ) ){
		$wp_customize->add_section( 'jeweltheme_polmo_map_contact_section' , array(
			'title'       => __( 'MAP & Contact Section', 'jeweltheme_polmo' ),
			'priority'    => 37,
			'capability' => 'edit_theme_options',
			));

		$wp_customize->add_setting( 'jeweltheme_polmo_map_contact_heading', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('<span>Contact</span> With Us','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_map_contact_heading', array(
			'label'    => __( 'Contact Heading', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_map_contact_section',
			'settings' => 'jeweltheme_polmo_map_contact_heading',
			'priority'    => 1,
			));		

		$wp_customize->add_setting( 'jeweltheme_polmo_map_contact_shortcode', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('[contact-form-7 id="1234" title="Contact form 1"]','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_map_contact_shortcode', array(
			'label'    => __( 'Contact Form Shortcode', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_map_contact_section',
			'settings' => 'jeweltheme_polmo_map_contact_shortcode',
			'priority'    => 2,
			));		


		$wp_customize->add_setting( 'jeweltheme_polmo_map_contact_center', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('-37.817331, 144.955652','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_map_contact_center', array(
			'label'    => __( 'Contact Center Lat,Lang', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_map_contact_section',
			'settings' => 'jeweltheme_polmo_map_contact_center',
			'priority'    => 3,
			));		

		$wp_customize->add_setting( 'jeweltheme_polmo_map_contact_lat_lang', array('sanitize_callback' => 'jeweltheme_polmo_sanitize_text','default' => __('23.709921, 90.407143','jeweltheme_polmo')));
		$wp_customize->add_control( 'jeweltheme_polmo_map_contact_lat_lang', array(
			'label'    => __( 'Contact Lat & Lang', 'jeweltheme_polmo' ),
			'section'  => 'jeweltheme_polmo_map_contact_section',
			'settings' => 'jeweltheme_polmo_map_contact_lat_lang',
			'priority'    => 3,
			));		

	}



}
add_action( 'customize_register', 'jeweltheme_polmo_customize_register' );


function jeweltheme_polmo_customize_preview_js() {
	wp_enqueue_script( 'jeweltheme_polmo_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'jeweltheme_polmo_customize_preview_js' );


function jeweltheme_polmo_sanitize_text( $input ) {
    return wp_kses_post( force_balance_tags( $input ) );
}


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */

function polmo_lite_custom_customize_enqueue() {
	wp_enqueue_script( 'polmo_lite-custom-customize', get_template_directory_uri() . '/js/customizer.js', array( 'jquery', 'customize-controls' ), false, true );
}
add_action( 'customize_controls_enqueue_scripts', 'polmo_lite_custom_customize_enqueue' );