<?php get_header(); ?>

<div id="main">
	<div id="content">
	
	<h1 class="archive"><?php printf( __( 'Tag Archives for <strong>%s</strong>', 'ari' ), '' . single_tag_title( '', false ) . '' ); ?></h1>
		<?php get_template_part( 'loop' ); ?>
	</div>
	<!--end Content-->

<?php get_sidebar('secondary'); ?>

</div>
<!--end Main-->

<?php get_footer(); ?>