<?php
/**
 *
 * The framework's functions and definitions
 *
 */

/**
 * ------------------------------------------------------------------------------------------------
 * Define constants.
 * ------------------------------------------------------------------------------------------------
 */

define( 'WOODMART_THEME_DIR', 		get_template_directory_uri() );
define( 'WOODMART_THEMEROOT', 		get_template_directory() );
define( 'WOODMART_IMAGES', 			WOODMART_THEME_DIR . '/images' );
define( 'WOODMART_SCRIPTS', 		WOODMART_THEME_DIR . '/js' );
define( 'WOODMART_STYLES', 			WOODMART_THEME_DIR . '/css' );
define( 'WOODMART_FRAMEWORK', 		'/inc' );
define( 'WOODMART_DUMMY', 			WOODMART_THEME_DIR . '/inc/dummy-content' );
define( 'WOODMART_CLASSES', 		WOODMART_THEMEROOT . '/inc/classes' );
define( 'WOODMART_CONFIGS', 		WOODMART_THEMEROOT . '/inc/configs' );
define( 'WOODMART_HEADER_BUILDER',  WOODMART_THEME_DIR . '/inc/header-builder' );
define( 'WOODMART_ASSETS', 			WOODMART_THEME_DIR . '/inc/admin/assets' );
define( 'WOODMART_ASSETS_IMAGES', 	WOODMART_ASSETS    . '/images' );
define( 'WOODMART_API_URL', 		'https://xtemos.com/licenses/api/' );
define( 'WOODMART_DEMO_URL', 		'https://woodmart.xtemos.com/' );
define( 'WOODMART_PLUGINS_URL', 	WOODMART_DEMO_URL . 'plugins/');
define( 'WOODMART_DUMMY_URL', 		WOODMART_DEMO_URL . 'dummy-content/');
define( 'WOODMART_SLUG', 			'woodmart' );
define( 'WOODMART_CORE_VERSION', 	'1.0.22' );
define( 'WOODMART_WPB_CSS_VERSION', '1.0.1' );


