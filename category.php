<?php get_header(); ?>

<div id="main">
	<div id="content">
	<h1 class="archive"><?php printf( __( 'Category Archives for <strong>%s</strong>', 'ari' ), '' . single_cat_title( '', false ) . '' ); ?></h1>
	
		<?php
			$category_description = category_description();
			if ( ! empty( $category_description ) )
				echo '' . $category_description . '';
			get_template_part( 'loop', 'category' );
		?>
			
	</div>
	<!--end Content-->

<?php get_sidebar('secondary'); ?>

</div>
<!--end Main-->

<?php get_footer(); ?>