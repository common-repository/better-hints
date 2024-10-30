<?php
/**
 * @package   betterhints
 * @author    Stefan Böttcher
 *
 * @wordpress-plugin
 * Plugin Name: Better Hints for WordPress
 * Description: adds custom notification based on your conditions to your wordpress site
 * Version:     1.3.1
 * Author:      wp-hotline.com ~ Stefan
 * Author URI:  https://wp-hotline.com/m/better-hints-for-wordpress/
 * License: GPLv2 or later
 */


function betterhints_translate__( $string ) {

  return __( $string, 'betterhints' );

  //needs rework!
  if(function_exists('pll__')) {
    return pll__( $string );
  } else {
    return __( $string, 'betterhints' );
  }

}

function is_betterhints_pro() {
  $options = get_option('betterhints_options');
  $serial_status = $options["betterhints_serial_status"];
  if($serial_status=='active' or $serial_status=='pending') { return true; }
  return false;
}

if ( !class_exists( 'WooCommerce' ) ) {
  function is_product() {
      return is_singular( array( 'product' ) );
  }
}

function betterhints_get_userrole( $user = null ) {
	$user = $user ? new WP_User( $user ) : wp_get_current_user();
  //var_dumpp( $user->roles[0] );
	return $user->roles ? $user->roles[0] : false;
}

add_action( 'plugins_loaded', 'betterhints_load_textdomain' );
function betterhints_load_textdomain() {
  load_plugin_textdomain( 'betterhints', false, basename( dirname( __FILE__ ) ) . '/languages' );
}

