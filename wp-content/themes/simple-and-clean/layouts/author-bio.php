<div class="author-bio clearfix">

	<?php echo get_avatar( get_the_author_meta('email'), '180' ); ?>
	
	<div class="author-info">
	
		<h3 class="author-name"><?php the_author_link(); ?></h3>
	
		<p class="author-description"><?php the_author_meta('description'); ?></p>
	
		<ul class="network-icons">
			<?php
				
				$instagram_profile = get_the_author_meta( 'instagram_profile' );
				if ( $instagram_profile != '' ) {
					echo '<li class="instagram"><a href="' . esc_url($instagram_profile) . '" rel="author"></a></li>';
				}
				
				$google_profile = get_the_author_meta( 'google_profile' );
				if ( $google_profile != '' ) {
					echo '<li class="google"><a href="' . esc_url($google_profile) . '" rel="author"></a></li>';
				}
				
				$twitter_profile = get_the_author_meta( 'twitter_profile' );
				if ( $twitter_profile != '' ) {
					echo '<li class="twitter"><a href="' . esc_url($twitter_profile) . '"</a></li>';
				}
				
				$facebook_profile = get_the_author_meta( 'facebook_profile' );
				if ( $facebook_profile != '' ) {
					echo '<li class="facebook"><a href="' . esc_url($facebook_profile) . '"></a></li>';
				}
				
				$linkedin_profile = get_the_author_meta( 'linkedin_profile' );
				if ( $linkedin_profile != '' ) {
					echo '<li class="linkedin"><a href="' . esc_url($linkedin_profile) . '"></a></li>';
				}
				
				$youtube_profile = get_the_author_meta( 'youtube_profile' );
				if ( $youtube_profile != '' ) {
					echo '<li class="youtube"><a href="' . esc_url($youtube_profile) . '"></a></li>';
				}
				
				$yelp_profile = get_the_author_meta( 'yelp_profile' );
				if ( $yelp_profile != '' ) {
					echo '<li class="yelp"><a href="' . esc_url($yelp_profile) . '"></a></li>';
				}
				
				$vimeo_profile = get_the_author_meta( 'vimeo_profile' );
				if ( $vimeo_profile != '' ) {
					echo '<li class="vimeo"><a href="' . esc_url($vimeo_profile) . '"></a></li>';
				}
				
				$pinterest_profile = get_the_author_meta( 'pinterest_profile' );
				if ( $pinterest_profile != '' ) {
					echo '<li class="pinterest"><a href="' . esc_url($pinterest_profile) . '"></a></li>';
				}
				
				$flickr_profile = get_the_author_meta( 'flickr_profile' );
				if ( $flickr_profile != '' ) {
					echo '<li class="flickr"><a href="' . esc_url($flickr_profile) . '"></a></li>';
				}
				
				$dribbble_profile = get_the_author_meta( 'dribbble_profile' );
				if ( $dribbble_profile != '' ) {
					echo '<li class="dribbble"><a href="' . esc_url($dribbble_profile) . '"></a></li>';
				}
			
			?>
	
		</ul>
	
	</div><!-- .author-bio-->

</div><!-- .author-bio -->