<?php
defined( 'ABSPATH' ) || exit;
/**
 * Table of contents
 *
 * @package Table of contents Maker
 */


function toc_maker_textdomain_load() {
	load_plugin_textdomain( 'toc-maker', false, dirname( plugin_basename( TOC_MAKER_PLUGIN_FILE ) ) .'/languages/' );
}
add_action( 'plugins_loaded', 'toc_maker_textdomain_load');


function toc_maker_add_menu() {

	$tocm_submenu_page = add_options_page(__('Setting Up Table of contents Maker','toc-maker'),'Table of contents Maker', 'administrator' , 'toc-maker','toc_maker_admin_page');
	add_action( "admin_print_scripts-$tocm_submenu_page", 'toc_maker_only_edit_page_scripts' );

}
add_action( 'admin_menu', 'toc_maker_add_menu' );


function toc_maker_admin_page() {
	require_once TOC_MAKER_DIR . 'inc/settings/content/settings-wrap.php';
	toc_maker_admin_wrap_page();
}





function toc_maker_only_edit_page_scripts() {

	
	wp_enqueue_media();
	wp_enqueue_style('toc_maker_admin',TOC_MAKER_URI . 'assets/css/admin/admin.min.css',array() , TOC_MAKER_VERSION);
	wp_enqueue_script('toc_maker_admin',TOC_MAKER_URI . 'assets/js/admin/admin.min.js',array(), TOC_MAKER_VERSION , true);

	wp_localize_script( 'toc_maker_admin', 'admin_zipang_translations', array(
		'select_image' => __('Select image','toc-maker'),
		'copy' => __('Copied','toc-maker'),
	) );
}






function toc_maker_plugin_action_links($links, $file) {
	if ('toc-maker/toc-maker.php' == $file  && current_user_can( 'manage_options' )) {
		$settings_link = '<a href="' . admin_url( 'options-general.php?page=toc-maker' ) . '">'.__( 'Settings', 'toc-maker' ).'</a>';
		array_unshift($links, $settings_link);
	}
	return $links;
}
add_filter('plugin_action_links', 'toc_maker_plugin_action_links', 10, 2);




function toc_maker_version_check() {

	$load_setting = get_option('toc_maker_settings');

	$check = false;

	if( isset($load_setting['toc_maker_version']) && TOC_MAKER_VERSION === $load_setting['toc_maker_version'] ){
		$check = true;
	}

	if(!$check){
		toc_maker_update_option();
	}

}
add_action( 'admin_init', 'toc_maker_version_check' );


function toc_maker_update_option() {


	$load_setting = get_option('toc_maker_settings' , array());

	if ( empty($load_setting) ) {
		
		$load_setting['toc_title'] = esc_html__('Contents','toc-maker');
		$load_setting['min_head'] = 3;
		$load_setting['toc_position'] = 1;
		$load_setting['hierarchy'] = false;
		$load_setting['numerical'] = false;
		$load_setting['hide_at_first'] = false;
		$load_setting['auto_insert_type'] = array('post','page');
		$load_setting['nextpage'] = false;
		$load_setting['widget'] = false;
		$load_setting['toc_position'] = 'before_1st_heading';
		$load_setting['skin_type'] = 'default';
	}

	

	$load_setting['toc_maker_version'] = TOC_MAKER_VERSION;

	update_option('toc_maker_settings', $load_setting );

}