add_action('wp_head', 'betterhints_add_scripts');
function betterhints_add_scripts()
{
    wp_enqueue_style( 'betterhints', plugins_url( '/css/style.css', __FILE__ ) );

    $css = "";

    $hint_color1 = get_theme_mod('hint_color1');
    //var_dumpp($hint_color1);
    $hint_color2 = get_theme_mod('hint_color2');
    $hint_color3 = get_theme_mod('hint_color3');
    $hint_color4 = get_theme_mod('hint_color4');

    $hint_pulse_color1 = get_theme_mod('hint_pulse_color1');
    $hint_pulse_color2 = get_theme_mod('hint_pulse_color2');

    $hint_animation_duration = get_theme_mod('hint_animation_duration');
    //var_dumpp( $hint_color3 );
    $customized_class = 'theme-default';
    if(!empty($hint_color1) or !empty($hint_color2) or !empty($hint_color3)) $customized_class = false;

    //$css = '';
    if(!empty( $hint_color1 )) {
    $css .= '#betterhints .betterhint { background: '.$hint_color1.';

      animation: colorchange '.$hint_animation_duration.'s;
      -webkit-animation: colorchange '.$hint_animation_duration.'s; /* Chrome and Safari */

      -webkit-animation-iteration-count: infinite;
     }
     ';
    }

    if(!empty( $hint_color1 ) && empty( $hint_color2 )) { $css .= '
    @keyframes colorchange  /* Safari and Chrome - necessary duplicate */
    {
      0% {background: '.$hint_color1.';}
      100% {background: '.$hint_color1.';}

    }
    @-webkit-keyframes colorchange  /* Safari and Chrome - necessary duplicate */
    {
      0% {background: '.$hint_color1.';}
      100% {background: '.$hint_color1.';}

    }
    '; }

    if(!empty( $hint_color2 ) && empty( $hint_color3 )) { $css .= '
    @keyframes colorchange
    {
      0% {background: '.$hint_color1.';}
      50% {background: '.$hint_color2.';}
      100% {background: '.$hint_color1.';}

    }
    @-webkit-keyframes colorchange /* Safari and Chrome - necessary duplicate */
    {
      0% {background: '.$hint_color1.';}
      50% {background: '.$hint_color2.';}
      100% {background: '.$hint_color1.';}

    }
    '; }

    if(!empty( $hint_color3 ) && empty( $hint_color4 )) { $css .= '
    @keyframes colorchange
    {
      0% {background: '.$hint_color1.';}
      33% {background: '.$hint_color2.';}
      66% {background: '.$hint_color3.';}
      100% {background: '.$hint_color1.';}
    }
    @-webkit-keyframes colorchange /* Safari and Chrome - necessary duplicate */
    {
      0% {background: '.$hint_color1.';}
      33% {background: '.$hint_color2.';}
      66% {background: '.$hint_color3.';}
      100% {background: '.$hint_color1.';}
    }
    '; }

    if(!empty( $hint_color4 )) { $css .= '
    @keyframes colorchange /* Safari and Chrome - necessary duplicate */
    {
      0% {background: '.$hint_color1.';}
      25% {background: '.$hint_color2.';}
      50% {background: '.$hint_color3.';}
      75% {background: '.$hint_color4.';}
      100% {background: '.$hint_color1.';}
    }
    @-webkit-keyframes colorchange /* Safari and Chrome - necessary duplicate */
    {
      0% {background: '.$hint_color1.';}
      25% {background: '.$hint_color2.';}
      50% {background: '.$hint_color3.';}
      75% {background: '.$hint_color4.';}
      100% {background: '.$hint_color1.';}
    }
    '; }

    if(!empty( $hint_pulse_color1 )) { $css .= '
      @-webkit-keyframes pulse {
        0% {
          -webkit-box-shadow: 0 0 0 0 rgba('.betterhints_hex2rgba($hint_pulse_color1).', 1);
        }
        70% {
            -webkit-box-shadow: 0 0 0 10px rgba('.betterhints_hex2rgba($hint_pulse_color1).', 0);
        }
        100% {
            -webkit-box-shadow: 0 0 0 0 rgba('.betterhints_hex2rgba($hint_pulse_color1).', 0);
        }
      }
      @keyframes pulse {
        0% {
          -moz-box-shadow: 0 0 0 0 rgba('.betterhints_hex2rgba($hint_pulse_color1).', 1);
          box-shadow: 0 0 0 0 rgba('.betterhints_hex2rgba($hint_pulse_color1).', 1);
        }

        10% {
          -moz-box-shadow: 0 0 0 0 rgba('.betterhints_hex2rgba($hint_pulse_color1).', .7);
          box-shadow: 0 0 0 0 rgba('.betterhints_hex2rgba($hint_pulse_color1).', .7);
        }

        70% {
            -moz-box-shadow: 0 0 0 10px rgba('.betterhints_hex2rgba($hint_pulse_color1).', 0);
            box-shadow: 0 0 0 10px rgba('.betterhints_hex2rgba($hint_pulse_color1).', 0);
        }
        100% {
            -moz-box-shadow: 0 0 0 0 rgba('.betterhints_hex2rgba($hint_pulse_color1).', 0);
            box-shadow: 0 0 0 0 rgba('.betterhints_hex2rgba($hint_pulse_color1).', 0);
        }
      }
    '; }

    wp_add_inline_style( 'betterhints', apply_filters('betterhints_css',$css) );

    wp_enqueue_style( 'wp-font', '//fonts.googleapis.com/css?family=Archivo+Narrow|Archivo+Black&amp;subset=latin-ext' );
    wp_enqueue_script( 'wp-js', plugins_url( '/js/script.js', __FILE__ ) );

    $hint_animation_popout_time = get_theme_mod('hint_animation_popout_time');

    wp_localize_script( 'wp-js', 'frontend_ajax_object',
        array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'hint_animation_popout_time' => $hint_animation_popout_time,
            'data_var_2' => 'value 2',
        )
    );

}

add_action('wp_footer', 'betterhints_add_to_footer');
function betterhints_add_to_footer()
{
    if ( !is_active_widget( false, false, 'betterhints_widget_area_id', true ) ) {
    	echo betterhints_html( array('bottom') );
    }
}

function betterhints_html( $classes = array() ) {

  $current_condition_agent = '';
  $current_condition = false;
  if(is_page()) $current_condition = 'is_page';
  if(is_singular()) $current_condition = 'is_single';
  if(is_archive()) $current_condition = 'is_archive';
  if(is_category()) $current_condition = 'is_category';
  if(is_product()) $current_condition = 'is_product';
  if(is_front_page()) $current_condition = 'is_front_page';
  //if(!wp_is_mobile())  $current_condition_agent = 'is_desktop';
  if(wp_is_mobile())  $current_condition_agent = 'is_mobile';
  if(betterhints_is_android())  $current_condition_agent = 'is_android';
  if(betterhints_is_ios())  $current_condition_agent = 'is_ios';
  if(betterhints_is_windows())  $current_condition_agent = 'is_windows';

  $current_condition = apply_filters( 'hint_current_condition', $current_condition );

  $meta_agent = false;

  $current_condition_value = false;
  $operator = "AND";
  $current_user_status = '';
  $user_role = betterhints_get_userrole();

  //var_dumpp(is_user_logged_in());
  if(is_user_logged_in()) $current_user_status = array($user_role, 'logged_in','registered');
  if(!is_user_logged_in()) $current_user_status = array('guest');

  if(is_single()) {
    $current_condition_value = get_the_ID();
    $operator = "OR";
  }

  $current_user = wp_get_current_user();

  $hints = get_betterhints_with_meta( $current_condition, get_the_ID(), $current_condition_agent, $current_user_status );
  if(!empty( $hints )) { //var_dumpp("einzelne id + agent + user_status überschreibt");
  } else {
    //var_dumpp("einzelne id + agent + user_status war es nicht");

  //var_dumpp( $hints );
  $hints = get_betterhints_with_meta( $current_condition, get_the_ID(), $current_condition_agent, '' );
  if(!empty( $hints )) {
    //var_dumpp("einzelne id + agent überschreibt");
  } else {

  $hints = get_betterhints_with_meta( $current_condition, get_the_ID(), '', $current_user_status );
  if(!empty( $hints )) { //var_dumpp("einzelne id ohne agent + user_status überschreibt");
  } else {

  $hints = get_betterhints_with_meta( $current_condition, get_the_ID(), '', '' );
  if(!empty( $hints )) {
    //var_dumpp("einzelne id ohne agent überschreibt");
  } else {

  $hints = get_betterhints_with_meta( $current_condition, '', $current_condition_agent, $current_user_status );
  if(!empty( $hints )) {
    //var_dumpp("current_condition mit current_condition_agent + user_status");
  } else {

  $hints = get_betterhints_with_meta( $current_condition, '', $current_condition_agent, '' );
  if(!empty( $hints )) {
    //var_dumpp("current_condition mit current_condition_agent");
  } else {

  $hints = get_betterhints_with_meta( $current_condition, '', '', false );
  if(!empty( $hints )) {
    //var_dumpp("current_condition ohne current_condition_agent");
  } else {

  $hints = get_betterhints_with_meta( '', '', $current_condition_agent, false );
  if(!empty( $hints )) {
    //var_dumpp("nur current_condition_agent");
  } else {

  }
  }
  }
  }
  }
  }
  }
  }
  //schleifenende
  //  var_dumpp( $hints );
    $html = '';
    if(!empty( $hints )) {
      foreach( $hints as $hint ) {

        $classes2 = array();
        //var_dumpp( $hint->post_title );
        $hint->url = get_post_meta( $hint->ID, '_hint_url', true );
        $hint->post_content = get_post_meta( $hint->ID, '_hint_post_content', true );
        ////var_dumpp( $hints[0]->url );
        //admin msg

        $url = wp_get_attachment_image_src( get_post_thumbnail_id( $hint->ID ), 'medium' )[0];
        $style = '';
        if($url) { $style = ' style="background-image:url('.$url.')"'; $classes2[] = 'has_image'; }
        if($hint->url) { $classes2[] = 'has_link'; }
        //var_dumpp( $url );
        //if( current_user_can('administrator') ) { $html .= '<hr /><small>admin view: $current_condition: '.$current_condition.'</small>'; }

        $html .= '<div class="betterhint '.implode(' ', $classes2).'" data-id="'.$hint->ID.'" data-url="'.$hint->url.'"><div class="betterhint-content pulsate" '. $style .'>'.$hint->post_title.'<p>'.$hint->post_content.'</p></div></div>';
    }


    } else {

      if( current_user_can('administrator') && !get_theme_mod('hint_hide_admin_hint') ) { $html .= '<div class="betterhint has_link" data-url="'.get_admin_url( null, 'post-new.php?post_type=hint').'"><div class="betterhint-content pulsate">'.betterhints_translate__('This is an admin-hint just for you.').' <a href="'.get_admin_url( null, 'post-new.php?post_type=hint').'">'.betterhints_translate__('Create one here!').'</a></div></div>'; }
    }

    if(!empty($current_condition)) $classes[] = 'betterhints_'.$current_condition;
    $html = '<div id="betterhints" class="'.implode(' ', apply_filters( 'betterhints_classes', $classes ) ) .'">'.$html.'</div>';
    return $html;
}

function get_betterhints_with_meta( $condition, $id, $condition_agent, $user_status ) {


  $meta =
  array(
    'relation' => 'AND',
    array(
          'key'     => '_hint_condition1',
          'value'   => array( $condition ),
          'compare' => 'IN'
    ),

  );

  if($user_status!==false) {
    $meta2 = array(array(
          'key'     => '_hint_condition1_user_role',
          'value'   => $user_status,
          'compare' => 'IN'
    ));

    $meta = array_merge($meta,$meta2);
  }


  if($id!==false) {
    $meta2 = array(array(
          'key'     => '_hint_condition1_value',
          'value'   => array( $id ),
          'compare' => 'IN'
    ));

    $meta = array_merge($meta,$meta2);
  }


  //if(empty( $meta )) { $meta =  array(); }

  if( $condition_agent === false ) {
    $meta_agent =       array(array(
              'relation' => 'OR',
              array(
                    'key'     => '_hint_condition1_agent',
                    'compare' => 'NOT EXISTS'
              ),
              array(
                    'key'     => '_hint_condition1_agent',
                    'value'   => '',
                    'compare' => '='
              ),
          ));

$meta_agent = array();

  } else {
    $meta_agent =       array(
            'relation' => 'AND',
              array(
                    'key'     => '_hint_condition1_agent',
                    'value'   => array($condition_agent),
                    'compare' => 'IN',
              ),

          );
  }

  $meta = array_merge($meta,$meta_agent);

  //var_dumpp($condition);
  //var_dumpp($meta);

  $args = array( 'post_type'=>'hint', 'posts_per_page'=>10, 'orderby2' => 'rand', 'meta_query' => $meta );
  //$args = array_merge($args, $meta);
  $posts = get_posts( $args );
  //var_dumpp( $args );
  //var_dumpp( $posts );
  return $posts;

}

add_action( 'init', 'betterhints_create_post_type' );
function betterhints_create_post_type() {
  register_post_type( 'hint',
    array(
      'labels' => array(
        'name' => betterhints_translate__('Hints'),
        'singular_name' => betterhints_translate__('Hint'),
        'add_new_item'  => betterhints_translate__('Add new Hint')
      ),
      'public' => false,  // it's not public, it shouldn't have it's own permalink, and so on
      'publicly_queryable' => true,  // you should be able to query it
      'show_ui' => true,  // you should be able to edit it in wp-admin
      'exclude_from_search' => true,  // you should exclude it from search results
      'show_in_nav_menus' => false,  // you shouldn't be able to add it to menus
      'has_archive' => false,  // it shouldn't have archive page
      'rewrite' => false,  // it shouldn't have rewrite rules
      'supports' => array('title','thumbnail')
    )
  );
}

add_action( 'add_meta_boxes', 'betterhints_add_meta_box' );
function betterhints_add_meta_box() {
  add_meta_box(
    'custom_post_metabox',
    betterhints_translate__('Hint'),
    'betterhints_display_meta_box',
    'hint',
    'normal',
    'high'
  );
}

function betterhints_display_meta_box( $post ) {

global $is_chrome;

wp_nonce_field( plugin_basename(__FILE__), 'hint_nonce_field' );

$hint_url = get_post_meta( $post->ID, '_hint_url', true );
$hint_condition1 = get_post_meta( $post->ID, '_hint_condition1', true );
$hint_condition1_value = get_post_meta( $post->ID, '_hint_condition1_value', true );
$hint_condition1_operator = get_post_meta( $post->ID, '_hint_condition1_operator', true );
$hint_condition1_agent = get_post_meta( $post->ID, '_hint_condition1_agent', true );
$hint_condition1_user_role = get_post_meta( $post->ID, '_hint_condition1_user_role', true );
$hint_post_content = get_post_meta( $post->ID, '_hint_post_content', true );

//var_dumpp( $hint_condition1_agent );

$html .= '<br />'.betterhints_translate__('URL').': <input type="url" name="hint_url" style="width:80%;" value="' . $hint_url . '" placeholder="https://" />';
$html .= '<br /><br />';
$settings = array( 'media_buttons' => false, 'editor_height' => 100 );

wp_editor( $hint_post_content, 'hint_post_content', $settings );

$is_shop_string = 'is_shop'; $is_shop_class = '';
if(!is_betterhints_pro()) { $is_shop_string = pll__('Pro') . ': is_shop';  $is_shop_class = 'disabled'; }
$is_product_category_string = 'is_product_category'; $is_product_category_class = '';
if(!is_betterhints_pro()) { $is_product_category_string = pll__('Pro') . ': is_product_category';  $is_product_category_class = 'disabled'; }
$is_cart_string = 'is_cart'; $is_cart_class = '';
if(!is_betterhints_pro()) { $is_cart_string = pll__('Pro') . ': is_cart'; $is_cart_class = 'disabled'; }
$is_checkout_string = 'is_checkout'; $is_checkout_class = '';
if(!is_betterhints_pro()) { $is_checkout_string = pll__('Pro') . ': is_checkout'; $is_checkout_class = 'disabled'; }

$is_android_string = 'is_android'; $is_android_class = '';
if(!is_betterhints_pro()) { $is_android_string = pll__('Pro') . ': is_android'; $is_android_class = 'disabled'; }
$is_ios_string = 'is_ios'; $is_ios_class = '';
if(!is_betterhints_pro()) { $is_ios_string = pll__('Pro') . ': is_ios'; $is_ios_class = 'disabled'; }
$is_ipad_string = 'is_ipad'; $is_ipad_class = '';
if(!is_betterhints_pro()) { $is_ipad_string = pll__('Pro') . ': is_ipad'; $is_ipad_class = 'disabled'; }
$is_windows_string = 'is_windows'; $is_windows_class = '';
if(!is_betterhints_pro()) { $is_windows_string = pll__('Pro') . ': is_windows'; $is_windows_class = 'disabled'; }

$html .= '<select name="hint_condition1">

<option value=""> </option>
<option value="is_archive" '.selected( $hint_condition1, 'is_archive', false ).'>is_archive</option>
<option value="is_page" '.selected( $hint_condition1, 'is_page', false ).'>is_page</option>
<option value="is_singular" '.selected( $hint_condition1, 'is_singular', false ).'>is_singular</option>
<option value="is_product" '.selected( $hint_condition1, 'is_product', false ).'>is_product</option>
<option value="is_front_page" '.selected( $hint_condition1, 'is_front_page', false ).'>is_front_page</option>
<option value="is_shop" '.selected( $hint_condition1, 'is_shop', false ).' '. $is_shop_class .'>'. $is_shop_string .'</option>
<option value="is_product_category" '.selected( $hint_condition1, 'is_product_category', false ).' '. $is_product_category_class .'>'. $is_product_category_string .'</option>
<option value="is_cart" '.selected( $hint_condition1, 'is_cart', false ).' '. $is_cart_class .'>'. $is_cart_string .'</option>
<option value="is_checkout" '.selected( $hint_condition1, 'is_checkout', false ).' '.$is_checkout_class.'>'.$is_checkout_string.'</option>
</select>';

$html2 .= '<select name="hint_condition1_operator">
          <option value="">=</option>
          <option value="!=" '.selected( $hint_condition1_operator, '!=', false ).'>!=</option>
          <option value=">=">>=</option>
          <option value="<="><=</option>
          </select>';

$html .= '<input type="text" name="hint_condition1_value" value="' . $hint_condition1_value . '" placeholder="'.betterhints_translate__('empty or ID').'" />';

$html .= '<select name="hint_condition1_agent">
          <option value="">desktop & mobile</option>
          <!--<option value="is_desktop" '.selected( $hint_condition1_agent, 'is_desktop', false ).'>desktop</option>-->
          <option value="is_mobile" '.selected( $hint_condition1_agent, 'is_mobile', false ).'>mobile</option>
          <option value="is_desktop" '.selected( $hint_condition1_agent, 'is_android', false ).' '.$is_android_class.'>'.$is_android_string.'</option>
          <option value="is_ios" '.selected( $hint_condition1_agent, 'is_ios', false ).' '.$is_ios_class.'>'.$is_ios_string.'</option>
          <option value="is_ipad" '.selected( $hint_condition1_agent, 'is_ipad', false ).' '.$is_ipad_class.'>'.$is_ipad_string.'</option>
          <option value="is_windows" '.selected( $hint_condition1_agent, 'is_windows', false ).' '.$is_windows_class.'>'.$is_windows_string.'</option>

          </select>';

$r = '';
$editable_roles = array_reverse( get_editable_roles() );

  foreach ( $editable_roles as $role => $details ) {
  $name = translate_user_role($details['name'] );
  // preselect specified role
  if ( $hint_condition1_user_role == $role ) {
    $r .= "\n\t<option selected='selected' value='" . esc_attr( $role ) . "'>$name</option>";
  } else {
        $r .= "\n\t<option value='" . esc_attr( $role ) . "'>$name</option>";
  }
}

$html .= ' '.betterhints_translate__('and').' <select name="hint_condition1_user_role">
          <option value="">'.betterhints_translate__('all').'</option>
          <option value="guest" '.selected( $hint_condition1_user_role, 'guest', false ).'>'.betterhints_translate__('guest').'</option>
          <option value="logged_in" '.selected( $hint_condition1_user_role, 'logged_in', false ).'>'.betterhints_translate__('logged in').'</option>
          <option value="registered" '.selected( $hint_condition1_user_role, 'registered', false ).'>'.betterhints_translate__('registered').'</option>

          '. $r .'
          </select> '.betterhints_translate__('User');

//var_dumpp( get_post_custom() );
echo $html;

}

function betterhints_save_meta_box_data( $post_id ) {

if ( betterhints_save_post( $post_id, 'hint_nonce_field' ) ) {

  $hint_url = sanitize_text_field( $_POST['hint_url'] );
  $hint_condition1 = sanitize_text_field( $_POST['hint_condition1'] );
  $hint_condition1_value = sanitize_text_field( $_POST['hint_condition1_value'] );
  $hint_condition1_operator = sanitize_text_field( $_POST['hint_condition1_operator'] );
  $hint_condition1_agent = sanitize_text_field( $_POST['hint_condition1_agent'] );
  $hint_condition1_user_role = sanitize_text_field( $_POST['hint_condition1_user_role'] );
  $hint_post_content = wp_kses_post( $_POST['hint_post_content'] );

  update_post_meta( $post_id, 'post_content', $hint_url );
  update_post_meta( $post_id, '_hint_url', $hint_url );
  update_post_meta( $post_id, '_hint_condition1', $hint_condition1 );
  update_post_meta( $post_id, '_hint_condition1_value', $hint_condition1_value );
  update_post_meta( $post_id, '_hint_condition1_operator', $hint_condition1_operator );
  update_post_meta( $post_id, '_hint_condition1_agent', $hint_condition1_agent );
  update_post_meta( $post_id, '_hint_condition1_user_role', $hint_condition1_user_role );
  update_post_meta( $post_id, '_hint_post_content', $hint_post_content );

}

}

add_action( 'save_post', 'betterhints_save_meta_box_data' );

function betterhints_save_post( $post_id, $nonce ) {

  $is_autosave = wp_is_post_autosave( $post_id );
  $is_revision = wp_is_post_revision( $post_id );
  $is_valid_nonce = ( isset( $_POST[ $nonce ] ) && wp_verify_nonce( $_POST [ $nonce ], plugin_basename( __FILE__ ) ) );
  return ! ( $is_autosave || $is_revision ) && $is_valid_nonce;

}

// add conditional statements
function betterhints_is_windows() { // if the user is on an iPod Touch
	$cn_is_windows = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'Windows');
	if ($cn_is_windows)
		return true;
	else return false;
}
function betterhints_is_ipad() { // if the user is on an iPad
	$is_ipad = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPad');
	if ($is_ipad)
		return true;
	else return false;
}
function betterhints_is_iphone() { // if the user is on an iPhone
	$cn_is_iphone = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPhone');
	if ($cn_is_iphone)
		return true;
	else return false;
}
function betterhints_is_ipod() { // if the user is on an iPod Touch
	$cn_is_iphone = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPod');
	if ($cn_is_iphone)
		return true;
	else return false;
}
function betterhints_is_ios() { // if the user is on any iOS Device
	if (betterhints_is_iphone() || betterhints_is_ipad() || betterhints_is_ipod())
		return true;
	else return false;
}
function betterhints_is_android() { // detect ALL android devices
	$is_android = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'Android');
	if ($is_android) return true;
	return false;
}
function betterhints_is_android_mobile() { // detect only Android phones
	$is_android   = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'Android');
	$is_android_m = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'Mobile');
	if ($is_android && $is_android_m)
		return true;
	else return false;
}
function betterhints_is_android_tablet() { // detect only Android tablets
	if (betterhints_is_android() && !betterhints_is_android_mobile())
		return true;
	else return false;
}
function betterhints_is_mobile_device() { // detect Android Phones, iPhone or iPod
	if (betterhints_is_android_mobile() || betterhints_is_iphone() || betterhints_is_ipod())
		return true;
	else return false;
}
function betterhints_is_tablet() { // detect Android Tablets and iPads
	if ((betterhints_is_android() && !betterhints_is_android_mobile()) || betterhints_is_ipad())
		return true;
	else return false;
}

