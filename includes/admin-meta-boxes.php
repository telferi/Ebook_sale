<?php
// Meta boxok hozzáadása az eBook bejegyzésekhez
function my_ebook_add_meta_boxes() {
    add_meta_box(
        'my_ebook_details_meta_box',
        __('Ebook Details', 'my-ebook-plugin'),
        'my_ebook_details_meta_box_callback',
        'ebook',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'my_ebook_add_meta_boxes');

// Meta box tartalom
function my_ebook_details_meta_box_callback($post) {
    wp_nonce_field('my_ebook_save_meta_box_data', 'my_ebook_meta_box_nonce');

    $price = get_post_meta($post->ID, '_my_ebook_price', true);
    $currency = get_post_meta($post->ID, '_my_ebook_currency', true);
    $category = get_post_meta($post->ID, '_my_ebook_category', true);

    echo '<label for="my_ebook_price">' . __('Price', 'my-ebook-plugin') . '</label> ';
    echo '<input type="number" id="my_ebook_price" name="my_ebook_price" value="' . esc_attr($price) . '" min="0" step="0.01" size="25" />';

    echo '<label for="my_ebook_currency">' . __('Currency', 'my-ebook-plugin') . '</label> ';
    echo '<select id="my_ebook_currency" name="my_ebook_currency">';
    echo '<option value="USD"' . selected($currency, 'USD', false) . '>USD</option>';
    echo '<option value="GBP"' . selected($currency, 'GBP', false) . '>GBP</option>';
    echo '<option value="Euro"' . selected($currency, 'Euro', false) . '>Euro</option>';
    echo '</select>';

    echo '<label for="my_ebook_category">' . __('Category', 'my-ebook-plugin') . '</label> ';
    echo '<input type="text" id="my_ebook_category" name="my_ebook_category" value="' . esc_attr($category) . '" size="25" />';
}

// Meta adatok mentése
function my_ebook_save_meta_box_data($post_id) {
    if (!isset($_POST['my_ebook_meta_box_nonce'])) {
        return;
    }
    if (!wp_verify_nonce($_POST['my_ebook_meta_box_nonce'], 'my_ebook_save_meta_box_data')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['my_ebook_price'])) {
        $price = sanitize_text_field($_POST['my_ebook_price']);
        update_post_meta($post_id, '_my_ebook_price', $price);
    }

    if (isset($_POST['my_ebook_currency'])) {
        $currency = sanitize_text_field($_POST['my_ebook_currency']);
        update_post_meta($post_id, '_my_ebook_currency', $currency);
    }

    if (isset($_POST['my_ebook_category'])) {
        $category = sanitize_text_field($_POST['my_ebook_category']);
        update_post_meta($post_id, '_my_ebook_category', $category);
    }
}
add_action('save_post', 'my_ebook_save_meta_box_data');
