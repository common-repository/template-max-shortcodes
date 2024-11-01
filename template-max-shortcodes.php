<?php
/**
 * Plugin Name: Template Max Shortcodes
 * Description: TemplateMax's Template shortcodes for Elementor.
 * Plugin URI: https://template-max.com/blogs/docs/how-to-use-template-max-shortcodes-plugin
 * Version:     0.2.0
 * Author:      Template Max
 * Author URI:  https://template-max.com/
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: template-max
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'TMAX_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once TMAX_PLUGIN_DIR . '/shortcodes/posts.php';

add_filter( 'widget_text', 'do_shortcode' );

/**
 * display posts
 * [tmax_posts]
 * 
 * @return void
 */
function tmax_posts( $atts ) {
  extract(shortcode_atts(array(
    'category_id' => '',
    'tag_id' => '',
    'post_id' => '',
    'page_id' => '',
    'post_type' => '',
    'taxonomy' => 'category',
    'terms' => '',
    'orderby' => 'date',
    'order' => 'DESC',
    'meta_key' => '',
    'meta_value' => '',
    'max' => 12,
    'block_type' => 'news',
    'thumbnail_size' => 'post-thumbnail'
  ), $atts));

  $return_string = '';

  // taxonomy
  $tax_query = '';
  if ($taxonomy != '' && $terms != '') {
    $tax_query = array(
      array(
          'taxonomy' => $taxonomy,
          'field'    => 'slug',
          'terms'    => $terms,
      ),
    );
  }

  $args = array(
    'cat' => $category_id,
    'tag_id' => $tag_id,
    'post_id' => $post_id,
    'page_id' => $page_id,
    'post_type' => $post_type,
    'tax_query' => $tax_query,
    'orderby' => $orderby,
    'order' => $order,
    'meta_key' => $meta_key,
    'meta_value' => $meta_value,
    'showposts' => $max
  );

  if ($block_type == 'news') {
    $return_string .= '<div class="tmax-news"><ul class="tmax-news__list">';
  } else {
    $return_string .= '<div class="tmax-blog__container"><div class="tmax-blog__row">';
  }

  $the_query = new WP_Query( $args );
  if ($the_query->have_posts()) : while ($the_query->have_posts()) : $the_query->the_post();

    $post_id = get_the_ID();
    
    $thumbnail_url = '';
    // サムネイルがなく、no_imageがあればno_imageをセット
    if ( !has_post_thumbnail( $post_id ) ) {
      $thumbnail_url = plugin_dir_url( __FILE__ ) . "assets/no_image.png";
    } else {
      $thumbnail_url = get_the_post_thumbnail_url( $post_id, $thumbnail_size);
    }

    $data = [
      'id' => $post_id,
      'title' => get_the_title(),
      'link' => get_permalink(),
      'excerpt' => get_the_excerpt(),
      'date' => get_the_date('Y-m-d'),
      'author_name' => get_the_author(),
      'thumbnail_url' => $thumbnail_url,
      'terms' => get_the_terms( $post_id, $taxonomy ),
    ];
    $return_string .= postCode($block_type, '', $data);

    /*
    $return_string .= '<li class="tmax-news__item">';
      $return_string .= '<span class="tmax-news__item-date">' . $post_date . '</span>';

			if ( $terms && ! is_wp_error( $terms ) ) {
				$return_string .= '<a class="tmax-news__item-category" href="' . get_term_link( $terms[0] ) . '">' . $terms[0]->name . '</a>';
			}

      $return_string .= '<a class="tmax-news__item-link" href="' . $post_link . '">' . $post_title . '</a>';
		$return_string .= '</li>';
    */

  endwhile; endif;

  if ($block_type == 'news') {
    $return_string .= '</ul></div>';
  } else {
    $return_string .= '</div></div>';
  }

  wp_reset_postdata();
  return $return_string;
}
add_shortcode( 'tmax_posts', 'tmax_posts' );


/**
 * display menu
 * [tmax_menu menu_name="header-1"]
 */
function tmax_menu($atts, $content = null) {
  extract(shortcode_atts(array(
    'menu_name' => '',
  ), $atts));

  $return_string = '';

	$return_string .=	'<a class="tmax-btn-svg tmax-drawer__bar" href="#" data-toggle="drawer" data-target="tmax-drawer-header">';
    $return_string .= '<span></span><span></span><span></span>';
  $return_string .= '</a>';
	$return_string .= '<div id="tmax-drawer-header">';
    $return_string .= '<a class="tmax-btn-svg tmax-drawer__close" href="#" data-toggle="drawer" data-target="tmax-drawer-header">';
      $return_string .= '<span></span><span></span>';
    $return_string .= '</a>';
		$return_string .= '<nav class="tmax-nav tmax-drawer-mobile tmax-drawer-right">';
      $return_string .= '<ul class="tmax-menu">';

  $menu = wp_get_nav_menu_object( $menu_name );
  $menu_items = wp_get_nav_menu_items($menu->term_id);

  foreach ( (array) $menu_items as $key => $menu_item ) {
    $menu_title = $menu_item->title;
    $menu_link = $menu_item->url;

		$return_string .= '<li class="tmax-menu__item"><a class="tmax-menu__item-link" href="' . $menu_link .'">' . $menu_title . '</a></li>';
  }

	$return_string .= $content;
	$return_string .= '</ul></nav></div>';

  return $return_string;
}
add_shortcode( 'tmax_menu', 'tmax_menu' );