/*
$is_iphone (boolean) iPhone Safari
$is_chrome (boolean) Google Chrome
$is_safari (boolean) Safari
$is_NS4 (boolean) Netscape 4
$is_opera (boolean) Opera
$is_macIE (boolean) Mac Internet Explorer
$is_winIE (boolean) Windows Internet Explorer
$is_gecko (boolean) FireFox
$is_lynx (boolean)
$is_IE (boolean) Internet Explorer
$is_edge (boolean) Microsoft Edge
*/

add_action( 'wp_ajax_betterhints_addHintClick', 'betterhints_addHintClick' );
add_action( 'wp_ajax_nopriv_betterhints_addHintClick', 'betterhints_addHintClick' );

function betterhints_addHintClick() {
	global $wpdb;
  $id = intval( $_POST['ID'] );


  if( $id ) {

    $clicks = get_post_meta( $id, '_hint_clicks', true);
    update_post_meta( $id, '_hint_clicks', sanitize_text_field( $clicks + 1 ) );
    $data["ID"] = $id;
    $data["success"] = true;

  } else {
    $data["success"] = false;
  }

	echo json_encode( $data );

  //var_dumpp( $_POST );
	wp_die();
}

add_action("manage_hint_posts_custom_column",  "betterhints_custom_columns");
add_filter("manage_edit-hint_columns", "betterhints_request_edit_columns");