/**
 * ------------------------------------------------------------------------------------------------
 * Load all CORE Classes and files
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'woodmart_load_classes' ) ) {
    function woodmart_load_classes() {
    	$classes = array(
		    'Singleton.php',
		    'Ajaxresponse.php',
		    'Api.php',
		    'Cssgenerator.php',
		    'Googlefonts.php',
		    'Import.php',
		    'Importversion.php',
		    'Layout.php',
		    'License.php',
		    'Notices.php',
		    'Options.php',
		    'Stylesstorege.php',
		    'Theme.php',
		    'Themesettingscss.php',
		    'Vctemplates.php',
		    'Wpbcssgenerator.php',
		    'Registry.php',
	    );
	
	    foreach ( $classes as $class ) {
		    $file_name = WOODMART_CLASSES . DIRECTORY_SEPARATOR . $class;
		    if ( file_exists( $file_name ) ) {
			    require $file_name;
		    }
    	}
    }
}

woodmart_load_classes();

$woodmart_theme = new WOODMART_Theme();

/**
 * ------------------------------------------------------------------------------------------------
 * Enqueue styles
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'woodmart_enqueue_styles' ) ) {
	add_action( 'wp_enqueue_scripts', 'woodmart_enqueue_styles', 10000 );

	function woodmart_enqueue_styles() {
		$version = woodmart_get_theme_info( 'Version' );
		$minified = woodmart_get_opt( 'minified_css' ) ? '.min' : '';
		$is_rtl = is_rtl() ? '-rtl' : '';
		$style_url = WOODMART_THEME_DIR . '/style' . $minified . '.css';
		if ( woodmart_woocommerce_installed() && is_rtl() ) {
			$style_url = WOODMART_STYLES . '/style-rtl' . $minified . '.css';
		} elseif ( ! woodmart_woocommerce_installed() ) {
			$style_url = WOODMART_STYLES . '/base' . $is_rtl . $minified . '.css';
		}

		// Custom CSS generated from the dashboard.
		
		$file = get_option('woodmart-generated-css-file');
		$file_data = isset( $file['file'] ) ? get_file_data( $file['file'], array( 'Version' => 'Version' ) ) : array();
		$file_version = isset( $file_data['Version'] ) ? $file_data['Version'] : '';
		if( ! empty( $file ) && ! empty( $file['url'] ) && version_compare( $version, $file_version, '==' ) ) {
			$style_url = $file['url'];
		}
		
		if ( class_exists( 'WeDevs_Dokan' ) )  {
			wp_deregister_style( 'dokan-fontawesome' );
			wp_dequeue_style( 'dokan-fontawesome' );
			
			wp_enqueue_style( 'vc_font_awesome_5' );
			wp_enqueue_style( 'vc_font_awesome_5_shims' );
		}

		wp_deregister_style( 'font-awesome' );
		wp_dequeue_style( 'font-awesome' );

		wp_dequeue_style( 'vc_pageable_owl-carousel-css' );
		wp_dequeue_style( 'vc_pageable_owl-carousel-css-theme' );
		
		wp_deregister_style( 'woocommerce_prettyPhoto_css' );
		wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
		
		wp_deregister_style( 'contact-form-7' );
		wp_dequeue_style( 'contact-form-7' );
		wp_deregister_style( 'contact-form-7-rtl' );
		wp_dequeue_style( 'contact-form-7-rtl' );

		$wpbfile = get_option('woodmart-generated-wpbcss-file');
		$wpbfile_data = isset( $wpbfile['file'] ) ? get_file_data( $wpbfile['file'], array( 'Version' => 'Version' ) ) : array();
		$wpbfile_version = isset( $wpbfile_data['Version'] ) ? $wpbfile_data['Version'] : '';
		if( ! empty( $wpbfile ) && ! empty( $wpbfile['url'] ) && version_compare( WOODMART_WPB_CSS_VERSION, $wpbfile_version, '==' ) ) {
			$wpbcssfile_url = $wpbfile['url'];

			$inline_styles = wp_styles()->get_data( 'js_composer_front', 'after' );

			wp_deregister_style( 'js_composer_front' );
			wp_dequeue_style( 'js_composer_front' );
			wp_register_style( 'js_composer_front', $wpbcssfile_url, array(), $version );
			if ( ! empty( $inline_styles ) ) {
				$inline_styles = implode( "\n", $inline_styles );
				wp_add_inline_style( 'js_composer_front', $inline_styles );
			}
		}

		wp_enqueue_style( 'js_composer_front', false, array(), $version );
		
		if ( 'always' === woodmart_get_opt( 'font_awesome_css' ) ) {
			wp_enqueue_style( 'vc_font_awesome_5' );
			wp_enqueue_style( 'vc_font_awesome_5_shims' );
		} elseif ( 'light' === woodmart_get_opt( 'font_awesome_css' ) ) {
			if ( ! wp_style_is( 'vc_font_awesome_5' ) && ! wp_style_is( 'vc_font_awesome_5' ) ) {
				wp_enqueue_style( 'font-awesome-css', WOODMART_STYLES . '/font-awesome-light.min.css', array(), $version );
			}
		}

		if ( woodmart_get_opt( 'light_bootstrap_version' ) ) {
			wp_enqueue_style( 'bootstrap', WOODMART_STYLES . '/bootstrap-light.min.css', array(), $version );
		} else {
			wp_enqueue_style( 'bootstrap', WOODMART_STYLES . '/bootstrap.min.css', array(), $version );
		}
		
		if ( woodmart_get_opt( 'disable_gutenberg_css' ) ) {
			wp_deregister_style( 'wp-block-library' );
			wp_dequeue_style( 'wp-block-library' );
			
			wp_deregister_style( 'wc-block-style' );
			wp_dequeue_style( 'wc-block-style' );
		}
		
		wp_enqueue_style( 'woodmart-style', $style_url, array( 'bootstrap' ), $version );

		// load typekit fonts
		$typekit_id = woodmart_get_opt( 'typekit_id' );

		if ( $typekit_id ) {
			wp_enqueue_style( 'woodmart-typekit', 'https://use.typekit.net/' . esc_attr ( $typekit_id ) . '.css', array(), $version );
		}

		remove_action('wp_head', 'print_emoji_detection_script', 7);
		remove_action('wp_print_styles', 'print_emoji_styles');

		wp_register_style( 'woodmart-inline-css', false );
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Enqueue scripts
 * ------------------------------------------------------------------------------------------------
 */
 
