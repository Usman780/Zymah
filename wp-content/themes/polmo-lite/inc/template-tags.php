<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Polmo
 */

if ( ! function_exists( 'the_posts_navigation' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 */
function the_posts_navigation() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	?>
	<nav class="navigation posts-navigation" role="navigation">
		<h2 class="screen-reader-text"><?php esc_html_e( 'Posts navigation', 'polmo-lite' ); ?></h2>
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( esc_html__( 'Older posts', 'polmo-lite' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( esc_html__( 'Newer posts', 'polmo-lite' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'the_post_navigation' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 */
function the_post_navigation() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h2 class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'polmo-lite' ); ?></h2>
		<div class="nav-links">
			<?php
				previous_post_link( '<div class="nav-previous">%link</div>', '%title' );
				next_post_link( '<div class="nav-next">%link</div>', '%title' );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'jeweltheme_polmo_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function jeweltheme_polmo_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		esc_html_x( 'Posted on %s', 'post date', 'polmo-lite' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$byline = sprintf(
		esc_html_x( 'by %s', 'post author', 'polmo-lite' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

}
endif;

if ( ! function_exists( 'jeweltheme_polmo_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function jeweltheme_polmo_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' == get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
			/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', esc_html__( ', ', 'polmo-lite' ) );
		if ( $tags_list ) {
			printf( '<li>' . esc_html__( '%1$s', 'polmo-lite' ) . '</li>', $tags_list ); // WPCS: XSS OK.
		}
	}

	edit_post_link( esc_html__( 'Edit', 'polmo-lite' ), '<span class="edit-link">', '</span>' );
}
endif;

if ( ! function_exists( 'the_archive_title' ) ) :
/**
 * Shim for `the_archive_title()`.
 *
 * Display the archive title based on the queried object.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the title. Default empty.
 * @param string $after  Optional. Content to append to the title. Default empty.
 */
function the_archive_title( $before = '', $after = '' ) {
	if ( is_category() ) {
		$title = sprintf( esc_html__( 'Category: %s', 'polmo-lite' ), single_cat_title( '', false ) );
	} elseif ( is_tag() ) {
		$title = sprintf( esc_html__( 'Tag: %s', 'polmo-lite' ), single_tag_title( '', false ) );
	} elseif ( is_author() ) {
		$title = sprintf( esc_html__( 'Author: %s', 'polmo-lite' ), '<span class="vcard">' . get_the_author() . '</span>' );
	} elseif ( is_year() ) {
		$title = sprintf( esc_html__( 'Year: %s', 'polmo-lite' ), get_the_date( esc_html_x( 'Y', 'yearly archives date format', 'polmo-lite' ) ) );
	} elseif ( is_month() ) {
		$title = sprintf( esc_html__( 'Month: %s', 'polmo-lite' ), get_the_date( esc_html_x( 'F Y', 'monthly archives date format', 'polmo-lite' ) ) );
	} elseif ( is_day() ) {
		$title = sprintf( esc_html__( 'Day: %s', 'polmo-lite' ), get_the_date( esc_html_x( 'F j, Y', 'daily archives date format', 'polmo-lite' ) ) );
	} elseif ( is_tax( 'post_format' ) ) {
		if ( is_tax( 'post_format', 'post-format-aside' ) ) {
			$title = esc_html_x( 'Asides', 'post format archive title', 'polmo-lite' );
		} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
			$title = esc_html_x( 'Galleries', 'post format archive title', 'polmo-lite' );
		} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
			$title = esc_html_x( 'Images', 'post format archive title', 'polmo-lite' );
		} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
			$title = esc_html_x( 'Videos', 'post format archive title', 'polmo-lite' );
		} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
			$title = esc_html_x( 'Quotes', 'post format archive title', 'polmo-lite' );
		} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
			$title = esc_html_x( 'Links', 'post format archive title', 'polmo-lite' );
		} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
			$title = esc_html_x( 'Statuses', 'post format archive title', 'polmo-lite' );
		} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
			$title = esc_html_x( 'Audio', 'post format archive title', 'polmo-lite' );
		} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
			$title = esc_html_x( 'Chats', 'post format archive title', 'polmo-lite' );
		}
	} elseif ( is_post_type_archive() ) {
		$title = sprintf( esc_html__( 'Archives: %s', 'polmo-lite' ), post_type_archive_title( '', false ) );
	} elseif ( is_tax() ) {
		$tax = get_taxonomy( get_queried_object()->taxonomy );
		/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
		$title = sprintf( esc_html__( '%1$s: %2$s', 'polmo-lite' ), $tax->labels->singular_name, single_term_title( '', false ) );
	} else {
		$title = esc_html__( 'Archives', 'polmo-lite' );
	}

	/**
	 * Filter the archive title.
	 *
	 * @param string $title Archive title to be displayed.
	 */
	$title = apply_filters( 'get_the_archive_title', $title );

	if ( ! empty( $title ) ) {
		echo $before . $title . $after;  // WPCS: XSS OK.
	}
}
endif;