function betterhints_request_edit_columns($columns){

  if( is_betterhints_pro() ) { $clickstring = betterhints_translate__("Clicks"); } else { $clickstring = betterhints_translate__("Clicks") .' '. " - <a href='https://wp-hotline.com/m/better-hints-for-wordpress-pro/' target='_blank'>".betterhints_translate__("only Pro")."</a>"; }

  $columns = array(
    "cb" => "<input type=\"checkbox\" />",
    "title2" => "",
    "condition" => betterhints_translate__("Conditional Logic"),
    "link" => betterhints_translate__("Target-Link"),
    "clicks" => $clickstring,
    "status" => "",
  );

  return $columns;
}

add_filter( 'manage_edit-hint_sortable_columns', 'betterhints_sortable_hint_column' );
function betterhints_sortable_hint_column( $columns ) {

    $columns['status'] = 'status';
    $columns['condition'] = 'condition';

    if( is_betterhints_pro() ) {
      $columns['clicks'] = 'clicks';
    }

    //unset if needed
    //unset($columns['date']);

    return $columns;
}

function betterhints_custom_columns($column){
  global $post;

  switch ($column) {
    case "title2":
        //$clicks = (int) get_post_meta( $post->ID, '_hint_clicks', true );
        $hint_url = get_post_meta( $post->ID, '_hint_url', true );
        if(!empty( $hint_url )) {
            $title = '<a href="'.$hint_url.'" title="'.$hint_url.'" target="_blank">'.$post->post_title.'</a> ';
        } else {
            //$hint_html = '<span>no Link</span><br />';
            $title = $post->post_title;
        }

        $hint_edit_html = '<a href="'.get_edit_post_link( $post->ID ).'">'.betterhints_translate__('Edit').'</a>';

        echo '<h4 style="font-size: 1.1em; margin:0 0 5px 0;padding:0;">'.$title.' </h4>   '.$hint_edit_html;
			break;
      case "status":

          $user_role = get_post_meta( $post->ID, '_hint_condition1_user_role', true );
          if(empty( $user_role )) { $user_role = betterhints_translate__('all'); }
          $status = get_post_status( $post->ID );
          switch($status) {
            case 'publish':
            $status_html = '<span class="dashicons dashicons-welcome-view-site"></span> '.$user_role;

            break;
            case 'draft':
            $status_html = '<span class="dashicons dashicons-welcome-view-site"></span> ' .betterhints_translate__('when published').' '.$user_role;
            break;
            default:
            $status_html = '';
          }
          echo '<small>'.$status_html.'</small >';
  			break;
		case "clicks":
        $clicks = (int) get_post_meta( $post->ID, '_hint_clicks', true );
        if( is_betterhints_pro() ) { echo ''.$clicks; } else { echo '<a href="https://wp-hotline.com/m/better-hints-for-wordpress-pro/" target="_blank" style="color:#f90;"><span class="dashicons dashicons-hidden"></span></a>'; }
			break;
      case "link":
        $hint_url = get_post_meta( $post->ID, '_hint_url', true );
        if(!empty( $hint_url )) {
            $hint_html = '<strong><a href="'.$hint_url.'" title="'.$hint_url.'" target="_blank">'.$hint_url.'</a></strong><br />';
        } else {
            $hint_html = '<span style="color:#f90">'.betterhints_translate__('no Link').'</span><br />';
        }
        echo $hint_html;
  			break;
      case "condition":

          $hint_condition1_html = '';

          $hint_condition1 = get_post_meta( $post->ID, '_hint_condition1', true );
          $hint_condition1_agent = get_post_meta( $post->ID, '_hint_condition1_agent', true );
          $hint_condition1_value = get_post_meta( $post->ID, '_hint_condition1_value', true );

          $hint_condition1_html = $hint_condition1;

          $hint_condition1_html .= '';
          if(!empty( $hint_condition1_value )) $hint_condition1_html .= ': <a target="_blank" href="'.get_permalink($hint_condition1_value).'">'.$hint_condition1_value.'</a>';
          if( !empty( $hint_condition1 ) && !empty( $hint_condition1_agent )) $hint_condition1_html .= '<br />+ ';
          if(!empty( $hint_condition1_agent )) $hint_condition1_html .= ''.$hint_condition1_agent.'';

          //if(!empty( $hint_condition1_agent )) $hint_condition1_html .= '<br />& '.$hint_condition1_agent.'';
          echo $hint_condition1_html;
  			break;
    }
}