if( ! function_exists( 'woodmart_enqueue_scripts' ) ) {
	add_action( 'wp_enqueue_scripts', 'woodmart_enqueue_scripts', 10000 );

	function woodmart_enqueue_scripts() {
		
		$version = woodmart_get_theme_info( 'Version' );
		/*
		 * Adds JavaScript to pages with the comment form to support
		 * sites with threaded comments (when in use).
		 */
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply', false, array(), $version );
		}
		if( ! woodmart_woocommerce_installed() ) {
			wp_register_script( 'js-cookie', woodmart_get_script_url( 'js.cookie' ), array( 'jquery' ), $version, true );
		}

		wp_dequeue_script( 'flexslider' );
		wp_dequeue_script( 'photoswipe-ui-default' );
		wp_dequeue_script( 'prettyPhoto-init' );
		wp_dequeue_script( 'prettyPhoto' );
		wp_dequeue_style( 'photoswipe-default-skin' );
		if( woodmart_get_opt( 'image_action' ) != 'zoom' ) {
			wp_dequeue_script( 'zoom' );
		}

		wp_enqueue_script( 'wpb_composer_front_js', false, array(), $version );
		wp_enqueue_script( 'imagesloaded', false, array(), $version );

		if( woodmart_get_opt( 'combined_js' ) ) {
		    wp_enqueue_script( 'isotope', woodmart_get_script_url( 'isotope.pkgd' ), array(), $version, true );
		    wp_enqueue_script( 'woodmart-theme', WOODMART_SCRIPTS . '/theme.min.js', array( 'jquery', 'js-cookie' ), $version, true );
		} else {
			wp_enqueue_script( 'woodmart-owl-carousel', woodmart_get_script_url( 'owl.carousel' ), array(), $version, true );
			wp_enqueue_script( 'woodmart-tooltips', woodmart_get_script_url( 'jquery.tooltips' ), array(), $version, true );
			wp_enqueue_script( 'woodmart-magnific-popup', woodmart_get_script_url( 'jquery.magnific-popup' ), array(), $version, true );
			wp_enqueue_script( 'woodmart-device', woodmart_get_script_url( 'device' ), array( 'jquery' ), $version, true );
			wp_enqueue_script( 'woodmart-waypoints', woodmart_get_script_url( 'waypoints' ), array( 'jquery' ), $version, true );

			if ( woodmart_get_opt( 'disable_nanoscroller' ) != 'disable' ) {
				wp_enqueue_script( 'woodmart-nanoscroller', woodmart_get_script_url( 'jquery.nanoscroller' ), array(), $version, true );
			}

			$minified = woodmart_get_opt( 'minified_js' ) ? '.min' : '';
			$base = ! woodmart_woocommerce_installed() ? '-base' : '';
			wp_enqueue_script( 'woodmart-theme', WOODMART_SCRIPTS . '/functions' . $base . $minified . '.js', array( 'js-cookie' ), $version, true );
			if ( woodmart_get_opt( 'ajax_shop' ) && woodmart_woocommerce_installed() && ( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() ) ) {
				wp_enqueue_script( 'woodmart-pjax', woodmart_get_script_url( 'jquery.pjax' ), array(), $version, true );
			}
		}
 		wp_add_inline_script( 'woodmart-theme', woodmart_settings_js(), 'after' );
		
		wp_register_script( 'woodmart-panr-parallax', woodmart_get_script_url( 'panr-parallax' ), array(), $version, true );
		wp_register_script( 'woodmart-photoswipe', woodmart_get_script_url( 'photoswipe-bundle' ), array(), $version, true );
		wp_register_script( 'woodmart-slick', woodmart_get_script_url( 'slick' ), array(), $version, true );
		wp_register_script( 'woodmart-countdown', woodmart_get_script_url( 'countdown' ), array(), $version, true );
		wp_register_script( 'woodmart-packery-mode', woodmart_get_script_url( 'packery-mode.pkgd' ), array(), $version, true );
		wp_register_script( 'woodmart-vivus', woodmart_get_script_url( 'vivus' ), array(), $version, true );
		wp_register_script( 'woodmart-threesixty', woodmart_get_script_url( 'threesixty' ), array(), $version, true );
		wp_register_script( 'woodmart-justifiedGallery', woodmart_get_script_url( 'jquery.justifiedGallery' ), array(), $version, true );
		wp_register_script( 'woodmart-autocomplete', woodmart_get_script_url( 'jquery.autocomplete' ), array(), $version, true );
		wp_register_script( 'woodmart-sticky-kit', woodmart_get_script_url( 'jquery.sticky-kit' ), array(), $version, true );
		wp_register_script( 'woodmart-parallax', woodmart_get_script_url( 'jquery.parallax' ), array(), $version, true );
		wp_register_script( 'woodmart-parallax-scroll', woodmart_get_script_url( 'parallax-scroll' ), array(), $version, true );
			wp_register_script( 'maplace', woodmart_get_script_url( 'maplace-0.1.3' ), array( 'google.map.api' ), $version, true );
		wp_register_script( 'isotope', woodmart_get_script_url( 'isotope.pkgd' ), array(), $version, true );

		if ( woodmart_woocommerce_installed() ) {
			wp_register_script( 'accounting', WC()->plugin_url() . '/assets/js/accounting/accounting.min.js', array( 'jquery' ), $version, true );
			wp_register_script( 'wc-jquery-ui-touchpunch', WC()->plugin_url() . '/assets/js/jquery-ui-touch-punch/jquery-ui-touch-punch.min.js', array( 'jquery-ui-slider' ), $version, true );
		}
	
		// Add virations form scripts through the site to make it work on quick view
		if( woodmart_get_opt( 'quick_view_variable' ) || woodmart_get_opt( 'quick_shop_variable' ) ) {
			wp_enqueue_script( 'wc-add-to-cart-variation', false, array(), $version );
		}
		
		$translations = array(
			'adding_to_cart' => esc_html__('Processing', 'woodmart'),
			'added_to_cart' => esc_html__('Product was successfully added to your cart.', 'woodmart'),
			'continue_shopping' => esc_html__('Continue shopping', 'woodmart'),
			'view_cart' => esc_html__('View Cart', 'woodmart'),
			'go_to_checkout' => esc_html__('Checkout', 'woodmart'),
			'loading' => esc_html__('Loading...', 'woodmart'),
			'countdown_days' => esc_html__('days', 'woodmart'),
			'countdown_hours' => esc_html__('hr', 'woodmart'),
			'countdown_mins' => esc_html__('min', 'woodmart'),
			'countdown_sec' => esc_html__('sc', 'woodmart'),
			'cart_url' => ( woodmart_woocommerce_installed() ) ?  esc_url( wc_get_cart_url() ) : '',
			'ajaxurl' => admin_url('admin-ajax.php'),
			'add_to_cart_action' => ( woodmart_get_opt( 'add_to_cart_action' ) ) ? esc_js( woodmart_get_opt( 'add_to_cart_action' ) ) : 'widget',
			'added_popup' => ( woodmart_get_opt( 'added_to_cart_popup' ) ) ? 'yes' : 'no',
			'categories_toggle' => ( woodmart_get_opt( 'categories_toggle' ) ) ? 'yes' : 'no',
			'enable_popup' => ( woodmart_get_opt( 'promo_popup' ) ) ? 'yes' : 'no',
			'popup_delay' => ( woodmart_get_opt( 'promo_timeout' ) ) ? (int) woodmart_get_opt( 'promo_timeout' ) : 1000,
			'popup_event' => woodmart_get_opt( 'popup_event' ),
			'popup_scroll' => ( woodmart_get_opt( 'popup_scroll' ) ) ? (int) woodmart_get_opt( 'popup_scroll' ) : 1000,
			'popup_pages' => ( woodmart_get_opt( 'popup_pages' ) ) ? (int) woodmart_get_opt( 'popup_pages' ) : 0,
			'promo_popup_hide_mobile' => ( woodmart_get_opt( 'promo_popup_hide_mobile' ) ) ? 'yes' : 'no',
			'product_images_captions' => ( woodmart_get_opt( 'product_images_captions' ) ) ? 'yes' : 'no',
			'ajax_add_to_cart' => ( apply_filters( 'woodmart_ajax_add_to_cart', true ) ) ? woodmart_get_opt( 'single_ajax_add_to_cart' ) : false,
			'all_results' => esc_html__('View all results', 'woodmart'),
			'product_gallery' => woodmart_get_product_gallery_settings(),
			'zoom_enable' => ( woodmart_get_opt( 'image_action' ) == 'zoom') ? 'yes' : 'no',
			'ajax_scroll' => ( woodmart_get_opt( 'ajax_scroll' ) ) ? 'yes' : 'no',
			'ajax_scroll_class' => apply_filters( 'woodmart_ajax_scroll_class' , '.main-page-wrapper' ),
			'ajax_scroll_offset' => apply_filters( 'woodmart_ajax_scroll_offset' , 100 ),
			'infinit_scroll_offset' => apply_filters( 'woodmart_infinit_scroll_offset' , 300 ),
			'product_slider_auto_height' => ( woodmart_get_opt( 'product_slider_auto_height' ) ) ? 'yes' : 'no',
			'price_filter_action' => ( apply_filters( 'price_filter_action' , 'click' ) == 'submit' ) ? 'submit' : 'click',
			'product_slider_autoplay' => apply_filters( 'woodmart_product_slider_autoplay' , false ),
			'close' => esc_html__( 'Close (Esc)', 'woodmart' ),
			'share_fb' => esc_html__( 'Share on Facebook', 'woodmart' ),
			'pin_it' => esc_html__( 'Pin it', 'woodmart' ),
			'tweet' => esc_html__( 'Tweet', 'woodmart' ),
			'download_image' => esc_html__( 'Download image', 'woodmart' ),
			'cookies_version' => ( woodmart_get_opt( 'cookies_version' ) ) ? (int)woodmart_get_opt( 'cookies_version' ) : 1,
			'header_banner_version' => ( woodmart_get_opt( 'header_banner_version' ) ) ? (int)woodmart_get_opt( 'header_banner_version' ) : 1,
			'promo_version' => ( woodmart_get_opt( 'promo_version' ) ) ? (int)woodmart_get_opt( 'promo_version' ) : 1,
			'header_banner_close_btn' => woodmart_get_opt( 'header_close_btn' ),
			'header_banner_enabled' => woodmart_get_opt( 'header_banner' ),
			'whb_header_clone' => woodmart_get_config( 'header-clone-structure' ),
			'pjax_timeout' => apply_filters( 'woodmart_pjax_timeout' , 5000 ),
			'split_nav_fix' => apply_filters( 'woodmart_split_nav_fix' , false ),
			'shop_filters_close' => woodmart_get_opt( 'shop_filters_close' ) ? 'yes' : 'no',
			'woo_installed' => woodmart_woocommerce_installed(),
			'base_hover_mobile_click' => woodmart_get_opt( 'base_hover_mobile_click' ) ? 'yes' : 'no',
			'centered_gallery_start' => apply_filters( 'woodmart_centered_gallery_start' , 1 ),
			'quickview_in_popup_fix' => apply_filters( 'woodmart_quickview_in_popup_fix', false ),
			'disable_nanoscroller' => woodmart_get_opt( 'disable_nanoscroller' ),
			'one_page_menu_offset' => apply_filters( 'woodmart_one_page_menu_offset', 150 ),
			'hover_width_small' => apply_filters( 'woodmart_hover_width_small', true ),
			'is_multisite' => is_multisite(),
			'current_blog_id' => get_current_blog_id(),
			'swatches_scroll_top_desktop' => woodmart_get_opt( 'swatches_scroll_top_desktop' ),
			'swatches_scroll_top_mobile' => woodmart_get_opt( 'swatches_scroll_top_mobile' ),
			'lazy_loading_offset' => woodmart_get_opt( 'lazy_loading_offset' ),
			'add_to_cart_action_timeout' => woodmart_get_opt( 'add_to_cart_action_timeout' ) ? 'yes' : 'no',
			'add_to_cart_action_timeout_number' => woodmart_get_opt( 'add_to_cart_action_timeout_number' ),
			'single_product_variations_price' => woodmart_get_opt( 'single_product_variations_price' ) ? 'yes' : 'no',
			'google_map_style_text' => esc_html__( 'Custom style', 'woodmart' ),
			'quick_shop' => woodmart_get_opt( 'quick_shop_variable' ) ? 'yes' : 'no',
			'sticky_product_details_offset' => apply_filters( 'woodmart_sticky_product_details_offset', 150 ),
			'preloader_delay' => apply_filters( 'woodmart_preloader_delay', 300 ),
			'comment_images_upload_size_text'             => sprintf( esc_html__( 'Some files are too large. Allowed file size is %s.', 'woodmart' ), size_format( woodmart_get_opt( 'single_product_comment_images_upload_size' ) * MB_IN_BYTES ) ), // phpcs:ignore
			'comment_images_count_text'                   => sprintf( esc_html__( 'You can upload up to %s images to your review.', 'woodmart' ), woodmart_get_opt( 'single_product_comment_images_count' ) ), // phpcs:ignore
			'comment_images_upload_mimes_text'            => sprintf( esc_html__( 'You are allowed to upload images only in %s formats.', 'woodmart' ), apply_filters( 'xts_comment_images_upload_mimes', 'png, jpeg' ) ), // phpcs:ignore
			'comment_images_added_count_text'             => esc_html__( 'Added %s image(s)', 'woodmart' ), // phpcs:ignore
			'comment_images_upload_size'                  => woodmart_get_opt( 'single_product_comment_images_upload_size' ) * MB_IN_BYTES,
			'comment_images_count'                        => woodmart_get_opt( 'single_product_comment_images_count' ),
			'comment_images_upload_mimes'                 => apply_filters(
				'woodmart_comment_images_upload_mimes',
				array(
					'jpg|jpeg|jpe' => 'image/jpeg',
					'png'          => 'image/png',
				)
			),
			'home_url' => home_url( '/' ),
			'shop_url' => woodmart_woocommerce_installed() ? esc_url( wc_get_page_permalink( 'shop' ) ) : '',
			'age_verify' => ( woodmart_get_opt( 'age_verify' ) ) ? 'yes' : 'no',
			'age_verify_expires' => apply_filters( 'woodmart_age_verify_expires', 30 ),
		);
		
		wp_localize_script( 'woodmart-functions', 'woodmart_settings', $translations );
		wp_localize_script( 'woodmart-theme', 'woodmart_settings', $translations );

	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Get script URL
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'woodmart_get_script_url') ) {
	function woodmart_get_script_url( $script_name ) {
	    return WOODMART_SCRIPTS . '/' . $script_name . '.min.js';
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Enqueue style for inline css
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'woodmart_enqueue_inline_style_anchor' ) ) {
	function woodmart_enqueue_inline_style_anchor() {
		wp_enqueue_style( 'woodmart-inline-css' );
	}
	
	add_action( 'wp_footer', 'woodmart_enqueue_inline_style_anchor', 10 );
}

