<?php
// Magyar megjegyzés: Ez a fájl a "Ebooks" listaoldalt és a hozzá tartozó funkciókat tartalmazza.

if ( ! defined('ABSPATH') ) {
    exit;
}

function my_ebook_plugin_ebooks_page() {
    // Magyar megjegyzés: Itt jelenítjük meg az összes ebook listáját, szerkesztési-, törlési- és megtekintési lehetőségekkel.
    // A listában szerepeljen ID, név, kategória, ár, currency, letöltések száma, shortcode, műveletek.
    ?>
    <div class="wrap">
        <h1><?php _e('Ebooks', 'my-ebook-plugin'); ?></h1>
        <!-- Magyar megjegyzés: Itt kinyerhető adatbázisból az ebook adatok listája, és megjeleníthető táblázatban. -->
        <table class="widefat">
            <thead>
                <tr>
                    <th><?php _e('ID', 'my-ebook-plugin'); ?></th>
                    <th><?php _e('Name', 'my-ebook-plugin'); ?></th>
                    <th><?php _e('Category', 'my-ebook-plugin'); ?></th>
                    <th><?php _e('Price', 'my-ebook-plugin'); ?></th>
                    <th><?php _e('Currency', 'my-ebook-plugin'); ?></th>
                    <th><?php _e('Downloads', 'my-ebook-plugin'); ?></th>
                    <th><?php _e('Shortcode', 'my-ebook-plugin'); ?></th>
                    <th><?php _e('Actions', 'my-ebook-plugin'); ?></th>
                </tr>
            </thead>
            <tbody>
            <?php 
            // Magyar megjegyzés: Itt példa adatokkal illusztrálunk, a valóságban DB lekérdezéssel nyerjük ki.
            $sample_data = [
                ['id'=>1, 'name'=>'Sample Ebook', 'category'=>'Test Category', 'price'=>'25', 'currency'=>'USD', 'downloads'=>0],
            ];

            foreach($sample_data as $data) {
                $shortcode = '[pay_stripe_fixed amount="'. esc_attr($data['price']) .'"]';
                ?>
                <tr>
                    <td><?php echo esc_html($data['id']); ?></td>
                    <td><?php echo esc_html($data['name']); ?></td>
                    <td><?php echo esc_html($data['category']); ?></td>
                    <td><?php echo esc_html($data['price']); ?></td>
                    <td><?php echo esc_html($data['currency']); ?></td>
                    <td><?php echo esc_html($data['downloads']); ?></td>
                    <td><?php echo esc_html($shortcode); ?></td>
                    <td>
                        <!-- Magyar megjegyzés: Műveletek (szerkesztés, törlés, megtekintés) -->
                        <a href="#"><?php _e('Edit', 'my-ebook-plugin'); ?></a> | 
                        <a href="#"><?php _e('Delete', 'my-ebook-plugin'); ?></a> | 
                        <a href="#" target="_blank"><?php _e('View', 'my-ebook-plugin'); ?></a>
                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
    <?php
}
