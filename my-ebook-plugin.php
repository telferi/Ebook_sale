<?php
/*
Plugin Name: Ebook Sales Plugin for Frank
Plugin URI: https://savinginvesting.blog
Description: A plugin for managing eBook sales.
Version: 1.3
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
require_once MY_EBOOK_PLUGIN_DIR . 'includes/admin-meta-boxes.php';

// Custom post type regisztrálása
function create_ebook_post_type() {
    register_post_type('ebook',
        array(
            'labels' => array(
                'name' => __('Ebooks'),
                'singular_name' => __('Ebook')
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'comments'),
            'rewrite' => array('slug' => 'ebooks'),
        )
    );
}
add_action('init', 'create_ebook_post_type');

// Plugin aktiválási hook
function my_ebook_plugin_activate() {
    // Inicializálás
    create_ebook_post_type();
    flush_rewrite_rules();

    // Adatbázis tábla létrehozás
    global $wpdb;
    $table_name = $wpdb->prefix . 'ebook_categories';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        category_name varchar(255) NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}
register_activation_hook(__FILE__, 'my_ebook_plugin_activate');

// Plugin deaktiválási hook
function my_ebook_plugin_deactivate() {
    // Takarítás
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'my_ebook_plugin_deactivate');