add_action( 'pre_get_posts', 'betterhints_orderby' );
function betterhints_orderby( $query ) {
    if( ! is_admin() )
        return;

    $orderby = $query->get( 'orderby');

    if( 'clicks' == $orderby ) {
        $query->set('meta_key','_hint_clicks');
        $query->set('orderby','meta_value_num');
    }

    if( 'condition' == $orderby ) {
        $query->set('meta_key','_hint_condition1');
        $query->set('orderby','meta_value');
    }
}

add_action('customize_register','betterhints_customizer_options');
/*
 * Add in our custom Accent Color setting and control to be used in the Customizer in the Colors section
 *
 */
function betterhints_customizer_options( $wp_customize ) {

  $wp_customize->add_section( 'betterhints_section_main' , array(
      'title'      => betterhints_translate__( 'Hints' ),
      'priority'   => 50,
  ) );

  $wp_customize->add_setting( 'hint_color1', array( 'default' => '', 'sanitize_callback' => 'sanitize_hex_color', ) );
  $wp_customize->add_control(
   new WP_Customize_Color_Control(
   $wp_customize,
   'hint_color1', //give it an ID
   array(
   'label' => betterhints_translate__( 'Hint Background-Color 1' ), //set the label to appear in the Customizer
   'section' => 'betterhints_section_main', //select the section for it to appear under
   'capability'     => 'edit_theme_options',
   'settings' => 'hint_color1' //pick the setting it applies to
   )
   )
  );

  $wp_customize->add_setting( 'hint_color2', array( 'default' => '', 'sanitize_callback' => 'sanitize_hex_color', ) );
  $wp_customize->add_control(
   new WP_Customize_Color_Control(
   $wp_customize,
   'hint_color2', //give it an ID
   array(
   'label' => betterhints_translate__( 'Hint Background-Color 2' ), //set the label to appear in the Customizer
   'section' => 'betterhints_section_main', //select the section for it to appear under
   'settings' => 'hint_color2' //pick the setting it applies to
   )
   )
  );

  $wp_customize->add_setting( 'hint_color3', array( 'default' => '', 'sanitize_callback' => 'sanitize_hex_color', ) );
  $wp_customize->add_control(
   new WP_Customize_Color_Control(
   $wp_customize,
   'hint_color3', //give it an ID
   array(
   'label' => betterhints_translate__( 'Hint Background-Color 3' ), //set the label to appear in the Customizer
   'section' => 'betterhints_section_main', //select the section for it to appear under
   'settings' => 'hint_color3' //pick the setting it applies to
   )
   )
  );

  $wp_customize->add_setting( 'hint_color4', array( 'default' => '', 'sanitize_callback' => 'sanitize_hex_color', ) );
  $wp_customize->add_control(
   new WP_Customize_Color_Control(
   $wp_customize,
   'hint_color4', //give it an ID
   array(
   'label' => betterhints_translate__( 'Hint Background-Color 4' ), //set the label to appear in the Customizer
   'section' => 'betterhints_section_main', //select the section for it to appear under
   'settings' => 'hint_color4' //pick the setting it applies to
   )
   )
  );

  $wp_customize->add_setting( 'hint_pulse_color1', array( 'default' => '', 'sanitize_callback' => 'sanitize_hex_color', ) );
  $wp_customize->add_control(
   new WP_Customize_Color_Control(
   $wp_customize,
   'hint_pulse_color1', //give it an ID
   array(
   'label' => betterhints_translate__( 'Hint Pulse-Color 1' ), //set the label to appear in the Customizer
   'section' => 'betterhints_section_main', //select the section for it to appear under
   'settings' => 'hint_pulse_color1' //pick the setting it applies to
   )
   )
  );


  $wp_customize->add_setting( 'hint_animation_duration', array( 'default' => '', 'sanitize_callback' => 'betterhints_sanitize_number', ) );
  $wp_customize->add_control( 'hint_animation_duration', array(
      'type'        => 'range',
      'priority'    => 10,
      'section'     => 'betterhints_section_main',
      'label'       => betterhints_translate__('Hint Animation: Duration'),
      'description' => '',
      'input_attrs' => array(
          'min'   => 1,
          'max'   => 120,
          'step'  => 1,
          'class' => 'test-class test',
          'style' => 'color: #0a0',
      ),
  ) );

  $wp_customize->add_setting( 'hint_animation_popout_time', array( 'default' => '', 'sanitize_callback' => 'betterhints_sanitize_number', ) );
  $wp_customize->add_control( 'hint_animation_popout_time', array(
      'type'        => 'range',
      'priority'    => 10,
      'section'     => 'betterhints_section_main',
      'label'       => betterhints_translate__('Hint Animation: Popout-Time'),
      'description' => '',
      'input_attrs' => array(
          'min'   => 500,
          'max'   => 30000,
          'step'  => 500,
          'class' => 'test-class test',
          'style' => 'color: #0a0',
      ),
  ) );

  $wp_customize->add_setting( 'hint_hide_admin_hint', array(
      'capability' => 'edit_theme_options'
  ) );

  // Add control and output for select field
  $wp_customize->add_control( 'hint_hide_admin_hint', array(
      'label'      => betterhints_translate__( 'hide example hint for administrators' ),
      'section'    => 'betterhints_section_main',
      'settings'    => 'hint_hide_admin_hint',
      'type'       => 'checkbox'
  ) );

  $hint = get_post_type_object('hint')->labels;
  $wp_customize->add_setting( 'hint_buy_now', array( 'default' => '', ) );
  $wp_customize->add_control( 'hint_buy_now', array(
      'type'        => 'text',
      'priority'    => 10,
      'section'     => 'betterhints_section_main',
      'label'       => '',
      'description' => '<a href="'.get_admin_url( null, 'post-new.php?post_type=hint') .'" target="_blank">'.$hint->add_new_item.'</a>',
      'input_attrs' => array(
          'min'   => 500,
          'max'   => 30000,
          'step'  => 500,
          'class' => 'hide hidden',
          'style' => 'color: #0a0',
      ),
  ) );

}
function betterhints_sanitize_number( $input ) {
    return absint($input);
}

