<?php get_header(); ?>

<div id="main">
	<div id="content">
	
<?php if ( have_posts() ) the_post(); ?>

			<h1 class="archive">
<?php if ( is_day() ) : ?>
				<?php printf( __( 'Daily Archives for <strong>%s</strong>', 'ari' ), get_the_date() ); ?>
<?php elseif ( is_month() ) : ?>
				<?php printf( __( 'Monthly Archives for <strong>%s</strong>', 'ari' ), get_the_date('F Y') ); ?>
<?php elseif ( is_year() ) : ?>
				<?php printf( __( 'Yearly Archives for <strong>%s</strong>', 'ari' ), get_the_date('Y') ); ?>
<?php else : ?>
				<?php _e( 'Blog Archives', 'ari' ); ?>
<?php endif; ?>
			</h1>

<?php rewind_posts();
	 get_template_part( 'loop', 'archive' );
?>
	</div>
	<!--end Content-->

<?php get_sidebar('secondary'); ?>

</div>
<!--end Main-->

<?php get_footer(); ?>