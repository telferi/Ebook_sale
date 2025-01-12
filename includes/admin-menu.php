<?php
// Magyar megjegyzés: Létrehozzuk a főmenüt és a három aloldalt (sub-menu-t).

if ( ! defined('ABSPATH') ) {
    exit;
}

function my_ebook_plugin_admin_menu() {
    // Magyar megjegyzés: Főmenü hozzáadása "Ebook" néven.
    add_menu_page(
        __('Ebook', 'my-ebook-plugin'),
        __('Ebook', 'my-ebook-plugin'),
        'manage_options',
        'my-ebook-plugin',
        'my_ebook_plugin_ebooks_page',    // Kezelőfüggvény a "Ebooks" oldalhoz
        'dashicons-book-alt',            // Menü ikon
        6
    );

    // Magyar megjegyzés: "Ebooks" oldal (ez lesz a főoldal is).
    add_submenu_page(
        'my-ebook-plugin',
        __('Ebooks', 'my-ebook-plugin'),
        __('Ebooks', 'my-ebook-plugin'),
        'manage_options',
        'my-ebook-plugin',
        'my_ebook_plugin_ebooks_page'
    );

    // Magyar megjegyzés: "Add New Ebook" oldal.
    add_submenu_page(
        'my-ebook-plugin',
        __('Add New Ebook', 'my-ebook-plugin'),
        __('Add New Ebook', 'my-ebook-plugin'),
        'manage_options',
        'my-ebook-plugin-add',
        'my_ebook_plugin_add_new_page'
    );

    // Magyar megjegyzés: "Ebook Categories" oldal.
    add_submenu_page(
        'my-ebook-plugin',
        __('Ebook Categories', 'my-ebook-plugin'),
        __('Ebook Categories', 'my-ebook-plugin'),
        'manage_options',
        'my-ebook-plugin-categories',
        'my_ebook_plugin_categories_page'
    );
}

add_action('admin_menu', 'my_ebook_plugin_admin_menu');
