<?php
define('CACTUS_VERSION','1.2.0');
define('CACTUS_TEXTDOMAIN','cactus');

if( !function_exists('is_plugin_active') ) {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}


	
$template_directory = trailingslashit( get_template_directory() );

require_once($template_directory . '/inc/plugin-install/class-plugin-install-helper.php');
// Helper library for the theme customizer.
require_once($template_directory . '/inc/customizer-library/customizer-library.php');
// Define options for the theme customizer.
require_once($template_directory . '/inc/customizer-options.php');
require_once($template_directory . '/inc/class-video.php');
require_once($template_directory . '/inc/demo-preview-images/init-prevdem.php');
require_once($template_directory . '/inc/customizer-library/custom-controls/editor/editor-page.php');
require_once ($template_directory . '/inc/wp-menu-item-custom-fields/Menu.php');

function cactus_setup() {

	load_theme_textdomain( 'cactus', get_template_directory() . '/languages' );

	add_theme_support( 'automatic-feed-links' );

	add_theme_support( 'title-tag' );

	add_theme_support( 'post-thumbnails' );

	add_image_size( 'cactus-featured-image', 960, 720, true );
	
	add_image_size( 'cactus-widget-post-image', 480, 360, true );

	// Set the default content width.
	$GLOBALS['content_width'] = 850;


	register_nav_menus( array(
		'top'    => __( 'Top Menu', 'cactus' ),
		'top-left'    => __( 'Top Left Menu (Split Navigation Bar)', 'cactus' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );


	// Add theme support for Custom Logo.
	add_theme_support( 'custom-logo', array(
		'height'      => 100,
		'flex-width'  => true,
		'flex-height' => true,
	) );
	
	// Setup the WordPress core custom header feature.
	add_theme_support( 'custom-header', array(
		'default-image'          => '',
		'random-default'         => false,
		'width'                  => '1920',
		'height'                 => '70',
		'flex-height'            => true,
		'flex-width'             => true,
		'default-text-color'     => '#333333',
		'header-text'            => true,
		'uploads'                => true,
		'wp-head-callback'       => '',
		'admin-head-callback'    => '',
		'admin-preview-callback' => ''
	)); 

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background',  array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );
	
	// Woocommerce Support
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
	
	// Add Works Image size
	add_image_size( 'cactus-works', 480, 360, true );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, and column width.
 	 */
	add_editor_style( array( 'assets/css/editor-style.css' ) );
	
	
}
add_action( 'after_setup_theme', 'cactus_setup' );

/**
 * After switch theme
 */
function cactus_after_switch_theme(){
	delete_option('cactus_welcome_notice');
}
add_action('after_switch_theme', 'cactus_after_switch_theme');

/**
 * Enqueue scripts and styles.
 */
function cactus_scripts() {
	
	global $cactus_options, $cactus_sections;
	
	$cactus_options = get_option(CACTUS_TEXTDOMAIN);
	
	$headings_font_family = esc_attr(cactus_option( 'headings_font_family'));
	$body_font_family     = esc_attr(cactus_option( 'body_font_family'));
	$page_preloader       = absint(cactus_option( 'page_preloader'));
	$fonts[] = $headings_font_family;
	$fonts[] = $body_font_family;
	
	
	wp_enqueue_style( 'cactus-google-fonts', customizer_library_get_google_font_uri($fonts), false, '', false );
	wp_enqueue_style( 'bootstrap',  get_template_directory_uri() .'/assets/plugins/bootstrap/css/bootstrap.css', false, '', false );
	wp_enqueue_style( 'font-awesome',  get_template_directory_uri() .'/assets/plugins/font-awesome/css/font-awesome.min.css', false, '', false );
	wp_enqueue_style( 'owl-carousel',  get_template_directory_uri() .'/assets/plugins/owl-carousel/css/owl.carousel.css', false, '', false );
	wp_enqueue_style( 'prettyphoto',  get_template_directory_uri() .'/assets/plugins/prettyphoto/css/prettyPhoto.min.css', false, '', false );
	
	// Theme stylesheet.
	wp_enqueue_style( 'cactus-style', get_stylesheet_uri() );
	if(is_page_template( 'template-sections.php' )){
		wp_enqueue_style( 'cactus-frontpage', get_template_directory_uri() .'/assets/css/frontpage.css' );
		wp_enqueue_style( 'animate', get_template_directory_uri() .'/assets/plugins/animate.css' );
	}
	
	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/assets/plugins/bootstrap/js/bootstrap.min.js' , array( 'jquery' ), null, true);
	
	wp_enqueue_script( 'respond', get_template_directory_uri() . '/assets/plugins/respond.min.js' , array( 'jquery' ), null, true);
	wp_enqueue_script( 'owl-carousel', get_template_directory_uri() . '/assets/plugins/owl-carousel/js/owl.carousel.min.js' , array( 'jquery' ), null, true);
	wp_enqueue_script( 'jquery-waypoints', get_template_directory_uri() . '/assets/plugins/waypoints/jquery.waypoints.min.js' , array( 'jquery' ), null, true);
	
	if(cactus_option('section_hide_counter')!='1'&&cactus_option('section_hide_counter')!='on')
		wp_enqueue_script( 'jquery-counterup', get_template_directory_uri() . '/assets/plugins/counter-up/jquery.counterup.js' , array( 'jquery' ), null, true);
	
	$mixitup = 0;
	if(cactus_option('section_hide_works')!='1' || is_customize_preview() ){
		$mixitup = 1;
		wp_enqueue_script( 'mixitup', get_template_directory_uri() . '/assets/plugins/mixitup/mixitup.min.js' , array( 'jquery' ), null, true);
	}
	
	$isotope = 0;
	if( (cactus_option('blog_list_style')=='3'&& (is_archive() || is_home())) || is_customize_preview() ){
		$isotope = 1;
		wp_enqueue_script( 'imagesloaded');
		wp_enqueue_script( 'isotope', get_template_directory_uri() . '/assets/plugins/isotope/isotope.pkgd.js' , array( 'jquery' ), null, true			);
	}
	
	$video_background = 0;
	$banner_video = '';
	if((cactus_option('section_hide_banner')!='1' && cactus_option('type_banner')=='2' )|| is_customize_preview() ){
		if(cactus_option('type_banner')=='2')
			$video_background = 1;
		$banner_video = cactus_option('video_banner');

		wp_enqueue_style( 'jquery-mb-vimeo_player',  get_template_directory_uri() .'/assets/plugins/jquery.mb.vimeo_player/css/jquery.mb.vimeo_player.min.css', false, '', false );
		wp_enqueue_style( 'jquery-mb-ytplayer',  get_template_directory_uri() .'/assets/plugins/jquery.mb.YTPlayer/css/jquery.mb.YTPlayer.min.css', false, '', false );
		wp_enqueue_script( 'jquery-mb-vimeo_player', get_template_directory_uri() . '/assets/plugins/jquery.mb.vimeo_player/jquery.mb.vimeo_player.js' , array( 'jquery' ), null, true);
		wp_enqueue_script( 'jquery-mb-ytplayer', get_template_directory_uri() . '/assets/plugins/jquery.mb.YTPlayer/jquery.mb.YTPlayer.js' , array( 'jquery' ), null, true);
	}
	
	
	wp_enqueue_script( 'jquery-parallax', get_template_directory_uri() . '/assets/plugins/parallax/jquery.parallax-1.1.3.js' , array( 'jquery' ), null, true);
	wp_enqueue_script( 'jquery-prettyphoto', get_template_directory_uri() . '/assets/plugins/prettyphoto/js/jquery.prettyPhoto.min.js' , array( 'jquery' ), null, true);
	
	if( $page_preloader == '1' ||  is_customize_preview() )
		wp_enqueue_script( 'loadingoverlay', get_template_directory_uri() . '/assets/plugins/jquery-loading-overlay/loadingoverlay.js' , array( 'jquery' ), null, true);
	
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	
	if(cactus_option('slider_autoplay_banner')=='1')
		$slider_autoplay = true;
	else
		$slider_autoplay = false;
		
	$autoplaytimeout = cactus_option('autoplaytimeout_banner');
	$autoplaytimeout = absint($autoplaytimeout);
	
	$preloader_background = esc_attr(cactus_option('preloader_background'));
	$preloader_opacity = esc_attr(cactus_option('preloader_opacity'));
	$preloader_image = esc_attr(cactus_option('preloader_image'));
	if (is_numeric($preloader_image)) {
		$image_attributes = wp_get_attachment_image_src($preloader_image, 'full');
		$preloader_image   = $image_attributes[0];
	}
	
	$preloader_bg = '';
	if($preloader_background!=''){
		$rgb = cactus_hex2rgb( $preloader_background );
		$preloader_bg = "rgba(".$rgb[0].",".$rgb[1].",".$rgb[2].",".$preloader_opacity.")";
	}
	
	wp_enqueue_script( 'cactus-main', get_template_directory_uri() . '/assets/js/cactus.js' , array( 'jquery' ), null, true);
	wp_localize_script( 'cactus-main', 'cactus_params', array(
		'ajaxurl'  => admin_url('admin-ajax.php'),
		'themeurl' => get_template_directory_uri(),
		'mixitup' => $mixitup,
		'isotope' => $isotope,
		'video_background' => $video_background,
		'banner_video' => $banner_video,
		'slider_autoplay' => $slider_autoplay,
		'autoplaytimeout' => $autoplaytimeout,
		'page_preloader' => $page_preloader,
		'preloader_background' => $preloader_bg,
		'preloader_image' => $preloader_image,
	)  );
	
	$custom_css = '';
	$header_text_color = get_header_textcolor();

	if ( 'blank' != $header_text_color ) :

	$custom_css .= ".site-name,
		.site-tagline {
		color: ".esc_attr( $header_text_color )." !important;
	}.site-tagline {
			display: none;
		}\r\n";
	else:

	$custom_css .= ".site-name,
		.site-tagline {
			display: none;
		}\r\n";
		
	endif;
	
	// Font family
	$custom_css .=  "h1,h2,h3,h4,h5,h6{font-family:".$headings_font_family.";}";
	$custom_css .=  "html, div, span, applet, object, iframe, p, blockquote, pre, a, abbr, acronym, address, big, cite, code, del, dfn, em, font, ins, kbd, q, s, samp, small, strike, strong, sub, sup, tt, var, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td {font-family:".$body_font_family.";}";
	
	// Font size
	$body_font_size = absint(cactus_option('body_font_size'));
	$custom_css .=  "html, body, div{font-size:".$body_font_size."px;}";
	for($i=1;$i<=6;$i++){
		$heading_font_size = absint(cactus_option('h'.$i.'_font_size'));
		$custom_css .=  "h".$i."{font-size:".$heading_font_size."px;}";
		}
		
	// Section Font size
	$section_title_font_size = absint(cactus_option('section_title_font_size'));
	$custom_css .=  ".cactus-section .cactus-section-title{font-size:".$section_title_font_size."px;}";
	
	$section_subtitle_font_size = absint(cactus_option('section_subtitle_font_size'));
	$custom_css .=  ".cactus-section .cactus-section-desc{font-size:".$section_subtitle_font_size."px;}";
	
	$section_item_title_font_size = absint(cactus_option('section_item_title_font_size'));
	$custom_css .=  ".cactus-section h4{font-size:".$section_item_title_font_size."px;}";
	
	$section_content_font_size = absint(cactus_option('section_content_font_size'));
	$custom_css .=  ".cactus-section, .cactus-section p, .cactus-section div{font-size:".$section_content_font_size."px;}";
	
		
		

	$sticky_header_background_color   = cactus_option('sticky_header_background_color');
	$sticky_header_background_opacity = cactus_option('sticky_header_background_opacity');
	if( $sticky_header_background_color!='' ){
		$rgb = customizer_library_hex_to_rgb($sticky_header_background_color);
		$custom_css .=  "header .cactus-fixed-header-wrap,header .cactus-fixed-header-wrap .cactus-header{background-color:rgba(".$rgb['r'].",".$rgb['g'].",".$rgb['b'].",".$sticky_header_background_opacity.");}";
	}

	foreach($cactus_sections as $key=>$name ){
		
		$background_color = cactus_option('background_color_'.$key);
		$background_image = cactus_option('background_image_'.$key);
		$section_padding  = cactus_option('section_padding_'.$key);
		
		if (is_numeric($background_image)) {
			$image_attributes = wp_get_attachment_image_src($background_image, 'full');
			$background_image    = $image_attributes[0];
		  }
		  
		if($background_color!=''||$background_image!='')
		  $custom_css .= ".cactus-section-".$key." {
			  background-color:".sanitize_hex_color( $background_color ).";
			  background-image:url(".esc_url( $background_image ).");
		  }\r\n";
		if($section_padding!='')
		  $custom_css .= ".cactus-section-".$key." .cactus-section-content {
			  padding:".$section_padding.";
		  }\r\n";

		}
		$video_poster_banner = cactus_option('video_poster_banner');
		if($video_poster_banner)
			$custom_css .= ".banner_video_background{
			 	 background-image:url(".esc_url( $video_poster_banner ).");
		  	}\r\n";
			
	$primary_color = cactus_option('primary_color');
	if( $primary_color !='' ){
	
	 	$primary_color = sanitize_hex_color( $primary_color );
	
		$custom_css .= "a:hover,
				a:active {
					color: ".$primary_color.";
				}
				
				header a:hover {
					color: ".$primary_color.";
				}
				
				.site-nav  > div > ul > li.current > a {
					color: ".$primary_color.";
				}
				
				.blog-list-wrap .entry-category a {
					color: ".$primary_color.";
				}
				
				.entry-meta a:hover {
					color: ".$primary_color.";
				}
				
				.form-control:focus,
				select:focus,
				input:focus,
				textarea:focus,
				input[type='text']:focus,
				input[type='password']:focus,
				input[type='datetime']:focus,
				input[type='datetime-local']:focus,
				input[type='date']:focus,
				input[type='month']:focus,
				input[type='time']:focus,
				input[type='week']:focus,
				input[type='number']:focus,
				input[type='email']:focus,
				input[type='url']:focus,
				input[type='search']:focus,
				input[type='tel']:focus,
				input[type='color']:focus,
				.uneditable-input:focus {
					border-color: #bc2944;
				}
				
				input[type='submit'] {
					background-color: ".$primary_color.";
				}
				
				.entry-box.grid .img-box-caption .entry-category {
					position: absolute;
					top: -10px;
					left: 15px;
					padding: 0 5px;
					background-color: ".$primary_color.";
				}
				
				.widget-title:before {
					color: ".$primary_color.";
				}
				
				.btn-normal {
					background-color: ".$primary_color.";
				}
				
				.woocommerce #respond input#submit.alt,
				.woocommerce a.button.alt,
				.woocommerce button.button.alt,
				.woocommerce input.button.alt {
					background-color: ".$primary_color.";
				}
				
				.woocommerce #respond input#submit.alt:hover,
				.woocommerce a.button.alt:hover,
				.woocommerce button.button.alt:hover,
				.woocommerce input.button.alt:hover {
					background-color: ".$primary_color.";
				}
				
				.woocommerce #respond input#submit.alt.disabled,
				.woocommerce #respond input#submit.alt.disabled:hover,
				.woocommerce #respond input#submit.alt:disabled,
				.woocommerce #respond input#submit.alt:disabled:hover,
				.woocommerce #respond input#submit.alt:disabled[disabled],
				.woocommerce #respond input#submit.alt:disabled[disabled]:hover,
				.woocommerce a.button.alt.disabled,
				.woocommerce a.button.alt.disabled:hover,
				.woocommerce a.button.alt:disabled,
				.woocommerce a.button.alt:disabled:hover,
				.woocommerce a.button.alt:disabled[disabled],
				.woocommerce a.button.alt:disabled[disabled]:hover,
				.woocommerce button.button.alt.disabled,
				.woocommerce button.button.alt.disabled:hover,
				.woocommerce button.button.alt:disabled,
				.woocommerce button.button.alt:disabled:hover,
				.woocommerce button.button.alt:disabled[disabled],
				.woocommerce button.button.alt:disabled[disabled]:hover,
				.woocommerce input.button.alt.disabled,
				.woocommerce input.button.alt.disabled:hover,
				.woocommerce input.button.alt:disabled,
				.woocommerce input.button.alt:disabled:hover,
				.woocommerce input.button.alt:disabled[disabled],
				.woocommerce input.button.alt:disabled[disabled]:hover {
					background-color: ".$primary_color.";
				}
				
				.woocommerce nav.woocommerce-pagination ul li a:focus,
				.woocommerce nav.woocommerce-pagination ul li a:hover {
					color: ".$primary_color.";
				}
				
				.cactus-header .cactus-main-nav > li > a:hover,
				.cactus-header .cactus-main-nav > li.active > a {
					color: ".$primary_color.";
				}
				
				@media (min-width: 920px) {
					.frontpage .main-header:not(.fxd-header) .site-nav > div > ul > li > a:hover {
						color: ".$primary_color.";
					}
				}
				
				.cactus-btn.primary,
				.cactus-btn.cactus-primary,
				.cactus-contact-form input[type='submit'],
				input[type='submit'].form-control {
					background-color: ".$primary_color.";
				}
				
				.cactus-portfolio-filter li.active a {
					background-color: ".$primary_color.";
				}
				
				.cactus-feature-caption h4,
				.cactus-gallery-item h4,
				.cactus-testimonial-name,
				.cactus-team-name,
				.cactus-section .entry-title a {
					color: ".$primary_color.";
				}
				
				.cactus-pricing-item.cactus-featured .cactus-pricing-header {
					background-color: ".$primary_color.";
				}
				
				.jetpack_subscription_widget input[type='submit'] {
					background-color: ".$primary_color.";
				}
				.cactus-header .cactus-main-nav > li > a:hover, .cactus-header .cactus-main-nav > li.active > a {color:".$primary_color.";}
				.cactus-btn.primary, .cactus-btn.cactus-primary, .cactus-contact-form input[type='submit'], input[type='submit'].form-control {
   				 background-color: ".$primary_color.";
					}
				
				";
	
	}
			
	$custom_css = apply_filters( 'cactus_additional_css', $custom_css );
	
	if(is_page_template( 'template-sections.php' )){
		wp_add_inline_style( 'cactus-frontpage', str_replace('&gt;','>',stripslashes(wp_filter_nohtml_kses($custom_css))) );
	}else{
		wp_add_inline_style( 'cactus-style', str_replace('&gt;','>',stripslashes(wp_filter_nohtml_kses($custom_css))) );
	}
}
add_action( 'wp_enqueue_scripts', 'cactus_scripts' );

