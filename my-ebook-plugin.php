<?php
/*
Plugin Name: Ebook Sales Plugin for Frank
Plugin URI: https://savinginvesting.blog
Description: A plugin for managing eBook sales.
Version: 1.0
Author: Frank Smith
Author URI: https://savinginvesting.blog
License: GPL2
Text Domain: savinginvesting
*/

// Prevent direct access
if ( ! defined('ABSPATH') ) {
    exit;
}

// Define constants
define('MY_EBOOK_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MY_EBOOK_PLUGIN_URL', plugin_dir_url(__FILE__));

// Enqueue admin styles and scripts
function my_ebook_plugin_admin_assets() {
    wp_enqueue_style('my-ebook-plugin-admin-styles', MY_EBOOK_PLUGIN_URL . 'assets/css/admin-styles.css');
    wp_enqueue_script('my-ebook-plugin-admin-scripts', MY_EBOOK_PLUGIN_URL . 'assets/js/admin-scripts.js', array('jquery'), '', true);
}
add_action('admin_enqueue_scripts', 'my_ebook_plugin_admin_assets');

// Include required files
require_once MY_EBOOK_PLUGIN_DIR . 'includes/admin-menu.php';
require_once MY_EBOOK_PLUGIN_DIR . 'includes/admin-page-ebooks.php';
require_once MY_EBOOK_PLUGIN_DIR . 'includes/admin-page-add.php';
require_once MY_EBOOK_PLUGIN_DIR . 'includes/admin-page-categories.php';

// Plugin activation hook
function my_ebook_plugin_activate() {
    // Initial setup
}
register_activation_hook(__FILE__, 'my_ebook_plugin_activate');

// Plugin deactivation hook
function my_ebook_plugin_deactivate() {
    // Cleanup
}
register_deactivation_hook(__FILE__, 'my_ebook_plugin_deactivate');