add_filter( 'pll_get_post_types', 'betterhints_add_cpt_to_pll', 10, 2 );

function betterhints_add_cpt_to_pll( $post_types, $is_settings ) {
    if ( $is_settings ) {
        // hides 'my_cpt' from the list of custom post types in Polylang settings
        unset( $post_types['hint'] );
    } else {
        // enables language and translation management for 'my_cpt'
        $post_types['hint'] = 'hint';
    }
    return $post_types;
}


// Register and load the widget
function betterhints_load_widget_area() {
    register_widget( 'betterhints_widget_area' );
}
add_action( 'widgets_init', 'betterhints_load_widget_area' );

// Creating the widget
class betterhints_widget_area extends WP_Widget {

  function __construct() {
    parent::__construct(

    // Base ID of your widget
    'betterhints_widget_area_id',

    // Widget name will appear in UI
    betterhints_translate__('Hint Widget'),

    // Widget description
    array( 'description' => betterhints_translate__( 'This widget holds all your hints in one place.' ), )
    );
  }

  // Creating widget front-end

  public function widget( $args, $instance ) {
    echo betterhints_html();
  }

  // Widget Backend
  public function form( $instance ) {
  ?>
    <p><?php echo betterhints_translate__('This widget holds all your hints in one place.'); ?></p>
    <?php
  }

