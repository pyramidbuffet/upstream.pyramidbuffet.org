<?php get_header(); ?>

<div id="main">
	<div id="content">

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<h2><?php the_title(); ?></h2>

			<?php the_content(); ?>
			<div class="clear"></div>
			
			<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'ari' ), 'after' => '</div>' ) ); ?>
						
		<?php if ( get_the_author_meta( 'description' ) ) : // If a user has filled out their description, show a bio on their entries  ?>
					<div id="author-info" class="clearfix">
						<div id="author-avatar">
							<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'ari_author_bio_avatar_size', 50 ) ); ?>
						</div>
						<div id="author-description">
							<h2><?php printf( __( 'About <span>%s</span>', 'ari' ), get_the_author() ); ?></h2>
							<p><?php the_author_meta( 'description' ); ?></p>
						</div>
					</div><!-- end Author Info -->
		<?php endif; ?>
						
		<p class="meta"><span><?php the_time('d. F Y') ?> <?php _e( 'by', 'ari' ); ?> <?php the_author() ?></span><br/>				

				<?php if ( count( get_the_category() ) ) : ?>
					<?php printf( __( 'Categories: %2$s', 'ari' ), 'entry-utility-prep entry-utility-prep-cat-links', get_the_category_list( ', ' ) ); ?>
					|
				<?php endif; ?>
				<?php
					$tags_list = get_the_tag_list( '', ', ' );
					if ( $tags_list ):
				?>
					<?php printf( __( 'Tags: %2$s', 'ari' ), 'entry-utility-prep entry-utility-prep-tag-links', $tags_list ); ?>
					|
				<?php endif; ?>
				<?php comments_popup_link( __( 'Leave a comment', 'ari' ), __( '1 comment', 'ari' ), __( '% comments', 'ari' ) ); ?>
				<?php edit_post_link( __( 'Edit &rarr;', 'ari' ), '| ', '' ); ?></p>


				<?php comments_template( '', true ); ?>

<?php endwhile; // end of the loop. ?>

	</div>
	<!--end Post-->
	
		<p class="previous"><?php previous_post_link( '%link',  __( '&larr;  Previous Post', 'Previous post link', 'ari' ) ); ?></p>
		<p class="next"><?php next_post_link( '%link', __( 'Next Post &rarr;', 'Next post link', 'ari' ) ); ?></p>
	
	</div>
	<!--end Content-->

<?php get_sidebar('secondary'); ?>

</div>
<!--end Main-->

<?php get_footer(); ?>