if ( ! function_exists( 'the_archive_description' ) ) :
/**
 * Shim for `the_archive_description()`.
 *
 * Display category, tag, or term description.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the description. Default empty.
 * @param string $after  Optional. Content to append to the description. Default empty.
 */
function the_archive_description( $before = '', $after = '' ) {
	$description = apply_filters( 'get_the_archive_description', term_description() );

	if ( ! empty( $description ) ) {
		/**
		 * Filter the archive description.
		 *
		 * @see term_description()
		 *
		 * @param string $description Archive description to be displayed.
		 */
		echo $before . $description . $after;  // WPCS: XSS OK.
	}
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function jeweltheme_polmo_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'jeweltheme_polmo_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'jeweltheme_polmo_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so jeweltheme_polmo_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so jeweltheme_polmo_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in jeweltheme_polmo_categorized_blog.
 */
function jeweltheme_polmo_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'jeweltheme_polmo_categories' );
}
add_action( 'edit_category', 'jeweltheme_polmo_category_transient_flusher' );
add_action( 'save_post',     'jeweltheme_polmo_category_transient_flusher' );


function jeweltheme_polmo_post_meta(){ ?>
	<div class="post-meta">
		<div class="entry-meta">
			<div class="author pull-left">
				<?php echo sprintf(
					esc_html_x( 'by %s', 'post author', 'polmo-lite' ),
					'<span class="author-name"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
					);
					?>
			</div><!-- /.author -->
			<div class="comments">
				<span class="comments-icon"><i class="fa fa-comments"></i></span>

				<?php
				$num_comments = get_comments_number(); // get_comments_number returns only a numeric value

				if ( comments_open() ) {
					if ( $num_comments == 0 ) {
						$comments = __('No Comments', 'polmo-lite');
					} elseif ( $num_comments > 1 ) {
						$comments = $num_comments . __(' Comments','polmo-lite');
					} else {
						$comments = __('1 Comment','polmo-lite');
					}
					echo wp_kses( $write_comments = '<span class="count">'. $comments .'</span>', 'polmo-lite' ) ;
				} else {
					echo esc_html( $write_comments =  __('Comments off','polmo-lite') );
				}

				?>
			</div><!-- /.comments -->
		</div><!-- /.entry-meta -->
	</div><!-- /.post-meta -->
<?php }


function jeweltheme_get_avatar_url($get_avatar){
    preg_match("/src='(.*?)'/i", $get_avatar, $matches);
    return $matches[1]; 
}


function jeweltheme_author_bio(){ ?>
    <div class="author-bio-container media">
        <div class="author-avatar media-left pull-left">
            <img class="img-circle media-boject" src="<?php echo get_avatar_url(get_the_author_meta( 'ID' ), 80 ); ?>" alt="Author Avatar">
        </div><!-- /.author-avatar -->
        <div class="author-details media-body">
            <div class="details-top">
                <?php echo esc_attr('by','polmo-lite');?> <span class="author-name"><?php echo get_the_author_meta('display_name');?></span>
            </div><!-- /.details-top -->
            <p class="about-author">
                <?php echo get_the_author_meta('description');?>
            </p><!-- /.about-author -->
        </div><!-- /.author-details -->
    </div><!-- /.author-bio-container -->

<?php }


function jeweltheme_comment_reply_link_filter($content){
    return '<div class="btn reply-btn">' . $content . '</div>';

    var_dump($content);
}
add_filter('comment_reply_link', 'jeweltheme_comment_reply_link_filter', 99);


/*===================================================================================
 * Polmo Comments
 * =================================================================================*/

if(!function_exists('jeweltheme_polmo_comment')){

    function jeweltheme_polmo_comment($comment, $args, $depth){

        $GLOBALS['comment'] = $comment;
        switch ( $comment->comment_type ) :
        case 'pingback' :
        case 'trackback' :
    ?>
    <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">

        <p>Pingback: <?php comment_author_link(); ?> <?php edit_comment_link( wp_kses( '(Edit)', 'polmo-lite' ), '<span class="edit-link">', '</span>' ); ?></p>
        <?php
        break;
        default :

        global $post;
        ?>

        <li class="comment parent media" id="li-comment-<?php comment_ID(); ?>">
            <div class="comment-top">
            	<div class="comment-author media-left pull-left">
            		<img class="img-circle media-object" src="<?php echo get_avatar_url(get_the_author_meta( 'ID' ), 65 ); ?>" alt="Author Comment Avatar">
            	</div><!-- /.comment-author -->

            	<div class="comment-content media-body">
            		<div class="comment-meta">
            			<span class="author-name">
            				<?php printf( '<span class="name">%1$s</span>', get_comment_author_link()); ?>
            			</span>
            			<span class="comment-reply pull-right">
            				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => wp_kses( 'Reply', 'polmo-lite' ), 'after' => '', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
            			</span> 
            		</div><!-- /.comment-meta -->

            		<p class="comment-description">
            			<?php echo get_comment_text(); ?>
            		</p>
            		<div class="comment-time">
            			<time datetime="<?php the_time( 'c' ); ?>"> <?php the_time('d  M, Y'); ?> <?php echo _e("at", "polmo-lite");?> <?php echo get_comment_time()?></time> 
            		</div>
            	</div><!-- /.comment-content -->
                
            </div><!-- /.comment-top -->

            <?php
            break;
            endswitch; 
        }

} 

