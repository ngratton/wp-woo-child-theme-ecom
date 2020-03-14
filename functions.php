<?php

/**
 * Storefront automatically loads the core CSS even if using a child theme as it is more efficient
 * than @importing it in the child theme style.css file.
 *
 * Uncomment the line below if you'd like to disable the Storefront Core CSS.
 *
 * If you don't plan to dequeue the Storefront Core CSS you can remove the subsequent line and as well
 * as the sf_child_theme_dequeue_style() function declaration.
 */
//add_action( 'wp_enqueue_scripts', 'sf_child_theme_dequeue_style', 999 );

/**
 * Dequeue the Storefront Parent theme core CSS
 */
function sf_child_theme_dequeue_style() {
    wp_dequeue_style( 'storefront-style' );
    wp_dequeue_style( 'storefront-woocommerce-style' );
}

/**
 * Note: DO NOT! alter or remove the code above this text and only add your custom PHP functions below this text.
 */

/**
 * Pour satisfaire les exigences de Theme Check
 */
add_theme_support( "custom-background", $args );
add_theme_support( "custom-header", $args );

/**
 * *********
 *  ACTIONS
 * *********
 */


/**
 * FOOTER STOREFRONT
 * 
 * @see theme_credit_ng()
 */

function storefront_credit() {
    $auteur = wp_get_theme()->display('Author', FALSE);
    echo '
        <p>&copy; ' . date("Y") .' - '. $auteur . '</p>
    ';
}
add_action( 'storefront_footer', 'storefront_credit', 20 );

// remove_action( 'storefront_header', 'storefront_site_branding', 20);

/**
 * Enlever les sidebar sur les pages produits
 */
function remove_storefront_sidebar() {
    if ( is_product() ) {
        remove_action( 'storefront_sidebar', 'storefront_get_sidebar', 10 );
	}
}
add_action( 'get_header', 'remove_storefront_sidebar' );


/**
 * Imposer des colones de 4 produits
 * sur les pages de catalogue
 */
if( is_shop() ) {

    add_filter('loop_shop_columns', 'loop_columns', 999);
    if (!function_exists('loop_columns')) {
        function loop_columns() {
            return 4; // 4 produits par ligne
        }
    }

}

 /**
  * Dashboard
  * @see logo_custom()
  * @see change_wp_dashboard_footer()
  */

// Ajout d'un logo custom au dashboard
function logo_custom() {
    // Les autres proprietes CSS sont dans le fichier style.css
    echo '
        <style type="text/css">
            #wpadminbar #wp-admin-bar-wp-logo > .ab-item .ab-icon:before {
                background-image: url(' . get_stylesheet_directory_uri() . '/images/ng-logo.png) !important;
                background-position: 0 0;
                color:rgba(0, 0, 0, 0);
            }

            #wpadminbar #wp-admin-bar-wp-logo > .ab-item .ab-icon:before {
                background-position: 0 0;
            }
        </style>
        ';
    }
add_filter('wp_before_admin_bar_render', 'logo_custom');


// Ajout du nom de l'autheur du theme dans le footer du dashboard
function change_wp_dashboard_footer() {
        $auteur = wp_get_theme()->display('Author', FALSE);
        $text = '<p>Theme par ' . $auteur . '</p>' ;
            return $text;
    } 
add_filter('admin_footer_text', 'change_wp_dashboard_footer');


/**
 * 
 *  UTILITAIRES
 * 
 */

 // Ajout du shotcode [sitename]
add_shortcode( 'sitename', function() { return get_bloginfo($show = 'name'); } );

// Ajout du shotcode [slogan]
add_shortcode( 'slogan', function() { return get_bloginfo($show = 'description'); } );

// Remplace le badge "PROMO!" par "En vente"
function wc_custom_replace_sale_text( $html ) {
    return str_replace( __( 'Sale!', 'woocommerce' ), __( 'En vente', 'woocommerce' ), $html );
}
add_filter( 'woocommerce_sale_flash', 'wc_custom_replace_sale_text' );

// Ajoute une nom de pag
function shop_top_banniere() {
    if ( is_woocommerce() ) {
        echo '
            <div class="page-sous-header">
                <span>Boutique</span>
            </div>
        ';
        
    }
}
add_action('storefront_content_top', 'shop_top_banniere');