  // Updating widget replacing old instances with new
  public function update( $new_instance, $old_instance ) {
    $instance = array();
    #$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
    return $new_instance;
  }
} // Class wpb_widget ends here

add_action('admin_menu', 'betterhints_submenu_page');

function betterhints_submenu_page() {

  if(is_betterhints_pro()) {
    add_submenu_page(
        'edit.php?post_type=hint',
        '',
        betterhints_translate__('Need Support?'),
        'manage_options',
        'options',
        'betterhints_options_page' );
  } else {
    add_submenu_page(
        'edit.php?post_type=hint',
        '',
        betterhints_translate__('Activate Pro'),
        'manage_options',
        'options',
        'betterhints_options_page' );
  }

}

function betterhints_options_page() {

	$options = get_option('betterhints_options');
  $serial = $options["betterhints_serial"];

  //var_dumpp( betterhints_check_serial($serial) );
  //var_dumpp( $options );

?>
	<div class="wrap">

	<form action="options.php" method="post">

    <?php if(!is_betterhints_pro()) { ?>
    <div class="notice notice-warning">
      <p><?php echo betterhints_translate__('You serial key is not active.'); ?> <a href="https://wp-hotline.com/m/better-hints-for-wordpress-pro/" target="_blank"><?php echo betterhints_translate__('Get one here!'); ?></a></p>
    </div>
    <?php } else { ?>
      <div class="notice notice-success">
        <p><?php echo betterhints_translate__('You serial key is active!'); ?> <a href="https://wp-hotline.com/book-2h-plugin-support/?plugin_name=Hints+for+WordPress" target="_blank"><?php echo betterhints_translate__('Need Support?'); ?></a></p>
      </div>
    <?php } ?>

	<?php settings_fields('betterhints_options'); ?>
	<?php do_settings_sections('betterhints_serial'); ?>
	<input type="text" class="large-text" style="padding: 1em;" id='betterhints_serial' name='betterhints_options[betterhints_serial]' value="<?php echo $serial; ?>" placeholder="serial key" />

	<button name="Submit" type="submit" value="true" class="button button-primary button-large"><?php echo betterhints_translate__('Check'); ?></button> <br /><br /><small><?php echo betterhints_translate__('last checked:'); ?> <?php echo $options["betterhints_serial_last_checked"]; ?></small>
	</form>

	</div>

<?php
}

