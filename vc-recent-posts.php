<?php
/*
  Plugin Name: VC Recent Posts Shortcode
  Plugin URI: http://www.visceralconcepts.com
  Description: A shortcode to add a stylized list of recent posts to any place on your site.
  Version: 1.0
  Author: Visceral Concepts
  Author URI: http://www.visceralconcepts.com
  License: GPLv3 or Later
 */
 
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*
  Before the plugin does anything, we check for updates.
*/

require_once( 'inc/vc-plugin-updater.php' );
if ( is_admin() ) {
    new VC_Plugin_Updater( __FILE__, 'mmcnew', 'vc-recent-posts-plugin' );
}


//Load CSS & Scripts

add_action( 'wp_enqueue_scripts', 'gmaps_scripts', 16 );
function gmaps_scripts() {

	wp_register_style( 'rp-css', plugin_dir_url(__FILE__) . 'css/recent-posts-styles.css' );
	wp_enqueue_style( 'rp-css' );

}

// Shortcode Construct

function vc_recent_posts_shortcode($atts){
 $q = new WP_Query(
   array( 'orderby' => 'date', 'posts_per_page' => '5')
 );

$list = '<div class="recent_posts">
			<ul class="recent-posts">';

while($q->have_posts()) : $q->the_post();

 $list .= '<li>' . get_the_date() . '<a href="' . get_permalink() . '">' . get_the_title() . '</a>' . '<br />' . get_the_excerpt() . '</li>';

endwhile;

wp_reset_query();

return $list . '</ul>
			</div>';

}

add_shortcode('recent-posts', 'vc_recent_posts_shortcode');


?>