<?php

if(!defined('WMTM_ABSPATH')) exit;

// Scripts and styles

add_action('admin_enqueue_scripts', function(){
    wp_enqueue_style('wmtm-admin-style',        WMTM_URI.'/assets/css/admin.css', array(), WMTM_PLUGIN_VERSION, 'all');
    wp_enqueue_script('wmtm-admin-form-js', 	WMTM_URI.'/lib/js/jquery.form.min.js', array(), null, true);
    wp_enqueue_script('wmtm-admin-js', 	        WMTM_URI.'/assets/js/admin.js', array(), WMTM_PLUGIN_VERSION, true);
});

// Settings page

function wmtm_add_settings_page() {
    add_menu_page(
        __('WM Tag Manager', WMTM_PLUGIN_SLUG),
        __('WM Tag Manager', WMTM_PLUGIN_SLUG),
        'manage_options',
        'wm-tag-manager',
        'wmtm_plugin_settings_page',
        'dashicons-admin-generic',
        75
    );
}

if(empty(get_option('wmtm_l'))){
    add_action('admin_menu', 'wmtm_add_settings_page');
}

function wmtm_plugin_settings_page(){
    $settings = wmtm_get_settings();
    ?>
    <form method="POST" class="wmtm-form"
        action="<?php wmtm_get_ajax_action_url('wmtm_change_settings'); ?>"
        data-enable-tab-action="<?php wmtm_get_ajax_action_url('wmtm_enable_tab'); ?>">

        <input type="hidden" name="submit_fl" value="">

        <div class="wmtm-settings-tabs">

            <div class="wmtm-settings-tabs-head">
                <a href="#google-tag-manager" class="wmtm-settings-tabs-item">
                    <?php _e('Google Tag Manager', WMTM_PLUGIN_SLUG); ?>
                </a>
                <a href="#google-analytics" class="wmtm-settings-tabs-item">
                    <?php _e('Google Analytics', WMTM_PLUGIN_SLUG); ?>
                </a>
                <a href="#google-ads" class="wmtm-settings-tabs-item">
                    <?php _e('Google Ads global site tag', WMTM_PLUGIN_SLUG); ?>
                </a>
                <a href="#facebook" class="wmtm-settings-tabs-item">
                    <?php _e('Facebook', WMTM_PLUGIN_SLUG); ?>
                </a>
            </div>

            <div class="wmtm-settings-tabs-content" id="google-tag-manager">
                <div class="wmtm-form-field wmtm-checkbox wmtm-switcher"
                    data-text-on="<?php _e('Enabled', WMTM_PLUGIN_SLUG); ?>"
                    data-text-off="<?php _e('Disabled', WMTM_PLUGIN_SLUG); ?>">
                    <input type="checkbox"
                        name="tag_manager_enabled"
                        value="1"
                        id="wmtm_tag_manager_enabled"
                        <?php if(!empty($settings['tag_manager_enabled'])) echo ' checked'; ?>>
                    <label for="wmtm_tag_manager_enabled" class="wmtm-form-label"></label>
                </div>
                <div class="wmtm-form-info">
                    <?php _e('Please fill the Google Tag Manager container ID in the field below.', WMTM_PLUGIN_SLUG); ?>
                </div>
                <div class="wmtm-form-field last">
                    <input type="text" name="tag_manager_id"
                       value="<?php esc_attr_e($settings['tag_manager_id']); ?>"
                       id="wmtm_tag_manager_id"
                       placeholder="GTM-AAAAAAAA">
                    <button type="submit" class="wmtm-form-submit disabled" data-submit="tag_manager_id">
                        <?php
                        $bt_text = empty($settings['tag_manager_id']) || empty($settings['tag_manager_enabled']) ? 'Update and activate' : 'Update';
                        _e($bt_text, WMTM_PLUGIN_SLUG);
                        ?>
                    </button>
                </div>
            </div>

            <div class="wmtm-settings-tabs-content" id="google-analytics">
                <div class="wmtm-form-field wmtm-checkbox wmtm-switcher"
                    data-text-on="<?php _e('Enabled', WMTM_PLUGIN_SLUG); ?>"
                    data-text-off="<?php _e('Disabled', WMTM_PLUGIN_SLUG); ?>">
                    <input type="checkbox"
                        name="analytics_enabled"
                        value="1"
                        id="wmtm_analytics_enabled"
                        <?php if(!empty($settings['analytics_enabled'])) echo ' checked'; ?>>
                    <label for="wmtm_analytics_enabled" class="wmtm-form-label">Enabled</label>
                </div>
                <div class="wmtm-form-info">
                    <?php _e('Please fill the Google analytics Tracking ID in the field below.', WMTM_PLUGIN_SLUG); ?>
                </div>
                <div class="wmtm-form-field last">
                    <input type="text" name="analytics_id"
                       value="<?php esc_attr_e($settings['analytics_id']); ?>"
                       id="wmtm_analytics_id"
                       placeholder="UA-123456789-0">
                    <button type="submit" class="wmtm-form-submit disabled" data-submit="analytics_id">
                        <?php
                        $bt_text = empty($settings['analytics_id']) || empty($settings['analytics_enabled']) ? 'Update and activate' : 'Update';
                        _e($bt_text, WMTM_PLUGIN_SLUG);
                        ?>
                    </button>
                </div>
            </div>

            <div class="wmtm-settings-tabs-content" id="google-ads">
                <div class="wmtm-form-field wmtm-checkbox wmtm-switcher"
                    data-text-on="<?php _e('Enabled', WMTM_PLUGIN_SLUG); ?>"
                    data-text-off="<?php _e('Disabled', WMTM_PLUGIN_SLUG); ?>">
                    <input type="checkbox"
                        name="adwords_enabled"
                        value="1"
                        id="wmtm_adwords_enabled"
                        <?php if(!empty($settings['adwords_enabled'])) echo ' checked'; ?>>
                    <label for="wmtm_adwords_enabled" class="wmtm-form-label">Enabled</label>
                </div>
                <div class="wmtm-form-info">
                    <?php _e('Please fill the global site tag in the field below.', WMTM_PLUGIN_SLUG); ?>
                </div>
                <div class="wmtm-form-field last">
                    <input type="text" name="adwords_id"
                        value="<?php esc_attr_e($settings['adwords_id']); ?>"
                        id="wmtm_adwords_id"
                        placeholder="AW-123456789">
                    <button type="submit" class="wmtm-form-submit disabled" data-submit="adwords_id">
                        <?php
                        $bt_text = empty($settings['adwords_id']) || empty($settings['adwords_enabled']) ? 'Update and activate' : 'Update';
                        _e($bt_text, WMTM_PLUGIN_SLUG);
                        ?>
                    </button>
                </div>
            </div>

            <div class="wmtm-settings-tabs-content" id="facebook">
                <div class="wmtm-form-field wmtm-checkbox wmtm-switcher"
                    data-text-on="<?php _e('Enabled', WMTM_PLUGIN_SLUG); ?>"
                    data-text-off="<?php _e('Disabled', WMTM_PLUGIN_SLUG); ?>">
                    <input type="checkbox"
                        name="facebook_enabled"
                        value="1"
                        id="wmtm_facebook_enabled"
                        <?php if(!empty($settings['facebook_enabled'])) echo ' checked'; ?>>
                    <label for="wmtm_facebook_enabled" class="wmtm-form-label">Enabled</label>
                </div>
                <div class="wmtm-form-info">
                    <?php _e('Please fill the Facebook Pixel ID in the field below.', WMTM_PLUGIN_SLUG); ?>
                </div>
                <div class="wmtm-form-field last">
                    <input type="text" name="facebook_id"
                        value="<?php esc_attr_e($settings['facebook_id']); ?>"
                        id="wmtm_facebook_id"
                        placeholder="1234567890123456789">
                    <button type="submit" class="wmtm-form-submit disabled" data-submit="facebook_id">
                        <?php
                        $bt_text = empty($settings['facebook_id']) || empty($settings['facebook_enabled']) ? 'Update and activate' : 'Update';
                        _e($bt_text, WMTM_PLUGIN_SLUG);
                        ?>
                    </button>
                </div>
            </div>
        </div>

        <div class="wmtm-result"></div>

        <div class="wmtm-form-bottom">
            <div class="wmtm-poweredby">Powered by <a href="https://www.wemake.co.il/"><span class="blue">we</span>make</a></div>
        </div>
    </form>
    <?php
}

