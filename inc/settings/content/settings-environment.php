<?php
defined( 'ABSPATH' ) || exit;
/**
 * Table of contents
 *
 * @package Table of contents Maker
 */

function toc_maker_environment_page() {
  $ua = sanitize_text_field( $_SERVER[ 'HTTP_USER_AGENT' ] );
  $os = 'unknown';
  $browser = 'unknown';
  $version = '';
  $device = 'unknown';

  
  if ( strpos( $ua ,'Windows NT 10.0' ) !== false ) {

    $os = 'Windows 10 or 11';
    $device = 'PC';

  } elseif ( strpos( $ua , 'Windows NT 6.3' ) !== false ) {

    $os = 'Windows 8.1';
    $device = 'PC';

  } elseif ( strpos( $ua , 'Windows NT 6.2' ) !== false ) {

    $os = 'Windows 8';
    $device = 'PC';

  } elseif ( strpos( $ua , 'Windows NT 6.1' ) !== false ) {

    $os = 'Windows 7';
    $device = 'PC';

  } elseif ( preg_match( '/OS ([a-z0-9_]+)/', $ua, $matches ) ) {

    $os = 'iOS ' . str_replace( '_', '.', $matches[ 1 ] );

    if ( strpos( $ua , '/iPhone;/' ) !== false ) {
      $device = 'iPhone';
    } elseif ( strpos( $ua , '/iPod/' ) !== false ) {
      $device = 'iPod';
    } elseif ( strpos( $ua , '/iPad/' ) !== false ) {
      $device = 'iPad';
    }

  } elseif ( strpos( $ua , 'Mac OS X' ) !== false ) {

    $os = 'Mac OS X';
    $device = 'PC';

  } elseif ( preg_match( '/Android ([a-z0-9\.]+)/', $ua, $matches ) ) {

    $os = 'Android ' . $matches[ 1 ];
    $device = 'Android';

  } elseif ( preg_match( '/Linux ([a-z0-9_]+)/', $ua, $matches ) ) {

    $os = 'Linux ' . $matches[ 1 ];
    $device = 'PC';

  }

  

  if ( preg_match( '/Edge\/([0-9\.]+)/', $ua, $matches ) ) {
    $browser = 'Edge';
    $version = $matches[ 2 ];
  } elseif ( preg_match( '/(MSIE\s|Trident.*rv:)([0-9\.]+)/', $ua, $matches ) ) {
    $browser = 'Internet Explorer';
    $version = $matches[ 2 ];
  } elseif ( preg_match( '/Chrome\/([0-9\.]+)/', $ua, $matches ) ) {
    $browser = 'Chrome';
    $version = $matches[ 1 ];
  } elseif ( preg_match( '/Firefox\/([0-9\.]+)/', $ua, $matches ) ) {
    $browser = 'Firefox';
    $version = $matches[ 1 ];
  } elseif ( preg_match( '/\/([0-9\.]+)(\sMobile\/[A-Z0-9]{6})?\sSafari/', $ua, $matches ) ) {
    $browser = 'Safari';
    $version = $matches[ 1 ];
  } elseif ( preg_match( '/(^Opera|OPR).*\/([0-9\.]+)/', $ua, $matches ) ) {
    $browser = 'Opera';
    $version = $matches[ 2 ];
  }





  echo '<span class="zipang_headding_text">' . esc_html__('Usage environment' , 'toc-maker') . '</span>';

  echo '<textarea id="zipang_usage_environment" readonly rows="7" style="width:100%">';
  echo '--------------' . "\n";
  echo "Server software : " . esc_html(sanitize_text_field( $_SERVER[ 'SERVER_SOFTWARE' ] ) ) . "\n";
  
  echo "Server OS : " . esc_html(sanitize_text_field(PHP_OS) ) . "\n";
  echo "PHP version : " . esc_html(sanitize_text_field(phpversion()) ) . "\n";
  echo '--------------' . "\n";
  echo "WordPress version : " . esc_html(sanitize_text_field(get_bloginfo('version')) ) . "\n";
  echo "Multisite : " . (is_multisite() ? 'true':'false') . "\n";
  echo "Theme : " . esc_html(sanitize_text_field( get_template() ) ) . "\n";
  echo "Table of contents Maker : " . esc_html(sanitize_text_field( TOC_MAKER_VERSION ) ) . "\n";
  echo '--------------' . "\n";
  echo $device === 'unknown' ? "Device : " . esc_html(sanitize_text_field( $device ) ) . "\n" : '';
  echo "OS : " . esc_html(sanitize_text_field( $os ) ) . "\n";
  echo "Browser : " . esc_html(sanitize_text_field( $browser ) ) . ' ' . esc_html(sanitize_text_field( $version ) ) ."\n";
  echo "User Agent : " . esc_html(sanitize_text_field( $ua ) ) ."\n";
  echo '--------------' . "\n";
  echo 'Active Plugins :' . "\n";
  
  if ( ! function_exists( 'get_plugins' ) ) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
  }
  $all_plugins = get_plugins();
  $plugins = '';
  foreach ($all_plugins as $key => $value) {
    if (is_plugin_active( $key )) {
      echo esc_html(sanitize_text_field( $value['Name'] ) ) . ' ' . esc_html(sanitize_text_field( $value['Version'] ) ) . "\n";
    }
  }
  echo '--------------' . "\n";
  echo '</textarea>';
  echo '<button class="button button-primary" style="margin-top: 8px;display: block;margin-left: auto;" type="button" onclick="document.getElementById(\'zipang_usage_environment\').select();document.execCommand(\'copy\');zipang_pop_up_message(admin_zipang_translations.copy,\'#28a745\');">'.esc_html__('Copy' , 'toc-maker').'</button>';
}
