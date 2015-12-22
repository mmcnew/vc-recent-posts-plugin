<?php
/*
  Plugin Name: VC Recent Posts Shortcode
  Plugin URI: http://www.visceralconcepts.com
  Description: A shortcode to add a stylized list of recent posts to any place on your site.
  Version: 1.01
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
    new VC_RP_Plugin_Updater( __FILE__, 'mmcnew', 'vc-recent-posts-plugin' );
}


//Load CSS & Scripts

add_action( 'wp_enqueue_scripts', 'rp_scripts', 17 );
function rp_scripts() {

	wp_register_style( 'rp-css', plugin_dir_url(__FILE__) . 'css/recent-post-styles.css' );
	wp_enqueue_style( 'rp-css' );

}

// Shortcode Construct

function vc_recent_posts_shortcode($atts){
 $q = new WP_Query(
   array( 'orderby' => 'date', 'posts_per_page' => '6')
 );

$list = '<div class="recent-posts">';

while($q->have_posts()) : $q->the_post();
	$count++;
	$feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
	$list .= '<div><a href="' . get_permalink() . '"><img src="' . $feat_image . '" alt="' . get_the_title() . '"/><p class="date">' . get_the_date() . '</p></a></div>';
	if ( 0 == $count%3 ) {
        $list .= '</div><div class="recent-posts">';
    }
endwhile;

wp_reset_query();

return $list . '</div> <a href="' . get_permalink( get_option( 'page_for_posts' ) ) . '" class="button more">View More</a>';

}

add_shortcode('recent-posts', 'vc_recent_posts_shortcode');


?>