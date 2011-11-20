<?php

/* Make theme available for translation */
/* Translations can be filed in the /languages/ directory */
load_theme_textdomain( 'ari', TEMPLATEPATH . '/languages' );

	$locale = get_locale();
	$locale_file = TEMPLATEPATH . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

/* Set the content width based on the theme's design and stylesheet. */
if ( ! isset( $content_width ) )
	$content_width = 450;

/* Tell WordPress to run ari_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'ari_setup' );

if ( ! function_exists( 'ari_setup' ) ):

function ari_setup() {

	/* This theme styles the visual editor with editor-style.css to match the theme style. */
	add_editor_style();
	
	/* This theme uses post thumbnails */
	add_theme_support( 'post-thumbnails' );

	/* Add default posts and comments RSS feed links to head */
	add_theme_support( 'automatic-feed-links' );

	/* This theme uses wp_nav_menu() in one location. */
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'ari'),
	) );

}
endif;

/* Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link. */
function ari_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'ari_page_menu_args' );


/* Sets the post excerpt length to 40 characters. */
function ari_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'ari_excerpt_length' );


/* Returns a "Continue Reading" link for excerpts */
function ari_continue_reading_link() {
	return ' <a href="'. get_permalink() . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'ari' ) . '</a>';
}

/* Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and ari_continue_reading_link(). */
function ari_auto_excerpt_more( $more ) {
	return ' &hellip;' . ari_continue_reading_link();
}
add_filter( 'excerpt_more', 'ari_auto_excerpt_more' );

/* Adds a pretty "Continue Reading" link to custom post excerpts. */
function ari_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= ari_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'ari_custom_excerpt_more' );


if ( ! function_exists( 'ari_comment' ) ) :

/*  Search form custom styling */
function ari_search_form( $form ) {

    $form = '<form role="search" method="get" id="searchform" action="'.get_bloginfo('url').'" >
    <input type="text" class="search-input" value="' . get_search_query() . '" name="s" id="s" />
    <input type="submit" id="searchsubmit" value="'. esc_attr__('Search', 'ari') .'" />
    </form>';

    return $form;
}
add_filter( 'get_search_form', 'ari_search_form' );