add_action('admin_init', 'betterhints_admin_init');
function betterhints_admin_init(){
register_setting( 'betterhints_options', 'betterhints_options', 'betterhints_options_validate' );
#add_settings_section('betterhints_serial', false, false, 'plugin');
#add_settings_field('betterhints_text_string', 'current content', 'betterhints_setting_string', 'plugin', 'betterhints_main');
}

function betterhints_setting_string() {
#$options = get_option('betterhints_options');
#echo "<textarea id='betterhints_text_string' name='betterhints_options[text_string]' size='40' type='text' value='{$options['text_string']}' />";
}

// validate our options
function betterhints_options_validate($input) {

$newinput['betterhints_serial'] = sanitize_html_class($input['betterhints_serial']);
$newinput['betterhints_serial_last_checked'] = current_time('mysql');
$serial = betterhints_check_serial( $newinput['betterhints_serial'] );

if( $serial ) {
  $newinput['betterhints_serial_status'] = $serial->status;
  $newinput['betterhints_serial_daystoexpire'] = $serial->days;
  $newinput['betterhints_serial_date_expiry'] = $serial->date_expiry;
} else {
  $newinput['betterhints_serial_status'] = 'expired';
  $newinput['betterhints_serial_date_expiry'] = current_time('mysql');
}

return $newinput;
}

function betterhints_get_domain( $url ) {

  $parse = parse_url( esc_url_raw($url) );
  if(!empty($parse['host'])) return $parse['host'];
  return false;
}

function betterhints_check_serial( $serial ) {

  $api_params = array(
    'slm_action' => 'slm_check',
    'secret_key' => '5a1d53facb5c54.63038765',
    'license_key' => $serial,
    'domain' => betterhints_get_domain( get_option('home') ),
  );


  // Send query to the license manager server
  $response = wp_remote_get(add_query_arg($api_params, 'https://wp-hotline.com'), array('timeout' => 20));
  //var_dumpp( json_decode($response["body"]) );
  //var_dumpp(($response->result);
  $body = json_decode($response["body"]);
  //var_dumpp( $body );
  if( $body->status == 'active' or $body->status == 'pending') {
    $body->days = 10;
    if($body->status == 'active') { $body->days = 60; }
    return $body;
  } else {
    return false;
  }


}

add_action('admin_notices', 'betterhints_admin_notices');
function betterhints_admin_notices(){
    global $pagenow, $post, $page;

    //var_dumpp( get_current_screen() );
    if ( $pagenow == 'edit.php' && ( $post->post_type == 'hint' or get_current_screen()->id == 'hint_page_options') ) {

      $options = get_option('betterhints_options');
      $serial_status = $options["betterhints_serial_last_status"];
      $serial_last_checked = $options["betterhints_serial_last_checked"];

      $since = date( 'Y-m-d H:i:s', time()+60*60*24 );


      $date_expiry = $options["betterhints_serial_date_expiry"];

      $days = number_format( abs( strtotime( $date_expiry ) - strtotime($serial_last_checked) )/60/60/24 ,1 );

      //var_dumpp( strtotime( $date_expiry ) - strtotime($serial_last_checked) );
      //var_dumpp( $date_expiry );
      //var_dumpp( $daysto );

        if(is_betterhints_pro()) {
          if($days<14) {
            echo '<div class="wrap"><div class="notice notice-warning">
                <p>'.str_replace('{days}',number_format( $days, 0 ), betterhints_translate__('your key expires in {days} days.')).' <a href="'.admin_url('edit.php?post_type=hint&page=options').'">'.betterhints_translate__('Please activate it now!').'</a></p>
            </div></div>';
         } else {

         }

       } else {

       }
    }
}

function betterhints_hex2rgba($color, $opacity = false) {

	$default = '0,0,0';

	//Return default if no color provided
	if(empty($color))
          return $default;

	//Sanitize $color if "#" is provided
        if ($color[0] == '#' ) {
        	$color = substr( $color, 1 );
        }

        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
                $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
                $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
                return $default;
        }

        //Convert hexadec to rgb
        $rgb =  array_map('hexdec', $hex);

        //Check if opacity is set(rgba or rgb)
        if($opacity){
        	if(abs($opacity) > 1)
        		$opacity = 1.0;
        	$output = ''.implode(",",$rgb).','.$opacity.'';
        } else {
        	$output = ''.implode(",",$rgb).'';
        }

        //Return rgb(a) color string
        return $output;
}
?>
