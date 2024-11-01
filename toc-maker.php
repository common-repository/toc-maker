<?php
/*
Plugin Name: Table of contents Maker
Description: Table of contents Maker is a plugin that checks the headings in an article and automatically inserts a table of contents.
Version: 0.9.1
Author: ZIPANG
Author URI: https://zipang.dev/
License: GNU General Public License v3 or later
Text Domain: toc-maker
Domain Path: /languages/
*/

/*
    Table of contents Maker
    Copyright (C) 2024 ZIPANG

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/
    defined( 'ABSPATH' ) || exit;

    $data = get_file_data( __FILE__, array( 'Version' ) );

    define( 'TOC_MAKER_VERSION', $data[0] );
    define( 'TOC_MAKER_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
    define( 'TOC_MAKER_URI', trailingslashit( esc_url( plugin_dir_url( __FILE__ ) ) ) );
    define( 'TOC_MAKER_PLUGIN_FILE', __FILE__ );



    
    if(is_admin()){
        require_once TOC_MAKER_DIR . 'inc/settings/admin.php';
    }else{
        require_once TOC_MAKER_DIR . 'inc/front.php';
        require_once TOC_MAKER_DIR . 'inc/toc.php';
    }

    require_once TOC_MAKER_DIR . 'inc/widget/widget-toc.php';

