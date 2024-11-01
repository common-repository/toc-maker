<?php
defined( 'ABSPATH' ) || exit;
/**
 * Table of contents
 *
 * @package Table of contents Maker
 */

function toc_maker_admin_wrap_page() {
	?>


	<div id="zipang_admin_settings_wrap" class="zipang_admin_settings_wrap">
		<div id="zipang_loading_bg"><div id="zipang_loading"></div></div>
		<div id="zipang_pop_up_message"></div>

		<input id="tocm_settings" class="tabs" type="radio" name="tab_item" checked="">
		<input id="tocm_environment" class="tabs" type="radio" name="tab_item">

		<div id="toc_maker_header" class="toc_maker_header">
			<div class="zipang_logo_wrap tocm_flex tocm_ai_c tocm_jc_c">
				<img width="256" height="21" src="<?php echo esc_url(TOC_MAKER_URI); ?>assets/images/toc-maker.svg" alt="Table of contents Maker" style="margin: 20px auto 4px;">
			</div>
			<div class="tocm_admin_edit_version">
				<?php echo esc_html(TOC_MAKER_VERSION); ?>
			</div>

			<div class="tocm_flex tocm_ai_c tocm_o_s_t">
				<label id="tocm_settings_label" class="tab_item" for="tocm_settings"><?php esc_html_e('Settings','toc-maker'); ?></label>
				<label id="tocm_environment_label" class="tab_item" for="tocm_environment"><?php esc_html_e('Usage environment','toc-maker'); ?></label>
			</div>
		</div>

		<?php
		
		if (isset($_POST['posted']) && $_POST['posted'] === 'toc_maker_update' && check_admin_referer( 'toc_maker_nonce_field_action','toc_maker_nonce_name' )) {

			$update_settings = array(
				'toc_title' => sanitize_text_field( $_POST['toc_title'] ),
				'min_head' => sanitize_text_field( $_POST['min_head'] ),
				'toc_position' => sanitize_text_field( $_POST['toc_position'] ),
				'hierarchy' => sanitize_text_field( $_POST['hierarchy'] ),
				'numerical' => sanitize_text_field( $_POST['numerical'] ),
				'hide_at_first' => sanitize_text_field( $_POST['hide_at_first'] ),
				'skin_type' => sanitize_text_field( $_POST['skin_type'] ),
				'toc_maker_version' => TOC_MAKER_VERSION
			);

			
			$update_settings['auto_insert_type'] = array();
			if(isset($_POST['auto_insert_type']) && is_array($_POST['auto_insert_type'])){
				foreach ($_POST['auto_insert_type'] as $tmp_key => $tmp_val) {
					$update_settings['auto_insert_type'][] = sanitize_text_field($tmp_val);
				}
			}


			update_option('toc_maker_settings', $update_settings );

			echo '<div id="message" class="updated notice notice-success is-dismissible notice-alt updated-message"><p>'.esc_html__('Settings updated successfully.','toc-maker').'</p></div>'; 

		}


		?>

		<div id="toc_maker" class="toc_maker_wrap">

			<div id="tocm_settings_content" class="tab_content zipang_box_design">

				<?php
				require_once TOC_MAKER_DIR . 'inc/settings/content/settings-main.php';
				toc_maker_admin_settings_page();
				?>

			</div>
			<div id="tocm_environment_content" class="tab_content zipang_box_design">

				<?php
				require_once TOC_MAKER_DIR . 'inc/settings/content/settings-environment.php';
				toc_maker_environment_page();
				?>

			</div>


			<div class="tocm_sub_menu zipang_box_design">
				<a href="https://wordpress.org/support/plugin/toc-maker/" class="" target="_blank">
					<?php esc_html_e('Support Forum','toc-maker'); ?>
					<p style="margin: 4px 0 0;">
						<svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 128 128" width="16px" height="16px"><path d="M 84 11 C 82.3 11 81 12.3 81 14 C 81 15.7 82.3 17 84 17 L 106.80078 17 L 60.400391 63.400391 C 59.200391 64.600391 59.200391 66.499609 60.400391 67.599609 C 61.000391 68.199609 61.8 68.5 62.5 68.5 C 63.2 68.5 63.999609 68.199609 64.599609 67.599609 L 111 21.199219 L 111 44 C 111 45.7 112.3 47 114 47 C 115.7 47 117 45.7 117 44 L 117 14 C 117 12.3 115.7 11 114 11 L 84 11 z M 24 31 C 16.8 31 11 36.8 11 44 L 11 104 C 11 111.2 16.8 117 24 117 L 84 117 C 91.2 117 97 111.2 97 104 L 97 59 C 97 57.3 95.7 56 94 56 C 92.3 56 91 57.3 91 59 L 91 104 C 91 107.9 87.9 111 84 111 L 24 111 C 20.1 111 17 107.9 17 104 L 17 44 C 17 40.1 20.1 37 24 37 L 69 37 C 70.7 37 72 35.7 72 34 C 72 32.3 70.7 31 69 31 L 24 31 z"/></svg> WordPress.org
					</p>
				</a>
			</div>

		</div>
	</div>
	<?php

}
