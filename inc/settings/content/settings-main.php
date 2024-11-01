<?php
defined( 'ABSPATH' ) || exit;
/**
 * Table of contents
 *
 * @package Table of contents Maker
 */

function toc_maker_admin_settings_page() {

	$load_setting = get_option('toc_maker_settings');

	if ( !$load_setting ) {
		toc_maker_update_option();
		$load_setting = get_option('toc_maker_settings');
	}

	?>

	<form name="toc_maker_edit_form" method="post" action="<?php echo esc_url( wp_nonce_url( admin_url('options-general.php?page=toc-maker') , 'toc_maker_nonce_field_action', 'toc_maker_nonce_name') );?>" onsubmit="return false;" style="max-width:600px;">
		<?php wp_nonce_field( 'toc_maker_nonce_field_action','toc_maker_nonce_name' ); ?>
		<input type="hidden" name="posted" value="toc_maker_update">

		<h2><?php esc_html_e('Settings','toc-maker'); ?></h2>
		<hr>
		<div class="zipang_setting_wrap">
			<label class="zipang_setting_head" for="hierarchy">
				<?php esc_html_e('Hierarchical display','toc-maker'); ?>
			</label>
			<div class="zipang_switch_wrap">
				<label class="zipang_switch_label">
					<input type="hidden" name="hierarchy" value="0">
					<input type="checkbox" value="hierarchy" id="hierarchy" name="hierarchy"<?php checked( $load_setting['hierarchy'], "hierarchy" ); ?>>
					<span class="zipang_switch_style"></span>
				</label>
			</div>
		</div>
		<div class="zipang_setting_wrap">
			<label class="zipang_setting_head" for="numerical">
				<?php esc_html_e('Number display','toc-maker'); ?>
			</label>
			<div class="zipang_switch_wrap">
				<label class="zipang_switch_label">
					<input type="hidden" name="numerical" value="0">
					<input type="checkbox" value="numerical" id="numerical" name="numerical"<?php checked( $load_setting['numerical'], "numerical" ); ?>>
					<span class="zipang_switch_style"></span>
				</label>
			</div>
		</div>
		<div class="zipang_setting_wrap">
			<label class="zipang_setting_head" for="hide_at_first">
				<?php esc_html_e('Hide at first','toc-maker'); ?>
			</label>
			<div class="zipang_switch_wrap">
				<label class="zipang_switch_label">
					<input type="hidden" name="hide_at_first" value="0">
					<input type="checkbox" value="hide_at_first" id="hide_at_first" name="hide_at_first"<?php checked( $load_setting['hide_at_first'], "hide_at_first" ); ?>>
					<span class="zipang_switch_style"></span>
				</label>
			</div>
		</div>
		<div class="zipang_setting_wrap">
			<label class="zipang_setting_head" for="toc_position">
				<?php esc_html_e('Position','toc-maker'); ?>
			</label>
			<div>
				<select id="toc_position" name="toc_position">
					<option value="before_1st_heading"<?php selected( $load_setting['toc_position'], 'before_1st_heading' ); ?>><?php esc_html_e('before the first heading','toc-maker'); ?></option>
					<option value="after_1st_heading"<?php selected( $load_setting['toc_position'], 'after_1st_heading' ); ?>><?php esc_html_e('after the first heading','toc-maker'); ?></option>
					<option value="before_content"<?php selected( $load_setting['toc_position'], 'before_content' ); ?>><?php esc_html_e('before content','toc-maker'); ?></option>
				</select>
			</div>
		</div>
		<div class="zipang_setting_wrap">
			<label class="zipang_setting_head" for="min_head">
				<?php esc_html_e('Minimum number of headings','toc-maker'); ?>
			</label>
			<div>
				<select id="min_head" name="min_head">
					<?php
					$i = 1;
					while ($i < 6) {
						echo '<option value="'.esc_attr($i).'"'.selected( $load_setting['min_head'], esc_attr($i) , false ).'>'.esc_attr($i).'</option>';
						++$i;
					}
					?>
				</select>
			</div>
		</div>
		<div class="zipang_setting_wrap">
			<label class="zipang_setting_head" for="toc_title">
				<?php esc_html_e('Title','toc-maker'); ?>
			</label>
			<div>
				<input id="toc_title" name="toc_title" class="" type="text" placeholder="<?php esc_html_e('Eg: Contents','toc-maker'); ?>" maxlength="300" value="<?php echo esc_attr($load_setting['toc_title']); ?>" />
			</div>
		</div>
		<div class="zipang_setting_wrap">
			<div class="zipang_setting_head" for="">
				<?php esc_html_e('Post types to auto insert','toc-maker'); ?>
			</div>
			<div>
				<div class="zipang_checkbox_wrap">
					<input type="checkbox" name="auto_insert_type[]" id="auto_insert_type_post" value="post"<?php checked( in_array( 'post', $load_setting['auto_insert_type'] ), true ); ?>>
					<label class="zipang_checkbox_style" for="auto_insert_type_post"></label>
					<label class="margin_L" for="auto_insert_type_post">post</label>
				</div>
				<div class="zipang_checkbox_wrap">
					<input type="checkbox" name="auto_insert_type[]" id="auto_insert_type_page" value="page"<?php checked( in_array( 'page', $load_setting['auto_insert_type'] ), true ); ?>>
					<label class="zipang_checkbox_style" for="auto_insert_type_page"></label>
					<label class="margin_L" for="auto_insert_type_page">page</label>
				</div>
			</div>
		</div>
		<div class="zipang_setting_wrap">
			<div class="zipang_setting_head">
				<?php esc_html_e('Appearance','toc-maker'); ?>
			</div>
			<div>
				<div class="zipang_radiobox_wrap">
					<input type="radio" name="skin_type" id="skin_type_default" value="default"<?php checked( $load_setting['skin_type'], "default" ); ?>>
					<label class="zipang_radio_style" for="skin_type_default"></label>
					<label class="margin_L" for="skin_type_default">default</label>
				</div>
				<div class="zipang_radiobox_wrap">
					<input type="radio" name="skin_type" id="skin_type_black" value="black"<?php checked( $load_setting['skin_type'], "black" ); ?>>
					<label class="zipang_radio_style" for="skin_type_black"></label>
					<label class="margin_L" for="skin_type_black">black</label>
				</div>
			</div>
		</div>
		<hr>
		<input type="button" id="zipang_settings_submit" class="button button-primary" value="<?php esc_html_e('Update','toc-maker'); ?>" onclick="submit();" />
	</form>

	<?php
}
