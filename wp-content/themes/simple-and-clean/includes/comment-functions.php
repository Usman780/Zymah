<?php
/**
 * Creates our comment list
 *
 *
 * @package WordPress
 * @subpackage Simple
 * @since 1.0.0
 *
 *
 */
   
function simple_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'simple' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'simple' ), ' ' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
	
		<div class="comment-header">
			<?php echo get_avatar( $comment, 50 ); ?>
			<?php printf( __( '%s', 'simple' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?><br />
			
			<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time pubdate datetime="<?php comment_time( 'c' ); ?>">
			<?php
				/* translators: 1: date, 2: time */
				printf( __( '%1$s at %2$s', 'simple' ), get_comment_date(), get_comment_time() ); ?>
			</time></a>
			<?php edit_comment_link( __( '(Edit)', 'simple' ), ' ' );
			?>
		</div><!-- close comment header -->
		
		<div class="comment-content">
			<?php if ( $comment->comment_approved == '0' ) : ?>
				<em><?php _e( 'Your comment is awaiting moderation.', 'simple' ); ?></em>
				<br />
			<?php endif; ?>
			<?php comment_text(); ?>
		
		</div>

		<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'], 'before' => '<p class="reply">', 'after' => '</p>' ) ) ); ?>
		<!-- #comment-## -->

	<?php
			break;
	endswitch;
}