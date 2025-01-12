<?php
// Magyar megjegyzés: Ez a fájl a "Add New Ebook" aloldalt kezeli, ahol létrehozható és feltölthető az ebook fájl valamint a kép.

if ( ! defined('ABSPATH') ) {
    exit;
}

function my_ebook_plugin_add_new_page() {
    // Magyar megjegyzés: Feldolgozzuk a form küldést, ellenőrzéseket végzünk stb.

    if ( isset($_POST['my_ebook_submit']) ) {

        // Magyar megjegyzés: Bemeneti adatok ellenőrzése, pl. title, slug, leírás, ár, currency, kategória stb.
        $ebook_title = sanitize_text_field($_POST['ebook_title'] ?? '');
        $ebook_slug  = sanitize_title($_POST['ebook_slug'] ?? '');
        $ebook_price = floatval($_POST['ebook_price'] ?? 0);
        $ebook_currency = sanitize_text_field($_POST['ebook_currency'] ?? 'USD');
        $ebook_description = wp_kses_post($_POST['ebook_description'] ?? '');
        $ebook_category = sanitize_text_field($_POST['ebook_category'] ?? '');

        // Magyar megjegyzés: Ha nincs title megadva, vegye fel a feltöltött fájl nevét.
        // Ezért itt csak akkor állítjuk be, ha üres a mező.
        if ( empty($ebook_title) && ! empty($_FILES['ebook_file']['name']) ) {
            $ebook_title = pathinfo($_FILES['ebook_file']['name'], PATHINFO_FILENAME);
        }
        if ( empty($ebook_slug) && ! empty($ebook_title) ) {
            $ebook_slug = sanitize_title($ebook_title);
        }

        // Magyar megjegyzés: Ellenőrizzük a fájlokat (ebook és kép).
        $errors = [];
        $allowed_ebook_types = ['application/pdf']; // Magyar megjegyzés: Később bővíthető
        $allowed_image_types = ['image/jpeg','image/png','image/gif']; 

        // Ebook fájl ellenőrzése
        if ( ! empty($_FILES['ebook_file']['tmp_name']) ) {
            $ebook_file_type = $_FILES['ebook_file']['type'];
            // Magyar megjegyzés: Ellenőrizzük, hogy a feltöltött fájl szerepel-e az engedélyezett típusok közt.
            if ( ! in_array($ebook_file_type, $allowed_ebook_types) ) {
                $errors[] = __('A feltöltött ebook fájl típusa nem megfelelő (csak PDF megengedett).', 'my-ebook-plugin');
            }
        } else {
            $errors[] = __('Nem töltöttél fel ebook fájlt.', 'my-ebook-plugin');
        }

        // Kép fájl ellenőrzése
        if ( ! empty($_FILES['ebook_image']['tmp_name']) ) {
            $ebook_image_type = $_FILES['ebook_image']['type'];
            if ( ! in_array($ebook_image_type, $allowed_image_types) ) {
                $errors[] = __('A feltöltött kép fájl típusa nem megfelelő (csak JPG, PNG vagy GIF).', 'my-ebook-plugin');
            }
        } else {
            $errors[] = __('Nem töltöttél fel képfájlt.', 'my-ebook-plugin');
        }

        // Ár és leírás ellenőrzése
        if ( $ebook_price < 0 ) {
            $errors[] = __('Az ár nem lehet negatív!', 'my-ebook-plugin');
        }

        // Ha az ár 0, írja át "Free" értékre
        if ($ebook_price === 0) {
            $ebook_price = 'Free';
        }

        // Leírás hossza
        $desc_length = strlen(strip_tags($ebook_description));
        if ( $desc_length < 200 || $desc_length > 4000 ) {
            $errors[] = __('Az ebook leírásának 200 és 4000 karakter között kell lennie.', 'my-ebook-plugin');
        }

        // Könyvtár létrehozása a kiválasztott kategória alapján
        $upload_dir = wp_upload_dir();
        $target_dir = $upload_dir['basedir'] . '/downloads/' . $ebook_category . '/';

        // Ha nincs ilyen könyvtár, létrehozzuk
        if ( ! file_exists($target_dir) ) {
            wp_mkdir_p($target_dir);
        }

        if ( empty($errors) ) {
            // Magyar megjegyzés: Feltöltjük a fájlokat
            $ebook_file_path = $target_dir . basename($_FILES['ebook_file']['name']);
            $ebook_img_path  = $target_dir . basename($_FILES['ebook_image']['name']);

            $ebook_uploaded = move_uploaded_file($_FILES['ebook_file']['tmp_name'], $ebook_file_path);
            $img_uploaded   = move_uploaded_file($_FILES['ebook_image']['tmp_name'], $ebook_img_path);

            if ( $ebook_uploaded && $img_uploaded ) {
                // Magyar megjegyzés: Sikeres feltöltés
                // Itt elmenthetjük adatbázisba az ebook adatait (title, slug, ár, currency, stb.).
                // Példa success üzenet
                echo '<div style="color: green;">';
                echo __('A fájlok sikeresen feltöltve: ', 'my-ebook-plugin');
                echo esc_html($_FILES['ebook_file']['name']) . ' és ' . esc_html($_FILES['ebook_image']['name']);
                echo '</div>';
            } else {
                $errors[] = __('Hiba történt a fájlok feltöltése közben.', 'my-ebook-plugin');
            }
        }

        // Magyar megjegyzés: Ha vannak hibák, listázzuk ki őket.
        if ( ! empty($errors) ) {
            echo '<div style="color: red;">';
            foreach($errors as $error) {
                echo '<p>' . esc_html($error) . '</p>';
            }
            echo '</div>';
        }
    }

    // Magyar megjegyzés: HTML űrlap a feltöltéshez
    ?>
    <div class="wrap">
        <h1><?php _e('Add New Ebook', 'my-ebook-plugin'); ?></h1>

        <form method="post" enctype="multipart/form-data">
            <table class="form-table">
                <tr>
                    <th><label for="ebook_title"><?php _e('Title', 'my-ebook-plugin'); ?></label></th>
                    <td><input type="text" name="ebook_title" id="ebook_title" value="" /></td>
                </tr>
                <tr>
                    <th><label for="ebook_slug"><?php _e('Slug', 'my-ebook-plugin'); ?></label></th>
                    <td><input type="text" name="ebook_slug" id="ebook_slug" value="" /></td>
                </tr>
                <tr>
                    <th><label for="ebook_price"><?php _e('Price', 'my-ebook-plugin'); ?></label></th>
                    <td><input type="number" name="ebook_price" id="ebook_price" value="0" min="0" step="0.01" /></td>
                </tr>
                <tr>
                    <th><label for="ebook_currency"><?php _e('Currency', 'my-ebook-plugin'); ?></label></th>
                    <td>
                        <select name="ebook_currency" id="ebook_currency">
                            <option value="USD">USD</option>
                            <option value="GBP">GBP</option>
                            <option value="Euro">Euro</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="ebook_category"><?php _e('Category', 'my-ebook-plugin'); ?></label></th>
                    <td>
                        <select name="ebook_category">
                            <!-- Magyar megjegyzés: Itt a valóságban a kategóriák DB adatai alapján töltjük fel a lehetőségeket. -->
                            <option value="default_category"><?php _e('Default Category', 'my-ebook-plugin'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="ebook_description"><?php _e('Description', 'my-ebook-plugin'); ?></label></th>
                    <td>
                        <?php
                        // Magyar megjegyzés: Gutenberg szerkesztő helyett egyszerű textarea példa.
                        // A tényleges Editors API segítségével használhatjuk a Gutenberg szerkesztőt is.
                        ?>
                        <textarea name="ebook_description" id="ebook_description" rows="6" cols="60"></textarea>
                    </td>
                </tr>
                <tr>
                    <th><label for="ebook_file"><?php _e('Ebook File (PDF)', 'my-ebook-plugin'); ?></label></th>
                    <td><input type="file" name="ebook_file" id="ebook_file"></td>
                </tr>
                <tr>
                    <th><label for="ebook_image"><?php _e('Ebook Image', 'my-ebook-plugin'); ?></label></th>
                    <td><input type="file" name="ebook_image" id="ebook_image"></td>
                </tr>
            </table>
            <!-- Magyar megjegyzés: Gomb a fájlfeltöltés indítására -->
            <input type="submit" name="my_ebook_submit" value="<?php esc_attr_e('Upload & Save', 'my-ebook-plugin'); ?>" class="button button-primary">
        </form>
    </div>
    <?php
}
