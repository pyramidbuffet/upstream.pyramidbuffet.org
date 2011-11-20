<?php if ( post_password_required() ) : ?>
				<p><?php _e( 'This post is password protected. Enter the password to view any comments.', 'ari' ); ?></p>
<?php
		/* Stop the rest of comments.php from being processed,
		 * but don't kill the script entirely -- we still have
		 * to fully load the template.
		 */
		return;
	endif;
?>

<?php
	// You can start editing here -- including this comment!
?>

<div id="comments-content" class="clearfix">

<?php if ( have_comments() ) : ?>

			<h3 id="comments"><?php
			printf( _n( 'One Comment', 'Comments (%1$s)', get_comments_number(), 'ari' ),
			number_format_i18n( get_comments_number() ), '' . get_the_title() . '' );
			?></h3>

			<ol class="commentlist">
				<?php wp_list_comments( array( 'callback' => 'ari_comment' ) ); ?>
			</ol>

<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
				<p class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'ari') ); ?></p>
				<p class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'ari') ); ?></p>
<?php endif; // check for comment navigation ?>

<?php endif; // end have_comments() ?>

<?php comment_form(
array(
	'title_reply' => __( 'Leave a Reply', 'ari' ),
	'comment_notes_before' =>__( '<p class="comment-notes">Required fields are marked <span class="required">*</span></p>', 'ari'),
	'comment_notes_after' => '',
	'comment_field'  => '<p class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'noun', 'ari' ) . '</label><br/><textarea id="comment" name="comment" rows="8" 	aria-required="true"></textarea></p>',
)
); ?>

</div>
<!--end Comments Content-->