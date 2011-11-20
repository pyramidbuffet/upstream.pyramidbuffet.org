<?php
/**
 * The right, additional Sidebar containing the secondary widget areas.
 */
?>

<div id="sidebar-secondary">

<?php
	if ( is_active_sidebar( 'secondary-widget-area' ) ) : ?>

			<ul class="sidebar">
				<?php dynamic_sidebar( 'secondary-widget-area' ); ?>
			</ul>

<?php endif; ?>

</div>
<!--end Sidebar Secondary-->