<?php
/*
Plugin Name: Rockon All in One
Plugin URI: https://wordpress.org/plugins/rockon-all-in-one/
Description: Rockon All in one plugin is provide to manage a breadcrumb, content limit, move script header to footer, add script on head or footer & enable disable the comment on wp-admin dashboard.
Version: 3.0
Author: Vikas Sharma
Author URI: https://profiles.wordpress.org/devikas301
License: GPLv2 or later
*/

define('RAIO_PATH', plugin_dir_path(__FILE__));
define('RAIO_LINK', plugin_dir_url(__FILE__));
define('RAIO_PLUGIN_NAME', plugin_basename(__FILE__));
define('RAIO_VERSION', '3.0');
define('RAIO_WP_VERSION', get_bloginfo('version'));

function raioLoadTextDomain() {
  load_plugin_textdomain( 'rockon-all-in-one' );
}
add_action( 'plugins_loaded', 'raioLoadTextDomain' );	

function raioCreateSubmenus() {
	require_once( RAIO_PATH . 'inc/raio_setting.php' );
}
add_action( 'raio_loaded', 'raioCreateSubmenus' );

require_once( RAIO_PATH . 'inc/raio_function.php' );	
	
function raioAddScriptHeader(){    
	$meta = get_option( 'raio_insert_header', '' );
	if($meta != ''){
		echo $meta, "\n";
	}

	$raio_post_meta = get_post_meta( get_the_ID(), 'raio_insert_header' , TRUE );
	if($raio_post_meta != ''){
		echo $raio_post_meta['raio_insert_header'], "\n";
	}
}

function raioAddScriptFooter(){
	if(!is_admin() && !is_feed() && !is_robots() && !is_trackback()){
		$raiotext = get_option( 'raio_insert_footer', '' );
		$raiotext = convert_smilies( $raiotext );
		$raiotext = do_shortcode( $raiotext );
			
		if( $raiotext != '' ){
		  echo $raiotext, "\n";
		}
	}
}

/***
*Content-Limit
***/
function raioContent($raio_atts){	
	$raio_args = shortcode_atts( array(
		'limit'     =>  '500'
    ), $raio_atts );	
	
	$raio_limit = $raio_args['limit'];
   
	$content = explode(' ', get_the_content(), $raio_limit);

  if (count($content)>=$raio_limit) {
    array_pop($content);
    $content = implode(" ",$content).'...';
  } else {
    $content = implode(" ",$content);
  }	
  $content = preg_replace('/[.+]/','', $content);
  $content = apply_filters('the_content', $content); 
  $content = str_replace(']]>', ']]&gt;', $content);
  return $content;
}
add_shortcode( 'rockon_content', 'raioContent' );  // [rockon_content limit=25]

