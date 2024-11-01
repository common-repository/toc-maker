<?php
defined( 'ABSPATH' ) || exit;
/**
 * Table of contents
 *
 * @package Table of contents Maker
 */


function toc_maker_enqueue_scripts() {

  $load_setting = get_option('toc_maker_settings');

  wp_enqueue_style('toc_maker_front',TOC_MAKER_URI . 'assets/css/front/toc.min.css',array() , TOC_MAKER_VERSION );
  wp_enqueue_style('toc_maker_skin',TOC_MAKER_URI . 'assets/css/skin/'.$load_setting['skin_type'].'.min.css',array() , TOC_MAKER_VERSION);

}

add_action('wp_enqueue_scripts','toc_maker_enqueue_scripts');