/**
* CUSTOMIZACOES NO TEMA
**/

/**
 * ------------------------------------------------------------------------------------------------
 * Setting Brasil as default billing_country
 * ------------------------------------------------------------------------------------------------
 */

add_filter( 'default_checkout_billing_country', 'jscorp_change_default_checkout_country' );
 
function jscorp_change_default_checkout_country() {
  return 'BR'; 
}


/**
 * ------------------------------------------------------------------------------------------------
 * Adicionando botão de 2a via de boleto na pagina de thank you
 * ------------------------------------------------------------------------------------------------
 */

add_action( 'woocommerce_thankyou', 'jscorp_add_content', 1 );
 
function jscorp_add_content($orderid) {

   $order = new WC_Order($orderid);
   //var_dump($order);
   
   //$meta_data = $order->get_data();
   //var_dump($meta_data);
   
   $meta_data = $order->get_meta_data();
   //print_r($meta_data);   
   
   $tipo_pagamento = "";
   $url_pagamento  = "";
   
   foreach($meta_data as $data){
           //var_dump($data);
           //echo $data->key."</br>";
           switch ($data->key){
           
                   default:
                   break;
                   
                   case "Tipo de pagamento": $tipo_pagamento = $data->value; break;
                   case "URL de pagamento.": $url_pagamento  = $data->value; break;                   
           
           }
   }
   
   if (strtoupper($tipo_pagamento) == "BOLETO"){
   
            echo 
            '<div>
                <a href="'.$url_pagamento.'" target="_blank">
		           <input type="button" value="PAGAR BOLETO BANC&Aacute;RIO" style="color: rgb(255, 255, 255) !important; background-color: rgb(147, 40, 138) !important">
		        </a>
		    </div>';
   
	  
   }   

}

