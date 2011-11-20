<?php get_header(); ?>

<div id="main">
	<div id="content">

<?php if ( have_posts() ) : ?>
	<h1 class="archive"><?php echo $wp_query->found_posts; ?> <?php printf( __( 'Search Results for <strong>%s</strong>', 'ari' ), '' . get_search_query() . '' ); ?></h1>
	
	<?php get_template_part( 'loop' ); ?>
	
	<?php else : ?>
	<h1 class="archive"><strong><?php _e( 'No Search Result Found', 'ari' ); ?></strong></h1>
		<div class="post">
			<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'ari' ); ?></p>
		</div>
<?php endif; ?>

	</div>
	<!--end Content-->
	
<?php get_sidebar('secondary'); ?>

</div>
<!--end Main-->

<?php get_footer(); ?>