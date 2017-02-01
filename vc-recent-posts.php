<?php
/*
  Plugin Name: VC Recent Posts Shortcode
  Plugin URI: http://www.visceralconcepts.com
  Description: A shortcode to add a stylized list of recent posts to any place on your site.
  Version: 1.05
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

add_action( 'wp_enqueue_scripts', 'rp_scripts' );
function rp_scripts() {

	wp_register_style( 'rp-css', plugin_dir_url(__FILE__) . 'css/recent-post-styles.css' );
	wp_enqueue_style( 'rp-css' );

}

// Shortcode Construct

function vc_recent_posts_shortcode($atts){
	
	$a = shortcode_atts ( array (
		'type' =>  'posts',
		'style' => 'grid',
		'columns' => '3',
		'image' => 'true',
		'title' => 'true',
		'excerpt' => 'false',
		'date' => 'false',
		'time' => 'true',
		'link' => 'true',
		'feat-bg' => 'true',
		'posts-per-page' => '6',
		'classes' => ''
		), $atts );
		
	$args = array(
		'orderby' => 'date',
		'posts_per_page' => $a['posts-per-page']
	);
	
	$q = new WP_Query( $args);
	
	$list = '<div id="recent-posts"><div class="recent-posts ' . $a['style'] . '">';
	while($q->have_posts()) : $q->the_post();
		if ( $a['style'] != 'list' ) {
			$count++;
		}
		$feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
		$excerpt =  get_the_excerpt();
		$link = get_permalink();
		$title = get_the_title();
		$date = get_the_date();
		$post = get_post();
		$words = str_word_count( strip_tags( $post->post_content ) );
		$minutes = floor( $words / 200 );
		$seconds = floor( $words % 200 / ( 200 / 60 ) );
		if ( 1 <= $minutes ) {
			$estimated_time = $minutes . ' minute read';
		} else {
			$estimated_time = 'Super quick read';
		}
		
		$list .= '<div class="post';
		if ( $a['image'] == 'false' && $a['feat-bg'] == 'false' ) {
			$list .= ' bborder';
		}
		$list .= '"';
		if ( $a['feat-bg'] != 'false' ) {
			$list .= ' style="background-image: url(' . $feat_image . ')"';
		}
		$list .= '>';
		if ( $a['link'] != 'false' ) {
			$list .= '<a href="' . $link . '">';
		}
		if ( $a['title'] != 'false' ) {
			$list .= '<h3>' . $title . '</h3>';
		}
		if ( $a['link'] != 'false' ) {
			$list .= '</a>';
		}
		if ( $a['link'] != 'false' && $a['image'] != 'false' ) {
			$list .= '<a href="' . $link . '">';
		}
		if ( $a['image'] != 'false' && $a['feat-bg'] != 'true' ) {
			$list .= '<img src="' . $feat_image . '" alt="' . $title . '"/>';
		}
		if ( $a['link'] != 'false' ) {
			$list .= '</a>';
		}
		if ( $a['link'] != 'false' ) {
			$list .= '<a href="' . $link . '">';
		}
		if ( $a['date'] != 'false' ) {
			$list .= '<p class="date">' . $date . '</p>';
		}
		if ( $a['date'] == 'false' && $a['time'] != 'false' ) {
			$list .= '<p class="date">' . $estimated_time . '</p>';
		}
		if ( $a['link'] != 'false' ) {
			$list .= '</a>';
		}
		if ( $a['excerpt'] != 'false' ) {
			$list .= '<div class="excerpt"><p>' . $excerpt . '</p></div>';
		}
		$list .= '</div>';
		if ( $a['style'] != 'list' ) {
			if ( $a['columns'] == 3 && 0 == $count%3 ) {
				$list .= '</div><div class="recent-posts ' . $a['style'] . '">';
			} elseif ( $a['columns'] == 4 && 0 == $count%4 ) {
				$list .= '</div><div class="recent-posts ' . $a['style'] . '">';
			}
		}
	endwhile;
	
	wp_reset_query();
	
	return $list . '</div> ' . /* Add this once we come up with an effective way to display the blog archive. ** <a href="' . get_permalink( get_option( 'page_for_posts' ) ) . '" class="button more">View More</a> . */ ' </div>';

}

add_shortcode('recent-posts', 'vc_recent_posts_shortcode');


?>