/**
 * breadcrumbs Code
**/
function raioBreadcrumbs(){  
  $raio_divider = '&#62;';
  $bdc_sep = get_option('raio_bdc_separator');
  
  if(isset($bdc_sep) && !empty($bdc_sep)){
	$raio_divider = $bdc_sep;
  }  
  
    $home   = __('Home', 'bootstrapwp'); // text for the 'Home' link
    $before = '<li class="active">'; // tag before the current crumb
    $sep    = '<span class="divider">'.$raio_divider.'</span>';
    $after  = '</li>'; // tag after the current crumb

	if(!is_home() && !is_front_page() || is_paged()){
		
		global $post;		 
        echo '<ul class="raio-breadcrumb breadcrumb">';      

        $homeLink = home_url();

		echo '<li><a href="'.$homeLink.'">'.$home.'</a> '.$sep.'</li>';

		if(is_category()){

			global $wp_query;
				
			$cat_obj   = $wp_query->get_queried_object();
            $thisCat   = $cat_obj->term_id;
            $thisCat   = get_category($thisCat);
            $parentCat = get_category($thisCat->parent);
            if ($thisCat->parent != 0) {
				echo get_category_parents($parentCat, true, $sep);
            }

            echo $before . __('Archive by category', 'bootstrapwp') . ' "' . single_cat_title('', false) . '"' . $after;

		} elseif (is_day()) {

			echo '<li><a href="'.get_year_link(get_the_time('Y')).'">'.get_the_time('Y').'</a></li> ';
            echo '<li><a href="'.get_month_link(get_the_time('Y'), get_the_time('m')).'">'.get_the_time('F').'</a></li> ';
            echo $before.get_the_time('d').$after;

		} elseif (is_month()) {

			echo '<li><a href="'.get_year_link(get_the_time('Y')).'">'.get_the_time('Y').'</a></li> ';
            echo $before . get_the_time('F') . $after;

		} elseif (is_year()) {

			echo $before.get_the_time('Y').$after;

		} elseif (is_single() && !is_attachment()) {
				
			if (get_post_type() != 'post') {
					
				$post_type = get_post_type_object(get_post_type());
                $slug      = $post_type->rewrite;
                echo '<li><a href="'.$homeLink.'/'.$slug['slug'].'/">'.$post_type->labels->singular_name.'</a></li> ';
				echo $before . get_the_title() . $after;

			} else {

				$cat = get_the_category();
                $cat = $cat[0];
                echo '<li>'.get_category_parents($cat, true, $sep).'</li>';
                echo $before . get_the_title() . $after;

			}

		} elseif (!is_single() && !is_page() && get_post_type() != 'post' && !is_404()){

			$post_type = get_post_type_object(get_post_type());
			echo $before.$post_type->labels->singular_name . $after;

		} elseif (is_attachment()) {

			$parent = get_post($post->post_parent);
			$cat    = get_the_category($parent->ID);
			$cat    = $cat[0];
			echo get_category_parents($cat, true, $sep);
            echo '<li><a href="'.get_permalink($parent).'">'.$parent->post_title.'</a></li> ';
            echo $before . get_the_title() . $after;

		} elseif (is_page() && !$post->post_parent) {

			echo $before.get_the_title().$after;

		} elseif (is_page() && $post->post_parent) {

			$parent_id   = $post->post_parent;
			$breadcrumbs = array();

			while ($parent_id) {					
				$page          = get_page($parent_id);
                $breadcrumbs[] = '<li><a href="'.get_permalink($page->ID).'">'.get_the_title($page->ID).'</a>'.$sep.'</li>';
                $parent_id     = $page->post_parent;
			}

			$breadcrumbs = array_reverse($breadcrumbs);

			foreach ($breadcrumbs as $crumb) {
				echo $crumb;
			}

			echo $before.get_the_title().$after;

		} elseif (is_search()) {

			echo $before.__('Search results for', 'bootstrapwp').' "'.get_search_query().'"' . $after;

		} elseif (is_tag()) {

			echo $before.__('Posts tagged', 'bootstrapwp') . ' "'.single_tag_title('', false) . '"' . $after;

		} elseif (is_author()) {

			global $author;

			$userdata = get_userdata($author);

			echo $before.__('Articles posted by', 'bootstrapwp').' '.$userdata->display_name.$after;

		} elseif (is_404()) {
			echo $before . __('Error 404', 'bootstrapwp') . $after;
		}
		echo '</ul>';
	}
}
add_shortcode( 'rockon_breadcrumbs', 'raioBreadcrumbs');

function loadRaioWpAdminStyle() {
	wp_register_style( 'raio_wp_admin_css', RAIO_LINK.'assets/css/raio-admin-style.css', false, '3.0.0' );
    wp_enqueue_style( 'raio_wp_admin_css' );
}
add_action( 'admin_enqueue_scripts', 'loadRaioWpAdminStyle' );

//  Add RAIO Styles
function raioRegisterStyles(){
	wp_register_style('raiostyle1', RAIO_LINK. 'assets/css/raio-style.css');
    //wp_register_style('raiostyle2', RAIO_LINK. 'assets/css/styles.css');
    wp_enqueue_style('raiostyle1');
    // wp_enqueue_style('raiostyle2');
}
add_action('wp_print_styles', 'raioRegisterStyles');

//  Add RAIO Script
function raioRegisterScript() {
	wp_register_script('raioscript1', RAIO_LINK. 'assets/js/raio_script.js');
	//wp_register_script('raioscript2', RAIO_LINK.'assets/js/script.js');
    wp_enqueue_script('raioscript1');
    // wp_enqueue_script('raioscript2');
}
add_action('wp_print_scripts', 'raioRegisterScript');	
	
add_action( 'init', 'raioInitAllFunction');
function raioInitAllFunction(){	
	add_action('wp_head', 'raioAddScriptHeader');
	add_action('wp_footer', 'raioAddScriptFooter');	
}

/***comment-system***/
$chk_cdsetting = get_option('raio_comment_dc');
if (isset($chk_cdsetting) && $chk_cdsetting == 1) {	
	require_once(RAIO_PATH.'inc/raio_comment_fun.php');
}	
/***cs-end***/

add_action( 'init', 'raioscriptHf');
function raioScriptHf(){	
	$chk_shf = get_option('raio_act_hsf');
  if(isset($chk_shf) && $chk_shf == 1){	
	remove_action('wp_head', 'wp_print_scripts');
	remove_action('wp_head', 'wp_print_head_scripts', 9);
	remove_action('wp_head', 'wp_enqueue_scripts', 1);
	add_action('wp_footer', 'wp_print_scripts', 5);
	add_action('wp_footer', 'wp_enqueue_scripts', 5);
	add_action('wp_footer', 'wp_print_head_scripts', 5);			
  }	 
 }
?>