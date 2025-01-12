<?php
// Magyar megjegyzés: Ez a fájl a "Ebook Categories" oldalt és a kategóriák kezelését tartalmazza.

if ( ! defined('ABSPATH') ) {
    exit;
}

function my_ebook_plugin_categories_page() {

    // Magyar megjegyzés: Itt lehet létrehozni új kategóriákat.
    // Kategória létrehozásakor egy könyvtár jön létre a wp-content/uploads/downloads/ könyvtár alatt.

    if ( isset($_POST['my_ebook_category_submit']) ) {
        $new_category = sanitize_text_field($_POST['new_category'] ?? '');

        if ( ! empty($new_category) ) {
            // Magyar megjegyzés: Könyvtár létrehozása.
            $upload_dir = wp_upload_dir();
            $target_dir = $upload_dir['basedir'] . '/downloads/' . $new_category . '/';
            if ( ! file_exists($target_dir) ) {
                wp_mkdir_p($target_dir);
                echo '<div style="color: green;">' . __('Kategória és könyvtár létrehozva: ', 'my-ebook-plugin') . esc_html($new_category) . '</div>';
                // Magyar megjegyzés: Itt adatbázisba is felvihetjük a kategóriát, ha szükséges.
            } else {
                echo '<div style="color: red;">' . __('Ez a kategória/könyvtár már létezik!', 'my-ebook-plugin') . '</div>';
            }
        }
    }
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
        <!-- Magyar megjegyzés: Itt megjeleníthetjük a már meglévő kategóriát/böngészését is. -->
        <h2><?php _e('Existing Categories', 'my-ebook-plugin'); ?></h2>
        <ul>
            <li>Default Category</li>
            <!-- Magyar megjegyzés: Példa. A valóságban adatbázisból listázzuk. -->
        </ul>
    </div>
    <?php
}