/**
 * ------------------------------------------------------------------------------------------------
 * Adicionando um espaco apos o termos e condicoes na pagina de checkout
 * ------------------------------------------------------------------------------------------------
 */

add_action( 'woocommerce_after_checkout_form', 'jscorp_add_space');
 
function jscorp_add_space() {
   
	echo '</br>';

}

/**
 * ------------------------------------------------------------------------------------------------
 * Plugin Multi-step checkout - setando apenas 2 passos
 * ------------------------------------------------------------------------------------------------
 */
 
/* 
add_filter('wpmc_modify_steps', function( $steps ) {
    $steps['billing']['sections'] = array('billing', 'shipping', 'review', 'payment' );
    $steps['billing']['title'] = __( 'pedido' );
    unset($steps['payment']);
    unset($steps['review']);

    return $steps;
});
*/


/**
 * ------------------------------------------------------------------------------------------------
 * Esconde outros tipos de metodo de entrega, se o frenet estiver disponivel
 * ------------------------------------------------------------------------------------------------
 */

function my_hide_shipping_when_frenet_is_available( $rates ) {
	$free = array();
	foreach ( $rates as $rate_id => $rate ) {
		if (  'frenet' === $rate->method_id ) {
			$free[ $rate_id ] = $rate;
			break;
		}
	}
	return ! empty( $free ) ? $free : $rates;
}
add_filter( 'woocommerce_package_rates', 'my_hide_shipping_when_frenet_is_available', 100 );