function cactus_admin_scripts(){
	global $pagenow;
	wp_enqueue_script( 'cactus-admin', get_template_directory_uri().'/assets/js/admin.js', array( 'jquery' ), '', true );
	if( ($pagenow == "themes.php" && isset($_GET['page']) && $_GET['page'] == "cactus-welcome") || $pagenow == 'nav-menus.php' ):
		wp_enqueue_style( 'cactus-admin', get_template_directory_uri() . '/assets/css/admin.css', '', '', false );
	endif;
	
	wp_localize_script( 'cactus-admin', 'cactus_admin', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
		)  );
		
	}
add_action( 'admin_enqueue_scripts', 'cactus_admin_scripts' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function cactus_customize_controls_enqueue(){
	wp_enqueue_style( 'cactus_library_customizer', get_template_directory_uri() . '/assets/css/customizer.css', '', '1.0.0', false );
	wp_enqueue_script( 'cactus_library_customizer_controls', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview', 'jquery-ui-sortable', 'jquery-ui-autocomplete' ), '1.0.0', true );
	wp_enqueue_style( 'jquery-mb-vimeo_player',  get_template_directory_uri() .'/assets/plugins/jquery.mb.vimeo_player/css/jquery.mb.vimeo_player.min.css', false, '', false );
	wp_enqueue_style( 'jquery-mb-ytplayer',  get_template_directory_uri() .'/assets/plugins/jquery.mb.YTPlayer/css/jquery.mb.YTPlayer.min.css', false, '', false );
	wp_enqueue_script( 'jquery-mb-vimeo_player', get_template_directory_uri() . '/assets/plugins/jquery.mb.vimeo_player/jquery.mb.vimeo_player.js' , array( 'jquery' ), null, true);
	wp_enqueue_script( 'jquery-mb-ytplayer', get_template_directory_uri() . '/assets/plugins/jquery.mb.YTPlayer/jquery.mb.YTPlayer.js' , array( 'jquery' ), null, true);
	
	$loading         = __('Updating','cactus');
	$complete        = __('Complete','cactus');
	$error           = __('Error','cactus');
	$import_options  = __('Restore Defaults','cactus');
	$confirm         = esc_js( __( 'Click OK to reset. Any Cactus options will be restored!', 'cactus' ) );
	$confirm_import  = esc_js( __( 'Click OK to import. Any Cactus options will be overwritten!', 'cactus' ) );
	wp_localize_script( 'cactus_library_customizer_controls', 'cactus_customize_params', array(
			'ajaxurl'        => admin_url('admin-ajax.php'),
			'themeurl' => get_template_directory_uri(),
			'loading' => $loading,
			'complete' => $complete,
			'error' => $error,
			'import_options' =>$import_options,
			'confirm' =>$confirm,
			'confirm_import' =>$confirm_import,
		)  );
		
}
add_action( 'customize_controls_init', 'cactus_customize_controls_enqueue' );


function cactus_customize_preview_enqueue(){
	wp_enqueue_script( 'cactus_library_customizer_preview', get_template_directory_uri() . '/assets/js/customizer-preview.js', array( 'jquery' ), '1.0.0', true );
	}
add_action( 'customize_preview_init', 'cactus_customize_preview_enqueue' );

/*
*  restore default
*/
function cactus_otpions_restore(){
  	add_option(CACTUS_TEXTDOMAIN.'_backup_'.time(),get_option(CACTUS_TEXTDOMAIN));
  	delete_option(CACTUS_TEXTDOMAIN);
	echo 'done';
	exit(0);
}
add_action( 'wp_ajax_cactus_otpions_restore', 'cactus_otpions_restore' );
add_action( 'wp_ajax_nopriv_cactus_otpions_restore', 'cactus_otpions_restore' );

/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function cactus_posted_on() {

	// Get the author name; wrap it in a link.
	$byline = sprintf(
		/* translators: %s: post author */
		__( 'by %s', 'cactus' ),
		'<span class="entry-author"> <a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . get_the_author() . '</a></span>'
	);
	
	// Finally, let's write all of this to the page.
	echo '<span class="entry-date">' . cactus_time_link() . '</span> | ' . $byline . '';
}
 
/**
 * Gets a nicely formatted string for the published date.
 */
function cactus_time_link() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	$time_string = sprintf( $time_string,
		get_the_date( DATE_W3C ),
		get_the_date(),
		get_the_modified_date( DATE_W3C ),
		get_the_modified_date()
	);

	// Wrap the time string in a link, and preface it with 'Posted on'.
	return sprintf(
		/* translators: %s: post date */
		__( '<span class="screen-reader-text">Posted on</span> %s ', 'cactus' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);
}

/**
 * Returns an accessibility-friendly link to edit a post or page.
 */
function cactus_edit_link() {

	$link = edit_post_link(
		sprintf(
			/* translators: %s: Name of current post */
			__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'cactus' ),
			get_the_title()
		),
		'<span class="edit-link">',
		'</span>'
	);

	return $link;
}

/**
 * Register widget area.
 *
 */
function cactus_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'cactus' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Add widgets here to appear in your sidebar.', 'cactus' ),
		'before_widget' => '<section id="%1$s" class="widget-box %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	
	register_sidebar( array(
		'name'          => __( 'Page Sidebar', 'cactus' ),
		'id'            => 'sidebar-page',
		'description'   => __( 'Add widgets here to appear in your pages sidebar.', 'cactus' ),
		'before_widget' => '<section id="%1$s" class="widget-box %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	
	register_sidebar( array(
		'name'          => __( 'Blog Sidebar', 'cactus' ),
		'id'            => 'sidebar-blog',
		'description'   => __( 'Add widgets here to appear in your posts sidebar.', 'cactus' ),
		'before_widget' => '<section id="%1$s" class="widget-box %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	
	register_sidebar( array(
		'name'          => __( 'Archives', 'cactus' ),
		'id'            => 'sidebar-archives',
		'description'   => __( 'Add widgets here to appear in your posts list sidebar.', 'cactus' ),
		'before_widget' => '<section id="%1$s" class="widget-box %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	
	
	register_sidebar( array(
		'name'          => __( 'WooCommerce Single Product', 'cactus' ),
		'id'            => 'sidebar-woo-single',
		'description'   => __( 'Add widgets here to appear in your products sidebar.', 'cactus' ),
		'before_widget' => '<section id="%1$s" class="widget-box %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	
	register_sidebar( array(
		'name'          => __( 'WooCommerce Archives', 'cactus' ),
		'id'            => 'sidebar-woo-archives',
		'description'   => __( 'Add widgets here to appear in your products list sidebar.', 'cactus' ),
		'before_widget' => '<section id="%1$s" class="widget-box %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	
	register_sidebar( array(
		'name'          => __( 'Footer 1', 'cactus' ),
		'id'            => 'footer-1',
		'description'   => __( 'Add widgets here to appear in your footer.', 'cactus' ),
		'before_widget' => '<section id="%1$s" class="widget-box %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer 2', 'cactus' ),
		'id'            => 'footer-2',
		'description'   => __( 'Add widgets here to appear in your footer.', 'cactus' ),
		'before_widget' => '<section id="%1$s" class="widget-box %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	
	register_sidebar( array(
		'name'          => __( 'Footer 3', 'cactus' ),
		'id'            => 'footer-3',
		'description'   => __( 'Add widgets here to appear in your footer.', 'cactus' ),
		'before_widget' => '<section id="%1$s" class="widget-box %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	
	register_sidebar( array(
		'name'          => __( 'Footer 4', 'cactus' ),
		'id'            => 'footer-4',
		'description'   => __( 'Add widgets here to appear in your footer.', 'cactus' ),
		'before_widget' => '<section id="%1$s" class="widget-box %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'cactus_widgets_init' );


/**
 *  Custom comments list
 */	
function cactus_comment($comment, $args, $depth) {

?>
   
<li <?php comment_class("comment media-comment"); ?> id="comment-<?php comment_ID() ;?>">
	<div class="media-avatar media-left">
	<?php echo get_avatar($comment,'70','' ); ?>
  </div>
  <div class="media-body">
      <div class="media-inner">
          <h4 class="media-heading clearfix">
             <?php echo get_comment_author_link();?> - <?php comment_date(); ?> <?php edit_comment_link(__('(Edit)','cactus'),'  ','') ;?>
             <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ;?>
          </h4>
          
          <?php if ($comment->comment_approved == '0') : ?>
                   <em><?php _e('Your comment is awaiting moderation.','cactus') ;?></em>
                   <br />
                <?php endif; ?>
                
          <div class="comment-content"><?php comment_text() ;?></div>
      </div>
  </div>
</li>
                                            
<?php
	}
	
/**
 * Returns breadcrumbs.
 */
function cactus_breadcrumbs() {
	$delimiter = '/'; 
	$before = '<span class="current">';
	$after = '</span>';
	if ( !is_home() && !is_front_page() || is_paged() ) {
		echo '<div itemscope itemtype="http://schema.org/WebPage" id="crumbs"><i class="fa fa-home"></i>';
		global $post;
		$homeLink = esc_url(home_url());
		echo ' <a itemprop="breadcrumb" href="' . $homeLink . '">' . __( 'Home' , 'cactus' ) . '</a> ' . $delimiter . ' ';
		if ( is_category() ) {
			global $wp_query;
			$cat_obj = $wp_query->get_queried_object();
			$thisCat = $cat_obj->term_id;
			$thisCat = get_category($thisCat);
			$parentCat = get_category($thisCat->parent);
			if ($thisCat->parent != 0){
				$cat_code = get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' ');
				echo $cat_code = str_replace ('<a','<a itemprop="breadcrumb"', $cat_code );
			}
			echo $before . '' . single_cat_title('', false) . '' . $after;
		} elseif ( is_day() ) {
			echo '<a itemprop="breadcrumb" href="' . esc_url(get_year_link(get_the_time('Y'))) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
			echo '<a itemprop="breadcrumb"  href="' . esc_url(get_month_link(get_the_time('Y'),get_the_time('m'))) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
			echo $before . get_the_time('d') . $after;
		} elseif ( is_month() ) {
			echo '<a itemprop="breadcrumb" href="' . esc_url(get_year_link(get_the_time('Y'))) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
			echo $before . get_the_time('F') . $after;
		} elseif ( is_year() ) {
			echo $before . get_the_time('Y') . $after;
		} elseif ( is_single() && !is_attachment() ) {
			if ( get_post_type() != 'post' ) {
				$post_type = get_post_type_object(get_post_type());
				$slug = $post_type->rewrite;
				echo '<a itemprop="breadcrumb" href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ' . $delimiter . ' ';
				echo $before . get_the_title() . $after;
			} else {
				$cat = get_the_category(); $cat = $cat[0];
				$cat_code = get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
				echo $cat_code = str_replace ('<a','<a itemprop="breadcrumb"', $cat_code );
				echo $before . get_the_title() . $after;
			}
		} elseif ( !is_single() && !is_page() && get_post_type() != 'post' ) {
			$post_type = get_post_type_object(get_post_type());
			if ($post_type)
			echo $before . $post_type->labels->singular_name . $after;
		} elseif ( is_attachment() ) {
			$parent = get_post($post->post_parent);
			$cat = get_the_category($parent->ID); $cat = isset($cat[0])?$cat[0]:'';
			echo '<a itemprop="breadcrumb" href="' . esc_url(get_permalink($parent)) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
			echo $before . get_the_title() . $after;
		} elseif ( is_page() && !$post->post_parent ) {
			echo $before . get_the_title() . $after;
		} elseif ( is_page() && $post->post_parent ) {
			$parent_id  = $post->post_parent;
			$breadcrumbs = array();
			while ($parent_id) {
				$page = get_page($parent_id);
				$breadcrumbs[] = '<a itemprop="breadcrumb" href="' .esc_url( get_permalink($page->ID)) . '">' . get_the_title($page->ID) . '</a>';
				$parent_id  = $page->post_parent;
			}
			$breadcrumbs = array_reverse($breadcrumbs);
			foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
			echo $before . get_the_title() . $after;
		} elseif ( is_search() ) {
			echo $before ;
			printf( __( 'Search Results for: %s', 'cactus' ),  get_search_query() );
			echo  $after;
		} elseif ( is_tag() ) {
			echo $before ;
			printf( __( 'Tag Archives: %s', 'cactus' ), single_tag_title( '', false ) );
			echo  $after;
		} elseif ( is_author() ) {
			global $author;
			$userdata = get_userdata($author);
			echo $before ;
			printf( __( 'Author Archives: %s', 'cactus' ),  $userdata->display_name );
			echo  $after;
		} elseif ( is_404() ) {
			echo $before;
			_e( 'Not Found', 'cactus' );
			echo  $after;
		}
		if ( get_query_var('paged') ) {
			if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() )
				echo sprintf( __( '( Page %s )', 'cactus' ), get_query_var('paged') );
		}
		echo '</div>';
	}
}

/**
 * Get option
 */
function cactus_option($name){
	
	global $cactus_options,$cactus_default_sections;
	
	if( isset($cactus_options[$name]) )
		return $cactus_options[$name];
	elseif(isset($cactus_default_sections[$name]))
		return $cactus_default_sections[$name];
	else
		return '';
	}

function cactus_option_saved($name){
	
	$cactus_options = get_option(CACTUS_TEXTDOMAIN);
	
	if( isset($cactus_options[$name]) )
		return $cactus_options[$name];
	else
		return '';
	}

/**
 * Do sections
 */
function cactus_do_sections(){
	
	global $cactus_sections, $cactus_section_key, $cactus_animation;
	
	$cactus_new_sections = array();

	$homepage_animation = cactus_option('homepage_animation');
	if($homepage_animation == '0' || $homepage_animation == '' )
		$cactus_animation = '';
	else
		$cactus_animation = 'cactus-animation';
					
	$section_order = cactus_option('section_order');
	if($section_order !=''){
		$my_sections = @json_decode($section_order,true);
		if(is_array($my_sections)){
			
			foreach($my_sections as $key){
				if(isset($cactus_sections[$key]))
					$cactus_new_sections[$key] = $cactus_sections[$key];
				}
			}
		}
	
	if(!empty($cactus_new_sections))
		$cactus_sections = $cactus_new_sections;
	
	if( $cactus_sections ){
	   foreach( $cactus_sections as $key=>$value){
		   $section_id   = cactus_option('section_id_'.$key);
		   $section_hide = cactus_option('section_hide_'.$key);
		    $font_color  = cactus_option('font_color_'.$key);
		   $background_parallax = cactus_option('background_parallax_'.$key);
		   $css_class        = 'cactus-section cactus-section-'.$key;
 
		   if( $background_parallax == '1' ){
			    $css_class .= ' cactus-parallax';
			   }
		   if( $font_color == '1' ){
			    $css_class .= ' cactus-text-light';
			   }
		   
		   $cactus_section_key = $key;
		   if( $section_hide != '1' || is_customize_preview() ):
		     if (is_customize_preview() && $section_hide == '1')
			 	$css_class .= ' hide';
				
			 echo '<div id="'.esc_attr($section_id).'" class="'.$css_class.'">';
			 do_action('cactus_before_section_'.$key);
			 get_template_part('sections/template',$key);
			 do_action('cactus_after_section_'.$key);
			 echo '</div>';
		   endif;
		   
		   }
	}
}

add_action('cactus_sections','cactus_do_sections');

/**
 * Get sidebar
 */
function cactus_get_sidebar($layout,$type){
	if($layout=='' || $layout == 'none' || $layout == 'no' )
		return '';
	?>
	<div class="col-aside-<?php echo $layout; ?>">
    <?php do_action('cactus_before_sidebar');?>
      <aside class="blog-side left text-left">
          <div class="widget-area">
             <?php get_sidebar($type);?>
          </div>
        </aside>
        <?php do_action('cactus_after_sidebar');?>
      </div>
<?php
	}

/**
 * Default menu
 */
function cactus_menu_fallback( $args ) {
		//if ( current_user_can( 'manage_options' ) ) {
			extract( $args );
			$fb_output = null;

			if ( $container ) {
				$fb_output = '<' . $container;

				if ( $container_id )
					$fb_output .= ' id="' . $container_id . '"';

				if ( $container_class )
					$fb_output .= ' class="' . $container_class . '"';

				$fb_output .= '>';
			}

			$fb_output .= '<ul';

			if ( $menu_id )
				$fb_output .= ' id="' . $menu_id . '"';

			if ( $menu_class )
				$fb_output .= ' class="' . $menu_class . '"';

			$fb_output .= '>';
			
			$fb_output .= '<li><a target="_blank" href="' . esc_url( 'http://www.velathemes.com/cactus-documentation.html#8' ) . '">'.__('How to create top menu','cactus').'</a></li>';

			$fb_output .= '</ul>';

			if ( $container )
				$fb_output .= '</' . $container . '>';

			$allowed_html = array(
				'a' => array( 'href' => array(), ),
				'div' => array( 'id' => array(), 'class' => array(), ),
				'ul' => array( 'class' => array() ),
				'li' => array(),
				'span' => array(),
			);

			echo wp_kses( $fb_output, $allowed_html );
	//	}
	}
	
/**
 * Split header left menu
 */
function cactus_header_left_menu( $args ) {
	
			extract( $args );
			$fb_output = null;

			if ( $container ) {
				$fb_output = '<' . $container;

				if ( $container_id )
					$fb_output .= ' id="' . $container_id . '"';

				if ( $container_class )
					$fb_output .= ' class="' . $container_class . '"';

				$fb_output .= '>';
			}

			$fb_output .= '<ul';

			if ( $menu_id )
				$fb_output .= ' id="' . $menu_id . '"';

			if ( $menu_class )
				$fb_output .= ' class="' . $menu_class . '"';

			$fb_output .= '>';
			
			$frontpage_menu = cactus_option('split_header_left_menu');
			
			if(is_array($frontpage_menu) && !empty($frontpage_menu)):
  				foreach($frontpage_menu as $item):
					if(trim($item['title'] )!='')
					$fb_output .= '<li><a href="' . esc_url( $item['link'] ) . '"><span>' . esc_attr( $item['title'] ) .'</span></a></li>';
				endforeach;
			endif;
			
			
			$fb_output .= '</ul>';

			if ( $container )
				$fb_output .= '</' . $container . '>';

			$allowed_html = array(
				'a' => array( 'href' => array(), ),
				'div' => array( 'id' => array(), 'class' => array(), ),
				'ul' => array( 'class' => array() ),
				'li' => array(),
				'span' => array(),
			);

			echo wp_kses( $fb_output, $allowed_html );

	}
	

/**
 * Function to change sections order
 */
function cactus_reorder_section($cactus_sections) {

	$cactus_new_sections = array();
	$section_order = cactus_option_saved('section_order');
	
	if($section_order !=''){
		$my_sections = @json_decode($section_order,true);

		if(is_array($my_sections)){
			
			foreach($my_sections as $key){
				if(isset($cactus_sections[$key]))
					$cactus_new_sections[$key] = $cactus_sections[$key];
				}
			}
		}

	if(!empty($cactus_new_sections))
		$cactus_sections = array_merge($cactus_new_sections,$cactus_sections);

	return $cactus_sections;
	
}
add_filter( 'cactus_get_sections','cactus_reorder_section' );

/**
 * Selective Refresh
 */
function cactus_register_partials( WP_Customize_Manager $wp_customize ) {
	
	  global $cactus_customizer_options;
	  $sections = cactus_get_sections();
	// Abort if selective refresh is not available.
		if ( ! isset( $wp_customize->selective_refresh ) ) {
			return;
		}

		// Bail early if we don't have any options.
		if ( empty( $cactus_customizer_options ) ) {
			return;
		}
	
	/*$wp_customize->selective_refresh->add_partial( 'frontpage_menu_selective', array(
		'selector' => '.frontpage_menu_selective ul.cactus-main-nav',
		'settings' => array( 'cactus[frontpage_menu]' ),
		'render_callback' => 'cactus_frontpage_menu',
	) );*/
	// Loops through each of the options
	
	foreach($sections as $key => $value){
		
		$wp_customize->selective_refresh->add_partial( 'section_title_'.$key.'_selective', array(
			'selector' => '.section_title_'.$key.'_selective',
			'settings' => array( 'cactus[section_title_'.$key.']' ),
			'render_callback' => 'cactus_section_title_'.$key.'',
			
		) );
		
		$wp_customize->selective_refresh->add_partial( 'section_subtitle_'.$key.'_selective', array(
			'selector' => '.section_subtitle_'.$key.'_selective',
			'settings' => array( 'cactus[section_subtitle_'.$key.']' ),
			'render_callback' => 'cactus_section_subtitle_'.$key.'',
			
		) );
	}
	
	$wp_customize->selective_refresh->add_partial( 'video_title_banner_selective', array(
		'selector' => '.video_title_banner_selective',
		'settings' => array( 'cactus[video_title_banner]' ),
		'render_callback' => 'cactus_video_title_banner',
	) );
	
	$wp_customize->selective_refresh->add_partial( 'video_subtitle_banner_selective', array(
		'selector' => '.video_subtitle_banner_selective',
		'settings' => array( 'cactus[video_subtitle_banner]' ),
		'render_callback' => 'cactus_video_subtitle_banner',
	) );
	
	$wp_customize->selective_refresh->add_partial( 'button_text_banner_selective', array(
		'selector' => '.button_text_banner_selective',
		'settings' => array( 'cactus[button_text_banner]' ),
		'render_callback' => 'cactus_button_text_banner',
	) );
	
	/*$wp_customize->selective_refresh->add_partial( 'button_link_banner_selective', array(
		'selector' => '.button_link_banner_selective',
		'settings' => array( 'cactus[button_link_banner]' ),
		//'render_callback' => 'cactus_button_link_banner',
	) );*/
	
	
	$wp_customize->selective_refresh->add_partial( 'text_promo_selective', array(
		'selector' => '.text_promo_selective',
		'settings' => array( 'cactus[text_promo]' ),
		'render_callback' => 'cactus_text_promo',
		
	) );
	
	$wp_customize->selective_refresh->add_partial( 'button_text_promo_selective', array(
		'selector' => '.button_text_promo_selective',
		'settings' => array( 'cactus[button_text_promo]' ),
		'render_callback' => 'cactus_button_text_promo',
		
	) );
	
	$wp_customize->selective_refresh->add_partial( 'address_contact_selective', array(
		'selector' => '.address_contact_selective',
		'settings' => array( 'cactus[address_contact]' ),
		'render_callback' => 'cactus_address_contact',
		
	) );
	
	$wp_customize->selective_refresh->add_partial( 'email_contact_selective', array(
		'selector' => '.email_contact_selective',
		'settings' => array( 'cactus[email_contact]' ),
		'render_callback' => 'cactus_email_contact',
		
	) );
	$wp_customize->selective_refresh->add_partial( 'tel_contact_selective', array(
		'selector' => '.tel_contact_selective',
		'settings' => array( 'cactus[tel_contact]' ),
		'render_callback' => 'cactus_tel_contact',
		
	) );
	
	$wp_customize->selective_refresh->add_partial( 'button_text_news_selective', array(
		'selector' => '.button_text_news_selective',
		'settings' => array( 'cactus[button_text_news]' ),
		'render_callback' => 'cactus_button_text_news',
		
	) );
	
	$wp_customize->selective_refresh->add_partial( 'button_text_call_to_action_selective', array(
		'selector' => '.button_text_call_to_action_selective',
		'settings' => array( 'cactus[button_text_call_to_action]' ),
		'render_callback' => 'cactus_button_text_call_to_action',
		
	) );
	
	$wp_customize->selective_refresh->add_partial( 'section_shop_selective', array(
		'selector' => '.section_shop_selective',
		'settings' => array( 'cactus[items_shop]', 'cactus[categories_shop]', 'cactus[orderby_shop]', 'cactus[order_shop]', 'cactus[shortcode_shop]' ),
		'render_callback' => 'cactus_section_shop_selective',
		
	) );
	
	$wp_customize->selective_refresh->add_partial( 'copyright_selective', array(
		'selector' => '.copyright_selective',
		'settings' => array( 'cactus[copyright]' ),
		'render_callback' => 'cactus_copyright',
	) );
	
	$wp_customize->selective_refresh->add_partial( 'footer_logo_selective', array(
		'selector' => '.footer_logo_selective',
		'settings' => array( 'cactus[footer_logo]' ),
		'render_callback' => '',
	) );
	
	/*$wp_customize->selective_refresh->add_partial( 'image_contact_selective', array(
		'selector' => '.image_contact_selective',
		'settings' => array( 'cactus[image_contact]' ),
		
	) );*/
	
	$wp_customize->selective_refresh->add_partial( 'header_site_title', array(
		'selector' => '.site-name',
		'settings' => array( 'blogname' ),
		'render_callback' => 'cactus_header_site_title',
		
	) );
	
	$wp_customize->selective_refresh->add_partial( 'header_site_description', array(
		'selector' => '.site-tagline',
		'settings' => array( 'blogdescription' ),
		'render_callback' => 'cactus_header_site_descriptione',
		
	) );
	
	$wp_customize->selective_refresh->add_partial( 'button_text_shop_selective', array(
		'selector' => '.button_text_shop_selective',
		'settings' => array( 'cactus[button_text_shop]' ),
		'render_callback' => 'cactus_button_text_shop',
		
	) );
	
	$wp_customize->get_section ('title_tagline')->panel = 'panel-header';
	//$wp_customize->get_section ('colors')->panel = 'panel-header';
	$wp_customize->get_section ('header_image')->panel = 'panel-header';
	
}
add_action( 'customize_register', 'cactus_register_partials' );

/* frontpage menu selective*/
function cactus_frontpage_menu(){
	
	$cactus_options = get_option(CACTUS_TEXTDOMAIN);
	if( isset($cactus_options['frontpage_menu']) )
		return $cactus_options['frontpage_menu'];
		
	}
	
	
/* section banner */
function cactus_video_title_banner(){
	$cactus_options = get_option(CACTUS_TEXTDOMAIN);
	if( isset($cactus_options['video_title_banner']) )
		return $cactus_options['video_title_banner'];
	}
function cactus_video_subtitle_banner(){
	$cactus_options = get_option(CACTUS_TEXTDOMAIN);
	if( isset($cactus_options['video_subtitle_banner']) )
		return $cactus_options['video_subtitle_banner'];
	}
function cactus_button_text_banner(){
	$cactus_options = get_option(CACTUS_TEXTDOMAIN);
	if( isset($cactus_options['button_text_banner']) )
		return $cactus_options['button_text_banner'];
	}

/*function cactus_button_link_banner(){
	$cactus_options = get_option(CACTUS_TEXTDOMAIN);
	if( isset($cactus_options['button_link_banner']) )
		return $cactus_options['button_link_banner'];
	}*/
	
/* section service */
function cactus_section_title_service(){
	$cactus_options = get_option(CACTUS_TEXTDOMAIN);
	if( isset($cactus_options['section_title_service']) )
		return $cactus_options['section_title_service'];
	
	}

function cactus_section_subtitle_service(){
	$cactus_options = get_option(CACTUS_TEXTDOMAIN);
	if( isset($cactus_options['section_subtitle_service']) )
		return $cactus_options['section_subtitle_service'];
	
	}
	
/* section works */
function cactus_section_title_works(){
	$cactus_options = get_option(CACTUS_TEXTDOMAIN);
	if( isset($cactus_options['section_title_works']) )
		return $cactus_options['section_title_works'];
	}
	
function cactus_section_subtitle_works(){
	$cactus_options = get_option(CACTUS_TEXTDOMAIN);
	if( isset($cactus_options['section_subtitle_works']) )
		return $cactus_options['section_subtitle_works'];
	}

/* section promo */
function cactus_section_title_promo(){
	$cactus_options = get_option(CACTUS_TEXTDOMAIN);
	if( isset($cactus_options['section_title_promo']) )
		return $cactus_options['section_title_promo'];
	}
	
function cactus_section_subtitle_promo(){
	$cactus_options = get_option(CACTUS_TEXTDOMAIN);
	if( isset($cactus_options['section_subtitle_promo']) )
		return $cactus_options['section_subtitle_promo'];
	}
function cactus_text_promo(){
	$cactus_options = get_option(CACTUS_TEXTDOMAIN);
	if( isset($cactus_options['text_promo']) )
		return $cactus_options['text_promo'];
	}

function cactus_button_text_promo(){
	$cactus_options = get_option(CACTUS_TEXTDOMAIN);
	if( isset($cactus_options['button_text_promo']) )
		return $cactus_options['button_text_promo'];
	}	
	
/* section team */
function cactus_section_title_team(){
	$cactus_options = get_option(CACTUS_TEXTDOMAIN);
	if( isset($cactus_options['section_title_team']) )
		return $cactus_options['section_title_team'];
	}
	
function cactus_section_subtitle_team(){
	$cactus_options = get_option(CACTUS_TEXTDOMAIN);
	if( isset($cactus_options['section_subtitle_team']) )
		return $cactus_options['section_subtitle_team'];
	}
	
	
/* section counter */
function cactus_section_title_counter(){
	$cactus_options = get_option(CACTUS_TEXTDOMAIN);
	if( isset($cactus_options['section_title_counter']) )
		return $cactus_options['section_title_counter'];
	}
	
function cactus_section_subtitle_counter(){
	$cactus_options = get_option(CACTUS_TEXTDOMAIN);
	if( isset($cactus_options['section_subtitle_counter']) )
		return $cactus_options['section_subtitle_counter'];
	}
/* section testimonial */
function cactus_section_title_testimonial(){
	$cactus_options = get_option(CACTUS_TEXTDOMAIN);
	if( isset($cactus_options['section_title_testimonial']) )
		return $cactus_options['section_title_testimonial'];
	}
	
function cactus_section_subtitle_testimonial(){
	$cactus_options = get_option(CACTUS_TEXTDOMAIN);
	if( isset($cactus_options['section_subtitle_testimonial']) )
		return $cactus_options['section_subtitle_testimonial'];
	}
	
/* section news */
function cactus_section_title_news(){
	$cactus_options = get_option(CACTUS_TEXTDOMAIN);
	if( isset($cactus_options['section_title_news']) )
		return $cactus_options['section_title_news'];
	}
	
function cactus_section_subtitle_news(){
	$cactus_options = get_option(CACTUS_TEXTDOMAIN);
	if( isset($cactus_options['section_subtitle_news']) )
		return $cactus_options['section_subtitle_news'];
	}

function cactus_button_text_news(){
	$cactus_options = get_option(CACTUS_TEXTDOMAIN);
	if( isset($cactus_options['button_text_news']) )
		return $cactus_options['button_text_news'];
	}
/* section contact */
function cactus_section_title_contact(){
	$cactus_options = get_option(CACTUS_TEXTDOMAIN);
	if( isset($cactus_options['section_title_contact']) )
		return $cactus_options['section_title_contact'];
	}
	
function cactus_section_subtitle_contact(){
	$cactus_options = get_option(CACTUS_TEXTDOMAIN);
	if( isset($cactus_options['section_subtitle_contact']) )
		return $cactus_options['section_subtitle_contact'];
	}
function cactus_address_contact(){
	$cactus_options = get_option(CACTUS_TEXTDOMAIN);
	if( isset($cactus_options['address_contact']) )
		return $cactus_options['address_contact'];
	}
function cactus_email_contact(){
	$cactus_options = get_option(CACTUS_TEXTDOMAIN);
	if( isset($cactus_options['email_contact']) )
		return $cactus_options['email_contact'];
	}
function cactus_tel_contact(){
	$cactus_options = get_option(CACTUS_TEXTDOMAIN);
	if( isset($cactus_options['tel_contact']) )
		return $cactus_options['tel_contact'];
	}
/*section shop*/

function cactus_section_title_shop(){
	$cactus_options = get_option(CACTUS_TEXTDOMAIN);
	if( isset($cactus_options['section_title_shop']) )
		return $cactus_options['section_title_shop'];
	}
	
function cactus_section_subtitle_shop(){
	$cactus_options = get_option(CACTUS_TEXTDOMAIN);
	if( isset($cactus_options['section_subtitle_shop']) )
		return $cactus_options['section_subtitle_shop'];
	}
	
function cactus_button_text_shop(){
	$cactus_options = get_option(CACTUS_TEXTDOMAIN);
	if( isset($cactus_options['button_text_shop']) )
		return $cactus_options['button_text_shop'];
	}
function cactus_section_shop_selective(){
	
	return cactus_shop_content(false);
	
	}


/* footer */
function cactus_copyright(){
	
	$cactus_options = get_option(CACTUS_TEXTDOMAIN);
	if( isset($cactus_options['copyright']) )
		return $cactus_options['copyright'];
		
	}

//button_text_call_to_action

function cactus_header_site_title(){
	return get_bloginfo( 'name' );
	}

function cactus_header_site_descriptione(){
	return get_bloginfo( 'description' );
	}

function cactus_ajax_get_image_url(){
	
	$id = $_POST['id'];
	$image = $id;
	if (is_numeric($id)) {
			$image_attributes = wp_get_attachment_image_src($id, 'full');
			$image   = $image_attributes[0];
		  }
	echo $image;
	exit(0);
	
	}
add_action('wp_ajax_cactus_ajax_get_image_url', 'cactus_ajax_get_image_url');
add_action('wp_ajax_nopriv_cactus_ajax_get_image_url', 'cactus_ajax_get_image_url');

/*
 * Get header widgets
 */
function cactus_get_header_widgets( $key, $output = true ){
	
	$widgets = cactus_option($key);
	$html = '';
	if(is_array($widgets) && !empty($widgets)):
		$html = "";
		foreach($widgets as $item):
			$html .= '<span class="cactus-microwidget">';
			if($item['link']!=''){
				$html .= '<a href="'.esc_url($item['link']).'" target="'.esc_attr($item['target']).'">';
			}
			if($item['icon']!=''){
				$html .= '<i class="fa '.esc_attr($item['icon']).'"></i>&nbsp;&nbsp;';
			}
			$html .= esc_attr($item['text']);
			if($item['link']!=''){
				$html .= '</a>';
			}
			$html .= '</span>';
		endforeach;
	endif;
	if( $output == true)
		echo $html;
	else
		return $html;
	
	}

/**
 * Get WooCommerce products categories.
 *
 */
function cactus_get_woo_categories() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return array();
	}
	$cactus_categories_array = array();
	$cactus_prod_categories = get_categories(
		array(
			'taxonomy' => 'product_cat',
			'hide_empty' => 1,
			'title_li' => '',
		)
	);
	
	if ( ! empty( $cactus_prod_categories ) ) {
		foreach ( $cactus_prod_categories as $cactus_prod_cat ) {
			if ( ! empty( $cactus_prod_cat->term_id ) && ! empty( $cactus_prod_cat->name ) ) {
				$cactus_categories_array[ $cactus_prod_cat->term_id ] = $cactus_prod_cat->name;
			}
		}
	}

	return $cactus_categories_array;
}


/**
 * Get content for shop section.
 *
 */
function cactus_shop_content($echo = true) {
	if ( !class_exists( 'WooCommerce' ) )
		return '';
	$cactus_options = get_option(CACTUS_TEXTDOMAIN);
		if ( $echo == false ) ob_start();
	?>
	<div class="cactus-shop-content">
    <ul class="products">
		<?php
		$shop_shortcode = isset($cactus_options['shortcode_shop'])?$cactus_options['shortcode_shop']:'';
		if ( ! empty( $shop_shortcode ) ) {
			echo do_shortcode( $shop_shortcode );
			echo '</div>';
			return;
		}
		$shop_items = isset($cactus_options['items_shop'])?$cactus_options['items_shop']:'4';

		$args = array(
			'post_type' => 'product',
		);
		
		$args['posts_per_page'] = ! empty( $shop_items ) ? absint( $shop_items ) : 4;
		if(!is_numeric( $args['posts_per_page'] ))
			$args['posts_per_page'] = 4;
			
		$categories_shop = isset($cactus_options['categories_shop'])?$cactus_options['categories_shop']:'';
		if ( sizeof( $categories_shop ) >= 1 && ! empty( $categories_shop[0] ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'product_cat',
					'field' => 'term_id',
					'terms' => $categories_shop,
				),
			);
		}

		$order_shop = isset($cactus_options['order_shop'])?$cactus_options['order_shop']:'DESC';
		if ( ! empty( $order_shop ) ) {
			$args['order'] = $order_shop;
		}
		$orderby_shop = isset($cactus_options['orderby_shop'])?$cactus_options['orderby_shop']:'date';
		if ( ! empty( $orderby_shop ) ) {
			$args['orderby'] = $orderby_shop;
		}

		$loop = new WP_Query( $args );

		if ( $loop->have_posts() ) {
			$i = 1;

			while ( $loop->have_posts() ) {
				$loop->the_post();
				global $product;
				global $post;
				
				$id               = get_the_ID();
				$size             = 'shop_catalog';
				$gallery          = get_post_meta($id, '_product_image_gallery', true);
				$attachment_image = '';
			
				if (!empty($gallery)) {
					$gallery          = explode( ',', $gallery );
					$first_image_id   = $gallery[0];
					$attachment_image = wp_get_attachment_image( $first_image_id, $size, false, array( 'class' => 'hover-image' ) );
				}
				
				if (has_post_thumbnail()) {
					$thumb = get_the_post_thumbnail(get_the_ID(), "shop_catalog"); 
			
					$product_image = $thumb.$attachment_image;
				} else {
					$product_image = '<img src="'. wc_placeholder_img_src() .'" alt="Placeholder" />';
				}
				?>
                
                
                
                <li class="product <?php echo ($i==1?'first':'')?> <?php echo ($i%4==0?'last':'')?>">
                                        <a href="<?php echo esc_url( get_permalink() ); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
                                            <?php
											$onsale = '';
											if ($product->is_on_sale()) : 
												$onsale = apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . __( 'Sale!', 'cactus' ) . '</span>', $post, $product ); 
											endif;
											echo $product_image;
											?>
    
                                            <h2 class="woocommerce-loop-product__title"><?php the_title(); ?></h2>
                                            <?php
											  woocommerce_template_loop_rating();
                                              woocommerce_template_loop_price();
  
											?>
                                        </a>
                                        <?php
										global $woocommerce, $wishlists;
	
										$add_to_cart_link_class =  implode( ' ', array_filter( array(
														//	'product_type_' . $product->product_type,
															$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
															$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : ''
													) ) );
										woocommerce_template_loop_add_to_cart(array('class'=> 'button '.$add_to_cart_link_class));		
										
										?>
                                               
                                    </li>
				<?php
				$i++;
			}
			wp_reset_postdata();
			
		}
		?>
         </ul>
	</div>
	<?php
	
	if ( $echo == false ) {
		$return = ob_get_clean();
		ob_end_flush();
		return $return;
	}
}


/*
 * Notifications in customize
 */
require get_template_directory() . '/inc/customizer-notify/class-cactus-customizer-notify.php';

$config_customizer = array(
	'recommended_plugins'       => array(
		'vela-companion' => array(
			'recommended' => true,
			'description' => sprintf( esc_html__( 'Install and activate %s plugin to take full advantage of Cactus theme. More options could be found at Customize and page meta options.', 'cactus' ), sprintf( '<strong>%s</strong>', 'Cactus Companion' ) ),
		),
	),
	'recommended_actions'       => array(),
	'recommended_actions_title' => esc_html__( 'Recommended Actions', 'cactus' ),
	'recommended_plugins_title' => esc_html__( 'Recommended Plugins', 'cactus' ),
	'install_button_label'      => esc_html__( 'Install and Activate', 'cactus' ),
	'activate_button_label'     => esc_html__( 'Activate', 'cactus' ),
	'deactivate_button_label'   => esc_html__( 'Deactivate', 'cactus' ),
);
//Cactus_Customizer_Notify::init( apply_filters( 'cactus_customizer_notify_array', $config_customizer ) );

/**
 * Include the TGM_Plugin_Activation class.
 */
if ( !class_exists( 'TGM_Plugin_Activation' ) ) 
	load_template( trailingslashit( get_template_directory() ) . 'inc/class-tgm-plugin-activation.php' );

add_action( 'tgmpa_register', 'cactus_theme_register_required_plugins' );

/**
 * Register the required plugins for this theme.
 *
 */
function cactus_theme_register_required_plugins() {

    $plugins = array(
		array(
			'name'     				=> __('Vela Companion','cactus'), // The plugin name
			'slug'     				=> 'vela-companion', // The plugin slug (typically the folder name)
			'source'   				=> '', // The plugin source
			'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
			'version' 				=> '1.0.0', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
		),

		array(
			'name'     				=> __('Elementor','cactus'), // The plugin name
			'slug'     				=> 'elementor', // The plugin slug (typically the folder name)
			'source'   				=> '', // The plugin source
			'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
			'version' 				=> '1.0.0', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
		),

	);

    /**
     * Array of configuration settings. Amend each line as needed.
     */
    $config = array(
        'id'           => 'vela-companion',                 // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to pre-packaged plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.
    );

    tgmpa( $plugins, $config );

}

/**
 * Dismiss welcome notice.
 *
 */
 
function cactus_dismiss_notice(){
	update_option('cactus_welcome_notice',1);
}
add_action('wp_ajax_cactus_dismiss_notice', 'cactus_dismiss_notice');
add_action('wp_ajax_nopriv_cactus_dismiss_notice', 'cactus_dismiss_notice');

/**
 * Admin page
 *
 */
add_action('admin_menu', 'cactus_about_theme');

function cactus_about_theme() {
    add_theme_page(
        esc_attr__('About Cactus', 'cactus' ),
        esc_attr__('About Cactus', 'cactus' ),
        'manage_options',
        'cactus-welcome',
        'cactus_about_theme_callback' );
}
 
function cactus_about_theme_callback() {
	
	$theme_info = wp_get_theme();
	
    echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
        echo '<h2>'.esc_attr__('About Cactus', 'cactus' ).'</h2>';
	
	echo '<div class="cactus-info-wrap">
	<h1>'.sprintf(esc_attr__('Welcome to %s Version %s', 'cactus'),$theme_info->get( 'Name' ), $theme_info->get( 'Version' ) ).'</h1>
	<p>'.$theme_info->get( 'Name' ).' is a modern and free WordPress theme which can be used to build both one-page and multi-page sites. Based on Bootstrap frontend framework, its fully responsive design looks stunning on any device. With 10+ prebuilt sections such as full width slider, services, promo, team, gallery, testimonials, counter, clients, recent posts, contact, etc., '.$theme_info->get( 'Name' ).' is perfect for any type of site like design agency, corporate, restaurant, personal, showcase, magazine, portfolio, etc. And the theme is compatible with Elementor Page Builder, WooCommerce, Polylang. Moreover, all setting options are embedded in Customize, you can change everything and check the changes in real time. Just insert text & images, no coding knowledge needed. This theme is also SEO friendly which can help you attain higher ranking on Google.</p>
	
	<div class="cactus-message blue">
	'.esc_attr__('Now you can just go to Customize to activate the companion plugin and customize everything in your site.', 'cactus' ).'
	<p><a class="button" href="'.admin_url('customize.php').'"> '.esc_attr__('Customize', 'cactus' ).' </a></p>
	</div>
	
	<div class="cactus-message">
	'.esc_attr__('More info could be found at the manual.', 'cactus' ).'
	<p><a class="button" target="_blank" href="'.esc_url('https://velathemes.com/cactus-documentation/').'"> '.esc_attr__('Step-by-step Manual', 'cactus' ).' </a></p>
	</div>
	
	<div class="cactus-message green">
	'.esc_attr__('If you have checked the documentation and still having an issue, please post in the support thread.', 'cactus' ).'
	<p><a class="button" target="_blank" href="'.esc_url('https://wordpress.org/support/theme/cactus').'"> '.esc_attr__('Support Thread', 'cactus' ).' </a></p>
	</div>
	
	<div class="cactus-message blue">
	<h2>'.esc_attr__('FAQ', 'cactus').'</h2>
	
	<p><a class="" target="_blank" href="'.esc_url('https://velathemes.com/faq/#1').'">'.esc_attr__('How to Create Child Theme?', 'cactus' ).'</a></p>
	<p><a class="" target="_blank" href="'.esc_url('https://velathemes.com/faq/#2').'">'.esc_attr__('How to Add Custom CSS to Your Website?', 'cactus' ).'</a></p>
	<p><a class="" target="_blank" href="'.esc_url('https://velathemes.com/faq/#3').'">'.esc_attr__('How to Translate the Theme?', 'cactus' ).'</a></p>
	<p><a class="" target="_blank" href="'.esc_url('https://velathemes.com/faq/#4').'">'.esc_attr__('How to Make Your Site Multilingual?', 'cactus' ).'</a></p>
	<p><a class="" target="_blank" href="'.esc_url('https://velathemes.com/faq/#5').'">'.esc_attr__('How to Increase Memory Limit?', 'cactus' ).'</a></p>

	</div>
	
	
	<div class="cactus-message blue">
	<h2>'.esc_attr__('Review Cactus on WordPress', 'cactus').'</h2>
	
	<p>We are grateful that you have chose our theme. If you like Cactus, please take 1 minitue to post your review on Wordpress. Few words of appreciation also motivates the development team.</p>
	<p><a class="button" target="_blank" href="'.esc_url('https://wordpress.org/support/theme/cactus/reviews/#new-post').'"> '.esc_attr__('Post Your Review', 'cactus' ).' </a></p>
	</div>
	
	
	</div>';
		
    echo '</div>';
	
}

/**
 * Add script to the footer
 *
 */

function cactus_footer_script(){ 
	$display_scroll_to_top = cactus_option('display_scroll_to_top');
	if($display_scroll_to_top=='1' || is_customize_preview() ){
		$css_class = 'back-to-top';
		if( $display_scroll_to_top !=1 && is_customize_preview() )
			$css_class  .= ' hide';
			
		echo '<div class="'.$css_class.'"></div>';
		}

 } 
add_action('wp_footer','cactus_footer_script');

/**
 * Add title bar
 *
 */
function cactus_page_title_bar(  $content, $type='page'){
	
	$display_titlebar = cactus_option('display_titlebar');
	if($display_titlebar==''||$display_titlebar==0)
		return '';
	
    $title_bar_layout = cactus_option('title_bar_layout');
	$title_bar_layout = apply_filters('cactus_title_bar_layout',$title_bar_layout);
	$class = 'page-title-bar '.$title_bar_layout;
	$html = '<section class="'.$class.'">';
	$html .= '<div class="container">';
	$html .= '<div class="page-title-bar-inner">';
	if($type=='page'){
   		$html .= ' <hgroup class="page-title">';
   		$html .= '<h1>'.get_the_title().'</h1>';
    	$html .= '</hgroup>';
     }
	 
    $html .= '<div class="breadcrumb-nav">';
	ob_start();
    cactus_breadcrumbs();
	$html .= ob_get_contents();
	ob_end_clean();
	$html .= '</div>';
	$html .= '<div class="clearfix"></div>';
	$html .= '</div>';
	$html .= '</div>';
	$html .= '</section>';

	return $html;
	}

add_filter( 'cactus_page_title_bar', 'cactus_page_title_bar', 10, 2 );

/**
 * Get separator
 *
 */
function cuctus_get_separator($type = 'cloud', $color = '#fff', $height = '100' ){
	
	$height = str_replace('px','',$height);
	$class = "cactus-section-separator";
	
	if($type == 'triangle-up')
		$class .= " cactus-triangle-up";
	if($type == 'triangle-down')
		$class .= " cactus-triangle-down";
	
	$html = '<div class="'.$class.'" style="color:'.esc_attr($color).'; height:'.absint($height).'px;">';
	switch($type){
		case "diagonal":
			$html .= '<svg preserveAspectRatio="none" viewBox="0 0 1000 100" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M0 100 L1000 0 L1000 100 Z"></path>
                                            </svg>';
		break;
		case "diagonal-reverse":
			$html .= '<svg preserveAspectRatio="none" viewBox="0 0 1000 100" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M0 0 L1000 100 L0 100 Z" fill="#fff"></path>
                                            </svg>';
		break;
		case "triangle-up":
			$html .= '<svg preserveAspectRatio="none" viewBox="0 0 20 10" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M0 10L10 0 20 10z"></path>
                                            </svg>';
		break;
		case "triangle-down":
			$html .= '<svg preserveAspectRatio="none" viewBox="0 0 20 10" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M20 0L10 10 0 0 0 10 20 10z"></path>
                                            </svg>';
		break;
		case "big-triangle-up":
			$html .= '<svg preserveAspectRatio="none" viewBox="0 0 20 10" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M0 10L10 0 20 10z"></path>
                                            </svg>';
		break;
		case "big-triangle-down":
			$html .= '<svg preserveAspectRatio="none" viewBox="0 0 20 10" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M20 0L10 10 0 0 0 10 20 10z"></path>
                                            </svg>';
		break;
		case "curve-up":
			$html .= '<svg preserveAspectRatio="none" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M0 100 C 20 0 50 0 100 100 Z"></path>
                                            </svg>';
		break;
		case "curve-down":
			$html .= '<svg preserveAspectRatio="none" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M0 100 L0 0 C50 100 80 100 100 0 L100 100Z"></path>
                                            </svg>';
		break;
		case "cloud":
			$html .= '<svg id="clouds" xmlns="http://www.w3.org/2000/svg" version="1.1" width="100%" height="100" viewBox="0 0 100 100" preserveAspectRatio="none">
                                                <path d="M-5 100 Q 0 20 5 100 Z
                                                         M0 100 Q 5 0 10 100
                                                         M5 100 Q 10 30 15 100
                                                         M10 100 Q 15 10 20 100
                                                         M15 100 Q 20 30 25 100
                                                         M20 100 Q 25 -10 30 100
                                                         M25 100 Q 30 10 35 100
                                                         M30 100 Q 35 30 40 100
                                                         M35 100 Q 40 10 45 100
                                                         M40 100 Q 45 50 50 100
                                                         M45 100 Q 50 20 55 100
                                                         M50 100 Q 55 40 60 100
                                                         M55 100 Q 60 60 65 100
                                                         M60 100 Q 65 50 70 100
                                                         M65 100 Q 70 20 75 100
                                                         M70 100 Q 75 45 80 100
                                                         M75 100 Q 80 30 85 100
                                                         M80 100 Q 85 20 90 100
                                                         M85 100 Q 90 50 95 100
                                                         M90 100 Q 95 25 100 100
                                                         M95 100 Q 100 15 105 100 Z">
                                                </path>
                                            </svg>';
		break;
		
		
		
		}
		$html .= '</div>';
		return $html;
	}

/*
*  Allow tags
*/
add_action('init', 'cactus_html_tags_code', 10);
function cactus_html_tags_code() {
	
	global $allowedposttags;

    $allowed_atts = array(
		'align'      => array(),
		'class'      => array(),
		'type'       => array(),
		'id'         => array(),
		'dir'        => array(),
		'lang'       => array(),
		'style'      => array(),
		'xml:lang'   => array(),
		'src'        => array(),
		'alt'        => array(),
		'href'       => array(),
		'rel'        => array(),
		'rev'        => array(),
		'target'     => array(),
		'novalidate' => array(),
		'type'       => array(),
		'value'      => array(),
		'name'       => array(),
		'tabindex'   => array(),
		'action'     => array(),
		'method'     => array(),
		'for'        => array(),
		'width'      => array(),
		'height'     => array(),
		'data'       => array(),
		'title'      => array(),
	);
	$allowedposttags['form']     = $allowed_atts;
    $allowedposttags["script"] = array("src" => array(),"name" => array(),"id" => array(),"type" => array());
	$allowedposttags['iframe'] = array (
		'align'       => true,
		'frameborder' => true,
		'height'      => true,
		'width'       => true,
		'sandbox'     => true,
		'seamless'    => true,
		'scrolling'   => true,
		'srcdoc'      => true,
		'src'         => true,
		'class'       => true,
		'id'          => true,
		'style'       => true,
		'border'      => true,
	);
	
	$allowedposttags["object"] = array("height" => array(), "width" => array());
	$allowedposttags["param"] = array("name" => array(), "value" => array());
	
	$allowedposttags["embed"] = array(
		"src" => array(),
		"type" => array(),
		"allowfullscreen" => array(),
		"allowscriptaccess" => array(),
		"height" => array(),
		"width" => array()
		);
    $allowedposttags["style"] = array("type" => array());
	$allowedposttags["link"] = array("rel" => array(),"href" => array(),"id" => array(),"type" => array(),"media" => array());
	$allowedposttags["input"] = array("name" => array(),"id" => array(),"value" => array(),"class" => array(),"placeholder" => array(),"required" => array(),"type" => array(),'aria-required' => array());
	$allowedposttags["select"] = array("name" => array(),"id" => array(),"value" => array(),"class" => array(),"required" => array(),"type" => array(),'aria-required' => array());
	$allowedposttags["textarea"] = array("name" => array(),"id" => array(),"value" => array(),"class" => array(),"placeholder" => array(),"required" => array(),"type" => array(),'aria-required' => array());
}

/**
 * Convert Hex Code to RGB
 * @param  string $hex Color Hex Code
 * @return array       RGB values
 */
 
function cactus_hex2rgb( $hex ) {
	if ( strpos( $hex,'rgb' ) !== FALSE ) {

		$rgb_part = strstr( $hex, '(' );
		$rgb_part = trim($rgb_part, '(' );
		$rgb_part = rtrim($rgb_part, ')' );
		$rgb_part = explode( ',', $rgb_part );

		$rgb = array($rgb_part[0], $rgb_part[1], $rgb_part[2], $rgb_part[3]);

	} elseif( $hex == 'transparent' ) {
		$rgb = array( '255', '255', '255', '0' );
	} else {

		$hex = str_replace( '#', '', $hex );
		
		
		if( strlen( $hex ) == 3 ) {
			$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
			$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
			$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
		} else {
			$r = hexdec( substr( $hex, 0, 2 ) );
			$g = hexdec( substr( $hex, 2, 2 ) );
			$b = hexdec( substr( $hex, 4, 2 ) );
		}
		$rgb = array( $r, $g, $b );
	}

	return $rgb;
}


/**
 * Mini cart
 */
 
function cactus_wcmenucart() {

	// Check if WooCommerce is active
	if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )  )
		return '';

	ob_start();
		global $woocommerce;
		$viewing_cart = __('View your shopping cart', 'cactus');
		$start_shopping = __('Start shopping', 'cactus');
		$cart_url = wc_get_cart_url();
		$shop_page_url = get_permalink( wc_get_page_id( 'shop' ) );
		$cart_contents_count = $woocommerce->cart->cart_contents_count;
		$cart_contents = sprintf(_n('%d item', '%d items', $cart_contents_count, 'cactus'), $cart_contents_count);
		$cart_total = $woocommerce->cart->get_cart_total();
		// Uncomment the line below to hide nav menu cart item when there are no items in the cart
		// if ( $cart_contents_count > 0 ) {
			if ($cart_contents_count == 0) {
				$menu_item = '<li class="right"><a class="wcmenucart-contents" href="'. $shop_page_url .'" title="'. $start_shopping .'">';
			} else {
				$menu_item = '<li class="right"><a class="wcmenucart-contents" href="'. $cart_url .'" title="'. $viewing_cart .'">';
			}

			$menu_item .= '<i class="fa fa-shopping-cart"></i> ';

			$menu_item .= $cart_contents.' - '. $cart_total;
			$menu_item .= '</a></li>';
		// Uncomment the line below to hide nav menu cart item when there are no items in the cart
		// }
		echo $menu_item;
	$social = ob_get_clean();
	return $social;

}

/**
 * Hide header & footer
 */
function cactus_hide_header(){
	if(isset($_GET['hide-header'])){
		return 1;
		}
	}

function cactus_hide_footer(){
	if(isset($_GET['hide-footer'])){
		return 1;
		}
	}
add_filter('cactus_hide_header','cactus_hide_header');
add_filter('cactus_hide_footer','cactus_hide_header');

/**
 * Menu items option
 */
add_action( 'init', 'cactus_setup_menu_custom_fields' );
function cactus_setup_menu_custom_fields() {

	$fields = array(
		'_mega_menu' => array(
			'label' => __( 'Mega Menu', 'cactus' ).' ',
			'element' => 'select',
	 		'sanitize_callback' => 'sanitize_text_field',
	  		'options' => array(
	 			'0' => __( 'No', 'cactus' ),
	 			'1' => __( 'Yes', 'cactus' ),
	 		),
			),
		);

		// Menu Management custom fields.
		new \Lucymtc\Menu( $fields );
}


class Cactus_Menu_Walker extends Walker_Nav_Menu {
	
	private $mega_menu;
	
	function start_lvl( &$output, $depth = 0, $args = array() ) {
    if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
        $t = '';
        $n = '';
    } else {
        $t = "\t";
        $n = "\n";
    }
    $indent = str_repeat( $t, $depth );
 
    // Default class.
    $classes = array( 'sub-menu' );
 
    /**
     * Filters the CSS class(es) applied to a menu list element.
     *
     */
    $class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
    $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
 	if( $this->mega_menu == '1' ){
    	$output .= "{$n}{$indent}<div class='cactus-mega-menu-wrap'><ul$class_names>{$n}";
	}
	else
		$output .= "{$n}{$indent}<ul$class_names>{$n}";

}


	function end_lvl( &$output, $depth = 0, $args = array() ) {
    if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
        $t = '';
        $n = '';
    } else {
        $t = "\t";
        $n = "\n";
    }
    $indent = str_repeat( $t, $depth );
	if( $this->mega_menu == '1' ){
    	$output .= "$indent</ul></div>{$n}";
		}
	else
		$output .= "$indent</ul>{$n}";
	
	$this->mega_menu = 0;
	
}

    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
    if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
        $t = '';
        $n = '';
    } else {
        $t = "\t";
        $n = "\n";
    }
    $indent = ( $depth ) ? str_repeat( $t, $depth ) : '';
 
    $classes = empty( $item->classes ) ? array() : (array) $item->classes;
    $classes[] = 'menu-item-' . $item->ID;
 
    /**
     * Filters the arguments for a single nav menu item.
     *
     */
    $args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );
 
    /**
     * Filters the CSS class(es) applied to a menu item's list item element.
     */
	 
	 
    $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
	
	$mega_menu = get_post_meta($item->ID,'_mega_menu',true);
	
	$this->mega_menu = 0;
	if( $mega_menu == '1' ){
		$class_names .= ' cactus-mega-menu';
		$this->mega_menu = 1;
	}
	
    $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
 
    /**
     * Filters the ID applied to a menu item's list item element.
     *
     */
    $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
    $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
	
 	
    $output .= $indent . '<li' . $id . $class_names .'>';
 
    $atts = array();
    $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
    $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
    $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
    $atts['href']   = ! empty( $item->url )        ? $item->url        : '';
 
    /**
     * Filters the HTML attributes applied to a menu item's anchor element.
     *
     */
    $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
 
    $attributes = '';
    foreach ( $atts as $attr => $value ) {
        if ( ! empty( $value ) ) {
            $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
            $attributes .= ' ' . $attr . '="' . $value . '"';
        }
    }
 
    /** This filter is documented in wp-includes/post-template.php */
    $title = apply_filters( 'the_title', $item->title, $item->ID );
 
    /**
     * Filters a menu item's title.
     */
    $title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );
 
    $item_output = $args->before;
    $item_output .= '<a'. $attributes .'>';
    $item_output .= $args->link_before . $title . $args->link_after;
    $item_output .= '</a>';
    $item_output .= $args->after;
 
    /**
     * Filters a menu item's starting output.
     *
     * @param stdClass $args        An object of wp_nav_menu() arguments.
     */
    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	
}

function end_el( &$output, $item, $depth = 0, $args = array() ) {
    if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
        $t = '';
        $n = '';
    } else {
        $t = "\t";
        $n = "\n";
    }
	
    $output .= "</li>{$n}"; 
}

}

function cactus_contact_form_email( $email ){
	
	$cactus_options = get_option(CACTUS_TEXTDOMAIN);
	
	if( isset($cactus_options['receiver_email_contact']) )
		return $cactus_options['receiver_email_contact'];
	else
		return $email;
	}
add_filter( 'vela_contact_form_email','cactus_contact_form_email' );