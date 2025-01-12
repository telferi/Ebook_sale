<?php
// Magyar megjegyzés: Ez a fájl a "Ebook Categories" oldalt és a kategóriák kezelését tartalmazza.

if ( ! defined('ABSPATH') ) {
    exit;
}

function my_ebook_plugin_categories_page() {

    // Új kategória létrehozása
    if ( isset($_POST['my_ebook_category_submit']) ) {
        $new_category = sanitize_text_field($_POST['new_category'] ?? '');

        if ( ! empty($new_category) ) {
            // Könyvtár létrehozása
            $upload_dir = wp_upload_dir();
            $target_dir = $upload_dir['basedir'] . '/downloads/' . $new_category . '/';
            if ( ! file_exists($target_dir) ) {
                wp_mkdir_p($target_dir);
                echo '<div style="color: green;">' . __('Kategória és könyvtár létrehozva: ', 'my-ebook-plugin') . esc_html($new_category) . '</div>';
                // Adatbázisba is felvihetjük a kategóriát
                global $wpdb;
                $wpdb->insert(
                    $wpdb->prefix . 'ebook_categories',
                    array(
                        'category_name' => $new_category
                    )
                );
            } else {
                echo '<div style="color: red;">' . __('Ez a kategória/könyvtár már létezik!', 'my-ebook-plugin') . '</div>';
            }
        }
    }

    // Kategória törlése
    if ( isset($_POST['delete_category']) ) {
        $category_id = intval($_POST['category_id'] ?? 0);
        if ( $category_id > 0 ) {
            global $wpdb;
            $category_name = $wpdb->get_var( $wpdb->prepare( "SELECT category_name FROM {$wpdb->prefix}ebook_categories WHERE id = %d", $category_id ) );
            if ( $category_name ) {
                $upload_dir = wp_upload_dir();
                $target_dir = $upload_dir['basedir'] . '/downloads/' . $category_name . '/';
                if ( file_exists($target_dir) ) {
                    // Könyvtár törlése
                    rmdir($target_dir);
                }
                // Adatbázisból törlés
                $wpdb->delete( $wpdb->prefix . 'ebook_categories', array( 'id' => $category_id ) );
                echo '<div style="color: green;">' . __('Kategória törölve: ', 'my-ebook-plugin') . esc_html($category_name) . '</div>';
            }
        }
    }

    // Kategóriák listázása
    global $wpdb;
    $categories = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}ebook_categories" );

    ?>
    <div class="wrap">
        <h1><?php _e('Ebook Categories', 'my-ebook-plugin'); ?></h1>
        <form method="post" action="">
            <table class="form-table">
                <tr>
                    <th><label for="new_category"><?php _e('New Category Name', 'my-ebook-plugin'); ?></label></th>
                    <td><input type="text" name="new_category" id="new_category" value=""></td>
                </tr>
            </table>
            <input type="submit" name="my_ebook_category_submit" 
                   value="<?php esc_attr_e('Create Category', 'my-ebook-plugin'); ?>" 
                   class="button button-primary">
        </form>

        <hr/>
        <h2><?php _e('Existing Categories', 'my-ebook-plugin'); ?></h2>
        <ul>
            <?php foreach ( $categories as $category ) : ?>
                <li>
                    <?php echo esc_html( $category->category_name ); ?>
                    <form method="post" action="" style="display:inline;">
                        <input type="hidden" name="category_id" value="<?php echo esc_attr( $category->id ); ?>">
                        <input type="submit" name="delete_category" value="<?php esc_attr_e('Delete', 'my-ebook-plugin'); ?>" class="button button-secondary">
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php
}