add_filter( 'woocommerce_defer_transactional_emails', '__return_true' );


/**
 * @snippet       Remove Cart Item Programmatically - WooCommerce
 * @author        Jansen Schemidt
 * @compatible    WooCommerce 3.8
 */
 
add_action( 'template_redirect', 'remove_product_from_cart' );
function remove_product_from_cart() {
    // Run only in the Cart or Checkout Page
    if( is_cart() || is_checkout() ) {
        // Set the product ID to remove
        $prod_to_remove_limite1 = 24121;
        $prod_to_remove_limite2 = 74723;
        $prod_to_remove_limite3 = 74739;                
		$limite1 = 300.00;
		$limite2 = 600.00;		
		$limite3 = 1000.00;		

        $total_carrinho = WC()->cart->subtotal_ex_tax;
		$a_descontar = 0.00;        

        foreach( WC()->cart->cart_contents as $prod_in_cart ) {
        
            $prod_id = ( isset( $prod_in_cart['variation_id'] ) && $prod_in_cart['variation_id'] != 0 ) ? $prod_in_cart['variation_id'] : $prod_in_cart['product_id'];

            if( $prod_to_remove_limite1 == $prod_id ) $a_descontar += round($prod_in_cart['data']->get_price() / $prod_in_cart['quantity'],2);
	        if( $prod_to_remove_limite2 == $prod_id ) $a_descontar += round($prod_in_cart['data']->get_price() / $prod_in_cart['quantity'],2);
            if( $prod_to_remove_limite3 == $prod_id ) $a_descontar += round($prod_in_cart['data']->get_price() / $prod_in_cart['quantity'],2);
            
            if (!in_array($prod_id,array($prod_to_remove_limite1,$prod_to_remove_limite2,$prod_to_remove_limite3)))
				if ( !has_term( 'progressivo', 'product_cat', $prod_in_cart['data']->id ) ) {
					$total_carrinho -= round($prod_in_cart['data']->get_price() / $prod_in_cart['quantity'],2);
				}                    

        }
        
        $total_carrinho -= $a_descontar;
        
        foreach( WC()->cart->cart_contents as $prod_in_cart ) {
        
            $prod_id = ( isset( $prod_in_cart['variation_id'] ) && $prod_in_cart['variation_id'] != 0 ) ? $prod_in_cart['variation_id'] : $prod_in_cart['product_id'];
            
            #echo "=> $prod_id | $total_carrinho | ".$prod_in_cart['data']->get_price()." </br>";            
                        
            if( $prod_to_remove_limite1 == $prod_id ){    
				if(( $total_carrinho < $limite1 ) or ( $total_carrinho >= $limite2 )){
					$prod_unique_id = WC()->cart->generate_cart_id( $prod_id );
					WC()->cart->remove_cart_item($prod_unique_id);					
					unset( WC()->cart->cart_contents[$prod_unique_id] );
				}	                
			}

            if( $prod_to_remove_limite2 == $prod_id ){    			
				if(( $total_carrinho < $limite2 ) or ( $total_carrinho >= $limite3 )){            
					$prod_unique_id = WC()->cart->generate_cart_id( $prod_id );
					WC()->cart->remove_cart_item($prod_unique_id);
					unset( WC()->cart->cart_contents[$prod_unique_id] );
				}        
			}
			
            if( $prod_to_remove_limite3 == $prod_id ){    			
				if( $total_carrinho < $limite3 ){            
					$prod_unique_id = WC()->cart->generate_cart_id( $prod_id );
					WC()->cart->remove_cart_item($prod_unique_id);					
					unset( WC()->cart->cart_contents[$prod_unique_id] );
				}	
			}
			
	   }		

    }
}


