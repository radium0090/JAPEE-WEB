<?php

function wppg_admin_scripts()
{
	wp_enqueue_script('jquery');
}

function dfm_wcpgpp_product_payments_settings() {
    $user = wp_get_current_user();
    ?>
    <div class="wrap "><div id="icon-tools" class="icon32"></div>
        <div class="left-mc-setting">
            <?php
            softsdev_sdwpp_plugin_settings();
            ?>
        </div>
        <div class="right-mc-setting">
        </div>
        <?php
    }

    function softsdev_get_category_ul($categories, $selected_cats, $class, $parent_id, $id = '', $name = '') {

        if ($parent_id > 0) {
            ?>
            <optgroup label="<?php echo $name; ?>">
                <?php
            }

            foreach ($categories[$parent_id] as $data) {
                $child_id = $data->term_taxonomy_id;
                ?>
                <option value="<?php echo $data->term_taxonomy_id; ?>" <?php if (in_array($data->term_taxonomy_id, $selected_cats)) { ?> selected="selected" <?php } ?>><?php echo $data->name; ?></option>
                <?php
                if (isset($categories[$child_id])) {
                    softsdev_get_category_ul($categories, $selected_cats, 'children', $child_id, '', $data->name);
                }
            }

            if ($parent_id > 0) {
                ?>
            </optgroup>
            <?php
        }
    }

    /**
     * Setting form
     */
    function softsdev_sdwpp_plugin_settings() {
        /**
         * Settings default
         */
        if (isset($_POST['sdwpp_setting'])) {
            if (isset($_POST['tax_input']['product_cat'])) {
                update_option('softsdev_selected_cats', implode(',', array_filter(array_map('sanitize_title', $_POST['tax_input']['product_cat']))));
            }
            update_option('sdwpp_plugin_settings', array_filter(array_map('sanitize_title', $_POST['sdwpp_setting'])));
        }
        $softsdev_wpp_plugin_settings = get_option('sdwpp_plugin_settings', array('default_payment' => ''));
        $default_payment = $softsdev_wpp_plugin_settings['default_payment'];

        $softsdev_selected_cats = explode(',', get_option('softsdev_selected_cats'));

        $args = array(
            'hide_empty' => 0,
            'orderby' => 'id',
            'order' => 'ASC',
        );
        $product_categories = get_terms('product_cat', $args);

        foreach ($product_categories as $cat_data) {
            $cats[$cat_data->parent][] = $cat_data;
        }
        ?>
        <form id="woo_sdwpp" action="<?php echo $_SERVER['PHP_SELF'] . '?page=s_japee-product-payments' ?>" method="post">
            <div class="postbox " style="padding: 10px 0; margin: 10px 0px;">
                <h3 class="hndle"><?php echo __('Select Categories:'); ?></h3>
                <div class="categorydiv">
                    <select class="multi_select" name="tax_input[product_cat][]" multiple  size="8">
                        <?php
                        softsdev_get_category_ul($cats, $softsdev_selected_cats, 'categorychecklist form-no-clear', 0, 'product_catchecklist');
                        ?>
                    </select><br>
                    <small>
                        <?php
                        echo __('You can select any 2 categories for this functionality due to free plugin.', 'softsdev');
                        ?>
                    </small>
                </div>
                <br /><br />
                <h3 class="hndle"><?php echo __('Default Payment option( If not match any.)', 'softsdev'); ?></h3>
                <?php
                $woo = new WC_Payment_Gateways();
                $payments = $woo->payment_gateways;
                ?>
                <select id="sdwpp_default_payment" name="sdwpp_setting[default_payment]">
                    <option value=""><?php echo __('None', 'softsdev'); ?></option>
                    <?php
                    foreach ($payments as $pay) {
                        /**
                         *  skip if payment in disbled from admin
                         */
                        if ($pay->enabled == 'no') {
                            continue;
                        }
                        echo "<option value = '" . $pay->id . "' " . selected($default_payment, $pay->id) . ">" . $pay->title . "</option>";
                    }
                    ?>
                </select>
                <br />
                <small><?php echo __('If in some case payment option not show then this will default one set', 'softsdev'); ?></small>
            </div>
            <input class="button-large button-primary" type="submit" value="<?php echo __('Save Changes', 'softsdev'); ?>" />
        </form>  
        <Script>
            var on_s_change = true;
            function updateSelectBox() {
                var selectedValues = jQuery(this).val();
                 
                jQuery('.multi_select option').removeAttr('disabled');
                on_s_change = true;
            }
            jQuery(document).ready(function() {
                on_s_change = false;
                jQuery('.multi_select').trigger('change');
            });
            jQuery('.multi_select').change(updateSelectBox);
        </script>
        <?php
    }
    