if ( ! function_exists( 'jeweltheme_polmo_slider_scripts' ) ) {
	function jeweltheme_polmo_slider_scripts(){
		if (is_page() && basename(get_page_template()) == "front-page.php") { ?>
			<script>
				
				!function(e){"use strict";e(".bxslider").bxSlider({auto:!0,preloadImages:"all",mode:"horizontal",captions:!1,controls:!0,pause:4e3,speed:1200,onSliderLoad:function(){e(".bxslider>li .slide-inner").eq(1).addClass("active-slide"),e(".slide-inner.active-slide .slider-title").addClass("wow animated bounceInDown"),e(".slide-inner.active-slide .slide-description").addClass("wow animated bounceInRight"),e(".slide-inner.active-slide .btn").addClass("wow animated zoomInUp")},onSlideAfter:function(i,n,t){console.log(t),e(".active-slide").removeClass("active-slide"),e(".bxslider>li .slide-inner").eq(t+1).addClass("active-slide"),e(".slide-inner.active-slide").addClass("wow animated bounceInRight")},onSlideBefore:function(){e(".slide-inner.active-slide").removeClass("wow animated bounceInRight"),e(".one.slide-inner.active-slide").removeAttr("style")}}),e(document).ready(function(){function i(){return"ontouchstart"in document.documentElement}function n(){if("undefined"!=typeof google){var n={center:[-37.817331,144.955652],zoom:15,mapTypeControl:!0,mapTypeControlOptions:{style:google.maps.MapTypeControlStyle.DROPDOWN_MENU},navigationControl:!0,scrollwheel:!1,streetViewControl:!0};i()&&(n.draggable=!1),e("#googleMaps").gmap3({map:{options:n},marker:{latLng:[23.709921,90.407143],options:{icon:"images/mapicon.png"}}})}}e("#masthead #main-menu").onePageNav(),n()}),e("#contactform").on("submit",function(i){i.preventDefault(),$this=e(this),e.ajax({type:"POST",url:$this.attr("action"),data:$this.serialize(),success:function(){alert("Message Sent Successfully")}})})}(jQuery);
				

				// ! function(e) {
				// 	"use strict";
				// 	e(".bxslider").bxSlider({
				// 		auto: !0,
				// 		preloadImages: "all",
				// 		mode: "horizontal",
				// 		captions: !1,
				// 		controls: !0,
				// 		pause: 4e3,
				// 		speed: 1200,
				// 		onSliderLoad: function() {
				// 			e(".bxslider>li .slide-inner").eq(1).addClass("active-slide"), e(".slide-inner.active-slide .slider-title").addClass("wow animated bounceInDown"), e(".slide-inner.active-slide .slide-description").addClass("wow animated bounceInRight"), e(".slide-inner.active-slide .btn").addClass("wow animated zoomInUp")
				// 		},
				// 		onSlideAfter: function(i, n, t) {
				// 			console.log(t), e(".active-slide").removeClass("active-slide"), e(".bxslider>li .slide-inner").eq(t + 1).addClass("active-slide"), e(".slide-inner.active-slide").addClass("wow animated bounceInRight")
				// 		},
				// 		onSlideBefore: function() {
				// 			e(".slide-inner.active-slide").removeClass("wow animated bounceInRight"), e(".one.slide-inner.active-slide").removeAttr("style")
				// 		}
				// 	}), e(document).ready(function() {
				// 		function i() {
				// 			return "ontouchstart" in document.documentElement
				// 		}


				// 		e("#masthead #main-menu").onePageNav(), n()
				// 	}), e("#contactform").on("submit", function(i) {
				// 		i.preventDefault(), $this = e(this), e.ajax({
				// 			type: "POST",
				// 			url: $this.attr("action"),
				// 			data: $this.serialize(),
				// 			success: function() {
				// 				alert("Message Sent Successfully")
				// 			}
				// 		})
				// 	})
				// }(jQuery);

		  </script>
		<?php }
	}
}

// Slider Footer Scripts
add_action('wp_footer','jeweltheme_polmo_slider_scripts',100);





if ( ! function_exists( 'jeweltheme_polmo_the_custom_logo' ) ) :
/**
 * Displays the optional custom logo.
 *
 * Does nothing if the custom logo is not available.
 *
 */
function jeweltheme_polmo_the_custom_logo() {
	if ( function_exists( 'the_custom_logo' ) ) {
		the_custom_logo();
	}
}
endif;