/* Template for comments and pingbacks. */
function ari_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>">
		<div class="comment-gravatar"><?php echo get_avatar( $comment, 50 ); ?></div>
		
		<div class="comment-body">
		<div class="comment-meta commentmetadata"><?php printf( __( '%s <span class="says">says</span>', 'ari' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?> <a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
			<?php
				/* translators: 1: date, 2: time */
				printf( __( '%1$s at %2$s', 'ari' ), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( 'Edit &rarr;', 'ari' ), ' ' );
			?>
		</div><!-- .comment-meta .commentmetadata -->

		<?php comment_text(); ?>
		
		<?php if ( $comment->comment_approved == '0' ) : ?>
		<p class="moderation"><?php _e( 'Your comment is awaiting moderation.', 'ari' ); ?></p>
		<?php endif; ?>

		<div class="reply">
			<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		</div><!-- .reply -->
		
		</div>
		<!--comment Body-->
		
	</div><!-- #comment-##  -->

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'ari' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __('(Edit)', 'ari'), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}
endif;


/* Register widgetized areas, including two sidebars and four widget-ready columns in the footer. */
function ari_widgets_init() {
	// Primary Widget area (left, fixed sidebar)
	register_sidebar( array(
		'name' => __( 'Primary Widget Area', 'ari' ),
		'id' => 'primary-widget-area',
		'description' => __( 'Here you can put one or two of your main widgets (like an intro text, your page navigation or some social site links) in your left sidebar. The sidebar is fixed, so the widgets content will always be visible, even when scrolling down the page.', 'ari' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Secondary Widget area (right, additional sidebar)
	register_sidebar( array(
		'name' => __( 'Secondary Widget Area', 'ari' ),
		'id' => 'secondary-widget-area',
		'description' => __( 'Here you can put all the additional widgets for your right sidebar.', 'ari' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

}
/* Register sidebars by running ari_widgets_init() on the widgets_init hook. */
add_action( 'widgets_init', 'ari_widgets_init' );

/* Removes the default styles that are packaged with the Recent Comments widget. */
function ari_remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}
add_action( 'widgets_init', 'ari_remove_recent_comments_style' );

if ( ! function_exists( 'ari_posted_on' ) ) :
/* Prints HTML with meta information for the current post—date/time and author. */
function ari_posted_on() {
	printf( __( '<span class="%1$s">Posted on</span> %2$s <span class="meta-sep">by</span> %3$s', 'ari' ),
		'meta-prep meta-prep-author',
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a>',
			get_permalink(),
			esc_attr( get_the_time() ),
			get_the_date()
		),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
			get_author_posts_url( get_the_author_meta( 'ID' ) ),
			sprintf( esc_attr__( 'View all posts by %s', 'ari' ), get_the_author() ),
			get_the_author()
		)
	);
}
endif;

/* Custom Ari Social Links Widget */
class Ari_SocialLinks_Widget extends WP_Widget {
	function Ari_SocialLinks_Widget() {
		$widget_ops = array(
			'classname' => 'widget_social_links',
			'description' => 'A list with your social profile links' );
		$this->WP_Widget('social_links', 'Ari Social Links', $widget_ops);
	}
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		echo $before_widget;
		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
		
		$rss_title = empty($instance['rss_title']) ? ' ' : apply_filters('widget_rss_title', $instance['rss_title']);	
		$rss_url = empty($instance['rss_url']) ? ' ' : apply_filters('widget_rss_url', $instance['rss_url']);
		$twitter_title = empty($instance['twitter_title']) ? ' ' : apply_filters('widget_twitter_title', $instance['twitter_title']);	
		$twitter_url = empty($instance['twitter_url']) ? ' ' : apply_filters('widget_twitter_url', $instance['twitter_url']);	
		$fb_title = empty($instance['fb_title']) ? ' ' : apply_filters('widget_fb_title', $instance['fb_title']);
		$fb_url = empty($instance['fb_url']) ? ' ' : apply_filters('widget_fb_url', $instance['fb_url']);
		$googleplus_title = empty($instance['googleplus_title']) ? ' ' : apply_filters('widget_googleplus_title', $instance['googleplus_title']);
		$googleplus_url = empty($instance['googleplus_url']) ? ' ' : apply_filters('widget_googleplus_url', $instance['googleplus_url']);
		$flickr_title = empty($instance['flickr_title']) ? ' ' : apply_filters('widget_flickr_title', $instance['flickr_title']);
		$flickr_url = empty($instance['flickr_url']) ? ' ' : apply_filters('widget_flickr_url', $instance['flickr_url']);
		$vimeo_title = empty($instance['vimeo_title']) ? ' ' : apply_filters('widget_vimeo_title', $instance['vimeo_title']);
		$vimeo_url = empty($instance['vimeo_url']) ? ' ' : apply_filters('widget_vimeo_url', $instance['vimeo_url']);
		$xing_title = empty($instance['xing_title']) ? ' ' : apply_filters('widget_xing_title', $instance['xing_title']);
		$xing_url = empty($instance['xing_url']) ? ' ' : apply_filters('widget_xing_url', $instance['xing_url']);
		$linkedin_title = empty($instance['linkedin_title']) ? ' ' : apply_filters('widget_linkedin_title', $instance['linkedin_title']);
		$linkedin_url = empty($instance['linkedin_url']) ? ' ' : apply_filters('widget_linkedin_url', $instance['linkedin_url']);
		$delicious_title = empty($instance['delicious_title']) ? ' ' : apply_filters('widget_delicious_title', $instance['delicious_title']);
		$delicious_url = empty($instance['delicious_url']) ? ' ' : apply_filters('widget_delicious_url', $instance['delicious_url']);
		
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
		echo '<ul>';
	if($rss_title == ' ') { echo ''; } else {  echo  '<li class="widget_sociallinks"><a href=" '. $rss_url .'" class="rss" target="_blank">'. $rss_title .'</a></li>'; }
		if($twitter_title == ' ') { echo ''; } else {  echo  '<li class="widget_sociallinks"><a href=" '. $twitter_url .'" class="twitter" target="_blank">'. $twitter_title .'</a></li>'; }
		if($fb_title == ' ') { echo ''; } else {  echo  '<li class="widget_sociallinks"><a href=" '. $fb_url .'" class="facebook" target="_blank">'. $fb_title .'</a></li>'; }
		if($googleplus_title == ' ') { echo ''; } else {  echo  '<li class="widget_sociallinks"><a href=" '. $googleplus_url .'" class="googleplus" target="_blank">'. $googleplus_title .'</a></li>'; }
		if($flickr_title == ' ') { echo ''; } else {  echo  '<li class="widget_sociallinks"><a href=" '. $flickr_url .'" class="flickr" target="_blank">'. $flickr_title .'</a></li>'; }
		if($vimeo_title == ' ') { echo ''; } else {  echo  '  <li class="widget_sociallinks"><a href=" '. $vimeo_url .'" class="vimeo" target="_blank">'. $vimeo_title .'</a></li>'; }
		if($xing_title == ' ') { echo ''; } else {  echo  '  <li class="widget_sociallinks"><a href=" '. $xing_url .'" class="xing" target="_blank">'. $xing_title .'</a></li>'; }
		if($linkedin_title == ' ') { echo ''; } else {  echo  '<li class="widget_sociallinks"><a href=" '. $linkedin_url .'" class="linkedin" target="_blank">'. $linkedin_title .'</a></li>'; }
		if($delicious_title == ' ') { echo ''; } else {  echo  '<li class="widget_sociallinks"><a href=" '. $delicious_url .'" class="delicious" target="_blank">'. $delicious_title .'</a></li>'; }
		echo '</ul>';
		echo $after_widget;
		
	}
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		
		$instance['rss_title'] = strip_tags($new_instance['rss_title']);
		$instance['rss_url'] = strip_tags($new_instance['rss_url']);
		$instance['twitter_title'] = strip_tags($new_instance['twitter_title']);
		$instance['twitter_url'] = strip_tags($new_instance['twitter_url']);
		$instance['fb_title'] = strip_tags($new_instance['fb_title']);
		$instance['fb_url'] = strip_tags($new_instance['fb_url']);
		$instance['googleplus_title'] = strip_tags($new_instance['googleplus_title']);
		$instance['googleplus_url'] = strip_tags($new_instance['googleplus_url']);
		$instance['flickr_title'] = strip_tags($new_instance['flickr_title']);
		$instance['flickr_url'] = strip_tags($new_instance['flickr_url']);
		$instance['vimeo_title'] = strip_tags($new_instance['vimeo_title']);
		$instance['vimeo_url'] = strip_tags($new_instance['vimeo_url']);		
		$instance['xing_title'] = strip_tags($new_instance['xing_title']);
		$instance['xing_url'] = strip_tags($new_instance['xing_url']);
		$instance['linkedin_title'] = strip_tags($new_instance['linkedin_title']);
		$instance['linkedin_url'] = strip_tags($new_instance['linkedin_url']);
		$instance['delicious_title'] = strip_tags($new_instance['delicious_title']);
		$instance['delicious_url'] = strip_tags($new_instance['delicious_url']);
		return $instance;
	}
	function form($instance) {
		$instance = wp_parse_args(
		(array) $instance, array( 
			'title' => '',
			'rss_title' => '',
			'rss_url' => '',
			'twitter_title' => '',
			'twitter_url' => '',
			'fb_title' => '',
			'fb_url' => '',
			'googleplus_title' => '',
			'googleplus_url' => '',
			'flickr_title' => '',
			'flickr_url' => '',
			'vimeo_title' => '',
			'vimeo_url' => '',
			'xing_title' => '',
			'xing_url' => '',
			'linkedin_title' => '',
			'linkedin_url' => '',
			'delicious_title' => '',
			'delicious_url' => ''
		) );
		$title = strip_tags($instance['title']);	
		$rss_title = strip_tags($instance['rss_title']);
		$rss_url = strip_tags($instance['rss_url']);
		$twitter_title = strip_tags($instance['twitter_title']);
		$twitter_url = strip_tags($instance['twitter_url']);
		$fb_title = strip_tags($instance['fb_title']);
		$fb_url = strip_tags($instance['fb_url']);
		$googleplus_title = strip_tags($instance['googleplus_title']);
		$googleplus_url = strip_tags($instance['googleplus_url']);
		$flickr_title = strip_tags($instance['flickr_title']);
		$flickr_url = strip_tags($instance['flickr_url']);
		$vimeo_title = strip_tags($instance['vimeo_title']);
		$vimeo_url = strip_tags($instance['vimeo_url']);
		$xing_title = strip_tags($instance['xing_title']);
		$xing_url = strip_tags($instance['xing_url']);
		$linkedin_title = strip_tags($instance['linkedin_title']);
		$linkedin_url = strip_tags($instance['linkedin_url']);
		$delicious_title = strip_tags($instance['delicious_title']);
		$delicious_url = strip_tags($instance['delicious_url']);
?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'ari' ); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('rss_title'); ?>"><?php _e( 'RSS Text:', 'ari' ); ?> <input class="widefat" id="<?php echo $this->get_field_id('rss_title'); ?>" name="<?php echo $this->get_field_name('rss_title'); ?>" type="text" value="<?php echo esc_attr($rss_title); ?>" /></label></p>	
			<p><label for="<?php echo $this->get_field_id('rss_url'); ?>"><?php _e( 'RSS  URL:', 'ari' ); ?> <input class="widefat" id="<?php echo $this->get_field_id('rss_url'); ?>" name="<?php echo $this->get_field_name('rss_url'); ?>" type="text" value="<?php echo esc_attr($rss_url); ?>" /></label></p>	
			<p><label for="<?php echo $this->get_field_id('twitter_title'); ?>"><?php _e( 'Twitter Text:', 'ari' ); ?> <input class="widefat" id="<?php echo $this->get_field_id('twitter_title'); ?>" name="<?php echo $this->get_field_name('twitter_title'); ?>" type="text" value="<?php echo esc_attr($twitter_title); ?>" /></label></p>	
			<p><label for="<?php echo $this->get_field_id('twitter_url'); ?>"><?php _e( 'Twitter  URL:', 'ari' ); ?> <input class="widefat" id="<?php echo $this->get_field_id('twitter_url'); ?>" name="<?php echo $this->get_field_name('twitter_url'); ?>" type="text" value="<?php echo esc_attr($twitter_url); ?>" /></label></p>	
			<p><label for="<?php echo $this->get_field_id('fb_title'); ?>"><?php _e( 'Facebook Text:', 'ari' ); ?> <input class="widefat" id="<?php echo $this->get_field_id('fb_title'); ?>" name="<?php echo $this->get_field_name('fb_title'); ?>" type="text" value="<?php echo esc_attr($fb_title); ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('fb_url'); ?>"><?php _e( 'Facebook URL:', 'ari' ); ?> <input class="widefat" id="<?php echo $this->get_field_id('fb_url'); ?>" name="<?php echo $this->get_field_name('fb_url'); ?>" type="text" value="<?php echo esc_attr($fb_url); ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('googleplus_title'); ?>"><?php _e( 'Google+ Text:', 'ari' ); ?> <input class="widefat" id="<?php echo $this->get_field_id('googleplus_title'); ?>" name="<?php echo $this->get_field_name('googleplus_title'); ?>" type="text" value="<?php echo esc_attr($googleplus_title); ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('googleplus_url'); ?>"><?php _e( 'Google+ URL:', 'ari' ); ?> <input class="widefat" id="<?php echo $this->get_field_id('googleplus_url'); ?>" name="<?php echo $this->get_field_name('googleplus_url'); ?>" type="text" value="<?php echo esc_attr($googleplus_url); ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('flickr_title'); ?>"><?php _e( 'Flickr Text:', 'ari' ); ?> <input class="widefat" id="<?php echo $this->get_field_id('flickr_title'); ?>" name="<?php echo $this->get_field_name('flickr_title'); ?>" type="text" value="<?php echo esc_attr($flickr_title); ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('flickr_url'); ?>"><?php _e( 'Flickr URL:', 'ari' ); ?> <input class="widefat" id="<?php echo $this->get_field_id('flickr_url'); ?>" name="<?php echo $this->get_field_name('flickr_url'); ?>" type="text" value="<?php echo esc_attr($flickr_url); ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('vimeo_title'); ?>"><?php _e( 'Vimeo Text:', 'ari' ); ?> <input class="widefat" id="<?php echo $this->get_field_id('vimeo_title'); ?>" name="<?php echo $this->get_field_name('vimeo_title'); ?>" type="text" value="<?php echo esc_attr($vimeo_title); ?>" /></label></p>	
			<p><label for="<?php echo $this->get_field_id('vimeo_url'); ?>"><?php _e( 'Vimeo URL:', 'ari' ); ?> <input class="widefat" id="<?php echo $this->get_field_id('vimeo_url'); ?>" name="<?php echo $this->get_field_name('vimeo_url'); ?>" type="text" value="<?php echo esc_attr($vimeo_url); ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('xing_title'); ?>"><?php _e( 'Xing Text:', 'ari' ); ?> <input class="widefat" id="<?php echo $this->get_field_id('xing_title'); ?>" name="<?php echo $this->get_field_name('xing_title'); ?>" type="text" value="<?php echo esc_attr($xing_title); ?>" /></label></p>	
			<p><label for="<?php echo $this->get_field_id('xing_url'); ?>"><?php _e( 'Xing URL:', 'ari' ); ?> <input class="widefat" id="<?php echo $this->get_field_id('xing_url'); ?>" name="<?php echo $this->get_field_name('xing_url'); ?>" type="text" value="<?php echo esc_attr($xing_url); ?>" /></label></p>		
			<p><label for="<?php echo $this->get_field_id('linkedin_title'); ?>"><?php _e( 'LinkedIn Text:', 'ari' ); ?> <input class="widefat" id="<?php echo $this->get_field_id('linkedin_title'); ?>" name="<?php echo $this->get_field_name('linkedin_title'); ?>" type="text" value="<?php echo esc_attr($linkedin_title); ?>" /></label></p>		
			<p><label for="<?php echo $this->get_field_id('linkedin_url'); ?>"><?php _e( 'LinkedIn URL:', 'ari' ); ?> <input class="widefat" id="<?php echo $this->get_field_id('linkedin_url'); ?>" name="<?php echo $this->get_field_name('linkedin_url'); ?>" type="text" value="<?php echo esc_attr($linkedin_url); ?>" /></label></p>	
			<p><label for="<?php echo $this->get_field_id('delicious_title'); ?>"><?php _e( 'Delicious Text:', 'ari' ); ?> <input class="widefat" id="<?php echo $this->get_field_id('delicious_title'); ?>" name="<?php echo $this->get_field_name('delicious_title'); ?>" type="text" value="<?php echo esc_attr($delicious_title); ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('delicious_url'); ?>"><?php _e( 'Delicious URL:', 'ari' ); ?> <input class="widefat" id="<?php echo $this->get_field_id('delicious_url'); ?>" name="<?php echo $this->get_field_name('delicious_url'); ?>" type="text" value="<?php echo esc_attr($delicious_url); ?>" /></label></p>

<?php
	}
}

// register Ari SocialLinks Widget
add_action('widgets_init', create_function('', 'return register_widget("Ari_SocialLinks_Widget");'));

/* Ari Theme-Options Page */
function themeoptions_admin_menu()
{
	// here's where we add the theme options page link to the dashboard sidebar
	add_theme_page("Theme Options", __('Theme Options', 'ari'), 'edit_themes', basename(__FILE__), 'themeoptions_page');
}

function themeoptions_page() {
	if ( isset( $_POST['update_themeoptions'] ) ) { themeoptions_update(); }  //check options update
	?>
	<div class="wrap">
		<div id="icon-themes" class="icon32"><br /></div>
		<h2><?php _e('Theme Options', 'ari'); ?></h2>

		<form method="POST" action="">
			<input type="hidden" name="update_themeoptions" value="true" />
			
			<table class="form-table" style="margin-bottom: 50px;">
			<h3><?php _e('Switch to the dark theme version', 'ari'); ?></h3>
			<tr valign="top">
				<th scope="row"><label for="dark-style"><?php _e('Ari dark theme version', 'ari'); ?></label></th>
				<td><input type="checkbox" name="dark-style" id="dark-style" <?php echo get_option('ari_dark-style'); ?> /><?php _e(' check the box, if you want to use the dark theme version', 'ari'); ?></h4></td>
			</tr>
 			</table>
			
			<table class="form-table" style="margin-bottom: 50px;">
			<h3><?php _e('Change the Theme Colors', 'ari'); ?></h3>
			<p class="description"><?php _e('(You can find out the HEX value of any color with the <a href="http://chir.ag/projects/name-that-color/" target="_blank">Name that Color</a> online-tool)', 'ari'); ?></p>
			<tr valign="top">
				<th scope="row"><label for="background-color"><?php _e('Background Color', 'ari'); ?></label></th>
				<td><input type="text" name="background-color" id="background-color" size="32" value="<?php echo get_option('ari_background-color'); ?>"/> <span class="description"><?php _e(' e.g. #FFFFFF or white (default color: white)', 'ari'); ?></span></td>
			</tr>
  			<tr valign="top">
				<th scope="row"><label for="linkcolor-1"><?php _e('First Link Color', 'ari'); ?></label></th>
				<td><input type="text" name="linkcolor-1" id="linkcolor-1" size="32" value="<?php echo get_option('ari_linkcolor-1'); ?>"/> <span class="description"><?php _e(' e.g. #0000FF or blue (default green link color: #88C34B)', 'ari'); ?></span></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="linkcolor-2"><?php _e('Second Link Color', 'ari'); ?></label></th>
				<td><input type="text" name="linkcolor-2" id="linkcolor-2" size="32" value="<?php echo get_option('ari_linkcolor-2'); ?>"/> <span class="description"><?php _e(' e.g. #FF0000 or red (default grey link color: #999999)', 'ari'); ?></span></td>
			</tr>			
			<tr valign="top">
				<th scope="row"><label for="text-color"><?php _e('Text Color', 'ari'); ?></label></th>
				<td><input type="text" name="text-color" id="text-color" size="32" value="<?php echo get_option('ari_text-color'); ?>"/> <span class="description"><?php _e(' e.g. #4C4C4C (default text color: #4C4C4C)', 'ari'); ?></span></td>
			</tr>
 			</table>
			
			<table class="form-table" style="margin-bottom: 10px;">
			<h3><?php _e('Use an image as your logo', 'ari'); ?></h3>
			<tr valign="top">
				<th scope="row"><label for="logo-image"><?php _e('Logo Image URL', 'ari'); ?></label></th>
				<td><input type="text" name="logo-image" id="logo-image" size="70" value="<?php echo get_option('ari_logo-image'); ?>"/><br/><span
                            class="description"> <a href="<?php echo home_url(); ?>/wp-admin/media-new.php" target="_blank"><?php _e('Upload your logo image', 'ari'); ?></a> <?php _e(' using the WordPress Media Library and insert the URL here<br/>(the maximum logo image size is: 240 x 75 Pixel)', 'ari'); ?> </span><br/><br/><img src="<?php echo (get_option('logo-image')) ? get_option('logo-image') : get_template_directory_uri() . '/images/logo.png' ?>"
                     alt=""/></td>
			</tr>
			</table>
			
			<p><input type="submit" name="search" value="<?php _e('Update Options', 'ari'); ?>" class="button button-primary" /></p>
		</form>

	</div>
	<?php
}

add_action('admin_menu', 'themeoptions_admin_menu');

// Update options
function themeoptions_update(){

	if (isset($_POST['dark-style'])=='on') { $display = 'checked'; } else { $display = ''; }
	update_option('ari_dark-style', $display);
	update_option('ari_background-color', 	$_POST['background-color']);
	update_option('ari_linkcolor-1', 	$_POST['linkcolor-1']);
	update_option('ari_linkcolor-2', 	$_POST['linkcolor-2']);
	update_option('ari_text-color', 	$_POST['text-color']);
	update_option('ari_logo-image', 	$_POST['logo-image']);
	
}


// Custom CSS-Styles for Background, Text-Color and Link Colors
function insert_custom_css(){
?>
<style type="text/css">
<?php if (get_option('ari_background-color') ) { ?>body { background-color: <?php echo get_option('ari_background-color'); ?>; } <?php } ?>
<?php if (get_option('ari_text-color') ) { ?>body { color: <?php echo get_option('ari_text-color'); ?>; }
#content h2 a { color: <?php echo get_option('ari_text-color'); ?>; }
<?php } ?>
<?php if (get_option('ari_linkcolor-1') ) { ?>a, ul.sidebar li.widget_text a { color:<?php echo get_option('ari_linkcolor-1'); ?>; }
#content h2 a:hover, ul.sidebar a:hover, .comment-meta a:hover, p.logged-in-as a:hover, p.meta a:hover, a.post-edit-link:hover, #footer a:hover { color:<?php echo get_option('ari_linkcolor-1'); ?>; }
#searchsubmit:hover, form#commentform p.form-submit input#submit:hover, input.wpcf7-submit:hover  {
	background:<?php echo get_option('ari_linkcolor-1'); ?>;
}
<?php } ?>
<?php if (get_option('ari_linkcolor-2') ) { ?>ul.sidebar a, p.meta a, .comment-meta a, p.logged-in-as a, a.post-edit-link, #footer a { color:<?php echo get_option('ari_linkcolor-2'); ?>; }
<?php } ?>
</style>
<?php
}

add_action('wp_head', 'insert_custom_css');

/* Remove the default CSS style from the WP image gallery */
add_filter('gallery_style', create_function('$a', 'return "
<div class=\'gallery\'>";'));