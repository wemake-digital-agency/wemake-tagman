<?php

if(!defined( 'WMTM_ABSPATH')) exit;

// Maintenance mode

add_action('template_redirect', function(){
    if(wmtm_get_maintenance_status()){
        die('<h1 align="center">'.__('Briefly unavailable for scheduled maintenance. Check back in a minute.', WMTM_PLUGIN_SLUG).'</h1>');
    }
});

if(!empty(get_option('wmtm_l'))){
    return false;
}

// Show tags

add_action('wp_head', function(){

    $settings = wmtm_get_settings();

    ?>
    <?php if(!empty($settings['tag_manager_enabled'])){ ?>
        <!-- Google Tag Manager (Wemake Tag Manager) -->
        <script>
            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','<?php esc_html_e($settings['tag_manager_id']); ?>');
        </script>
        <!-- End Google Tag Manager (Wemake Tag Manager) -->
    <?php } ?>
    <?php if(!empty($settings['analytics_enabled'])){ ?>
        <!-- Google Analytics (Wemake Tag Manager) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php esc_html_e($settings['analytics_id']); ?>"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '<?php esc_html_e($settings['analytics_id']); ?>');
        </script>
        <!-- End Google Analytics (Wemake Tag Manager) -->
    <?php } ?>
    <?php if(!empty($settings['adwords_enabled'])){ ?>
        <!-- Global site tag - Google Ads: <?php esc_html_e($settings['adwords_id']); ?> (Wemake Tag Manager) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php esc_html_e($settings['adwords_id']); ?>"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '<?php esc_html_e($settings['adwords_id']); ?>');
        </script>
        <!-- End Global site tag (Wemake Tag Manager) -->
    <?php } ?>
    <?php if(!empty($settings['facebook_enabled'])){ ?>
        <!-- Facebook Pixel Code (Wemake Tag Manager) -->
        <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                n.callMethod.apply(n,arguments):n.queue.push(arguments)};
                if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
                n.queue=[];t=b.createElement(e);t.async=!0;
                t.src=v;s=b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t,s)}(window, document,'script',
                'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '<?php esc_html_e($settings['facebook_id']); ?>');
            fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=<?php esc_html_e($settings['facebook_id']); ?>&ev=PageView&noscript=1"
        /></noscript>
        <!-- End Facebook Pixel Code (Wemake Tag Manager) -->
    <?php } ?>

    <?php
}, -999);

add_action('wp_footer', function(){
    if(empty(get_option('wmtm_tag_manager_enabled'))){
        return false;
    }
    ?>
        <!-- Google Tag Manager (noscript) (Wemake Tag Manager) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php esc_html_e(get_option('wmtm_tag_manager_id')); ?>"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) (Wemake Tag Manager) -->
    <?php
}, -999);

?>