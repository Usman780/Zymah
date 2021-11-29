<?php if ( ! is_page() ) : ?>

	<p class="post-meta">
		<?php the_time('F j, Y'); ?>&nbsp;&#124;&nbsp;
		<?php the_author_posts_link(); ?>
		<?php if ( comments_open() || '0' != get_comments_number() ) : ?>
			&nbsp;&#124;&nbsp;
			<a href="<?php the_permalink(); ?>#comments"><?php comments_number(__('Comment', 'simple'), __('1 Comment', 'simple'), __('% Comments', 'simple')); ?></a>
		<?php endif; // comments_open() ?>&nbsp;&#124;&nbsp;
		<?php the_category(', '); ?><?php edit_post_link('Edit', '&nbsp;&#124;&nbsp;', ''); ?>
	</p>

<?php endif; // ! is_page ?>