/**
 * Adds a custom message about how long will take to delivery.
 */
function my_wc_custom_cart_shipping_notice() {
	echo '<tr class="shipping-notice"><td colspan="2"><small>';
	_e( '<label style="color:#820118!important;text-align:left"><strong>Atenção:</strong> O prazo de entrega é de 2 dias após a aprovação do pagamento.</lable>', 'my-text-domain' );
	echo '</small></td></tr>';
}

add_action( 'woocommerce_cart_totals_after_shipping', 'my_wc_custom_cart_shipping_notice' );
add_action( 'woocommerce_review_order_after_shipping', 'my_wc_custom_cart_shipping_notice' );

/**
 * Adds a custom message about how long will take to delivery in emails.
 *
 * @param  WC_Order $order         Order data.
 * @param  bool     $sent_to_admin True if is an admin email.
 */
function my_wc_custom_email_shipping_notice( $order, $sent_to_admin ) {
	if ( $sent_to_admin ) {
		return;
	}

	_e( '<label style="color:#820118!important;text-align:left"><strong>Atenção:</strong> O prazo de entrega é de 2 dias após a aprovação do pagamento.</label>', 'my-text-domain' );
}

add_action( 'woocommerce_email_after_order_table', 'my_wc_custom_email_shipping_notice', 100, 2 );