// Admin footer

add_action('admin_footer', function(){
    ?>
    <script>
        var wmtm_language = {
            "unsaved_changes": "<?php _e('You have unsaved changes', WMTM_PLUGIN_SLUG); ?>",
            "request_error": "<?php _e('Request error!', WMTM_PLUGIN_SLUG); ?>",
            "success": "<?php _e('Settings successfully changed', WMTM_PLUGIN_SLUG); ?>",
            "update_error": "<?php _e('Error. Please try again later.', WMTM_PLUGIN_SLUG); ?>",
            "submit_text1": "<?php _e('Update', WMTM_PLUGIN_SLUG); ?>",
            "submit_text2": "<?php _e('Update and activate', WMTM_PLUGIN_SLUG); ?>",
        };
    </script>
    <?php
});

// "Settings" link

add_action('admin_footer', function(){
    if(!preg_match('/plugins\.php/', $_SERVER['REQUEST_URI'])){
        return false;
    }
    ?>
    <script>
        jQuery(function($){
            $("[data-slug='wemake-tag-manager'] .plugin-version-author-uri").append(" | <a href=\'<?php echo get_admin_url() . 'options-general.php?page=wm-tag-manager'; ?>\'><?php _e('Settings', WMTM_PLUGIN_SLUG) ?></a>");
        });
    </script>
    <?php
});

// Update

require_once(WMTM_ABSPATH . '/inc/admin_update.php');

?>