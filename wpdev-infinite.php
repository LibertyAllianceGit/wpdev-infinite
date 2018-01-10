<?php
/**
 * Plugin Name: WP Developers | Infinite ∞
 * Plugin URI: http://wpdevelopers.com
 * Description: Auto loads the next post on single, with a slick transition, and loads new ads.
 * Version: 1.1.4
 * Author: Tyler Johnson
 * Author URI: http://libertyalliance.com
 * Copyright 2016 WP Developers & Liberty Alliance LLC
 */

/**
Check for Plugin Updates
**/
require 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/LibertyAllianceGit/wpdev-infinite',
	__FILE__,
	'wpdev-infinite'
);

/**
Enqueue Scripts
**/
// Enqueue Needed Scripts
function wpdevinf_enqueue_files() {
  // Setup Options
  $wpdevinf_op = get_option( 'wpdevinf_options_option_name' ); // Array of All Options
  if(wp_is_mobile()) {
    $enableinf = $wpdevinf_op['enable_infinite_scroll_10']; // Enable Infinite Scroll
    $postnav   = $wpdevinf_op['post_navigation_container_11']; // Post Navigation Container
    $toptype   = $wpdevinf_op['top_loading_type_12']; // Top Loading Type
    $toploc    = $wpdevinf_op['top_loading_type_placement_12']; // Top Loading Placement
    $toptrig   = $wpdevinf_op['top_loading_type_insert_12']; // Scroll down trigger
    $bottype   = $wpdevinf_op['bottom_loading_type_13']; // Bottom Loading Type
    $botloc    = $wpdevinf_op['bottom_loading_type_placement_13']; // Bottom Loading Placement
    $loadimg   = $wpdevinf_op['enable_panel_image_15']; // Enable Panel Image
    $enablecom = $wpdevinf_op['enable_comment_buttons_17']; // Enable Comment Buttons
    $fbcomcont = $wpdevinf_op['facebook_comment_container_18']; // Facebook Comment Container
    $dqcomcont = $wpdevinf_op['disqus_comment_container_19']; // Disqus Comment Container
    $modalsize = '300'; // Modal Size
  } else {
    $enableinf = $wpdevinf_op['enable_infinite_scroll_0']; // Enable Infinite Scroll
    $postnav   = $wpdevinf_op['post_navigation_container_1']; // Post Navigation Container
    $toptype   = $wpdevinf_op['top_loading_type_2']; // Top Loading Type
    $toploc    = $wpdevinf_op['top_loading_type_placement_2']; // Top Loading Placement
    $toptrig   = $wpdevinf_op['top_loading_type_insert_2']; // Scroll down trigger
    $bottype   = $wpdevinf_op['bottom_loading_type_3']; // Bottom Loading Type
    $botloc    = $wpdevinf_op['bottom_loading_type_placement_3']; // Bottom Loading Placement
    $loadimg   = $wpdevinf_op['enable_panel_image_5']; // Enable Panel Image
    $enablecom = $wpdevinf_op['enable_comment_buttons_7']; // Enable Comment Buttons
    $fbcomcont = $wpdevinf_op['facebook_comment_container_8']; // Facebook Comment Container
    $dqcomcont = $wpdevinf_op['disqus_comment_container_9']; // Disqus Comment Container
    $modalsize = '500'; // Modal Size
  }

  if(is_single() && !empty($enableinf)) {
      wp_enqueue_script('wpdevinf-scroll-js', plugin_dir_url(__FILE__) . 'js/wpdevinf-scroll.min.js', array('jquery'), null, true);
      wp_enqueue_script('jquery-effects-bounce');
      wp_enqueue_script( 'jquery-ui-core' );
      wp_enqueue_script( 'jquery-ui-dialog' );

      // Option Logic
        // Setup Remove Navigation
        if(!empty($postnav)) {
          $navoutput = $postnav;
        } else {
          $navoutput = '';
        }

        // Setup Top Panel Type
        if(!empty($toptype) && !empty($toploc)) {
          $topout = $toptype;
          $toplocation = $toploc;
        } elseif(!empty($toptype) && empty($toploc)) {
          $topout = $toptype;
          $toplocation = 'body';
        } elseif(empty($toptype) && !empty($toploc)) {
          $topout = '1';
          $toplocation = $toploc;
        } else {
          $topout = '1';
          $toplocation = 'body';
        }

        // Setup Bottom Panel Type
        if(!empty($bottype) && !empty($botloc)) {
          $botout = $bottype;
          $botlocation = $botloc;
        } elseif(!empty($bottype) && empty($botloc) && !empty($postnav)) {
          $botout = $bottype;
          $botlocation = $postnav;
        } elseif(empty($bottype) && !empty($botloc)) {
          $botout = '1';
          $botlocation = $botloc;
        } else {
          $botout = '1';
          $botlocation = 'body';
        }

        // Setup Buttons
        if(!empty($enablecom)) {
          if(!empty($fbcomcont)) {
            $fboutput = $fbcomcont;
          } else {
            $fboutput = '';
          }
          if(!empty($dqcomcont)) {
            $dqoutput = $dqcomcont;
          } else {
            $dqoutput = '';
          }
        }

        // Top Trigger
        if(!empty($toptrig)) {
          $toptrigout = $toptrig;
        } else {
          $toptrigout = 'body > div';
        }

      $data = array(
        'navcontainer' => $navoutput,
        'topnavtype'   => $topout,
        'topnavplace'  => $toplocation,
        'topnavtrig'   => $toptrigout,
        'botnavtype'   => $botout,
        'botnavplace'  => $botlocation,
        'commentbtns'  => $enablecom,
        'fbbuttons'    => $fboutput,
        'dqbuttons'    => $dqoutput,
        'loadingimg'   => $loadimg,
        'modalsize'    => $modalsize
      );
      wp_localize_script('wpdevinf-scroll-js', 'wpdevinf_vars', $data);
  }
}
// Load Scripts on Single Only
add_action('wp_enqueue_scripts', 'wpdevinf_enqueue_files', 10);

/**
Enqueue Admin Styles
**/
function wpdevinf_admin_styles() {
        wp_enqueue_style( 'wpdevinf-admin-css', plugin_dir_url(__FILE__) . 'admin/wpdevinf-admin.min.css');
}
add_action('admin_enqueue_scripts', 'wpdevinf_admin_styles');

/**
Load Comments Modal
**/
function wpdevinf_comments_modal()  {
  $enableinfinite = '1'; // Enable infinite scroll

  $output = '';

  if(!empty($enableinfinite)) {
    $output .= '
    <div id="fbcomments-dialog" style="display: none;">
      <p>This is going to house Facebook comments.</p>
    </div>';

    $output .= '
    <div id="dqcomments-dialog" style="display: none;">
      <p>This is going to house Disqus comments.</p>
    </div>';
  }

  echo $output;
}
add_action('wp_head', 'wpdevinf_comments_modal');

/**
Load Post Information
**/
function wpdevinf_post_information() {
  $enableinfinite = '1'; // Enable infinite scroll

  if(is_single() && !empty($enableinfinite)) {
    // Get Posts
    $next_post = get_next_post();
    $prev_post = get_previous_post();

    // If previous post exists
    if(!empty($prev_post)) {
      // Get image
      $previmg = wp_get_attachment_image_src(get_post_thumbnail_id( $prev_post->ID ), 'full');
      if(!empty($previmg)) {
        $previmgcomp = 'data-img="' . $previmg[0] . '" ';
      } else {
        $previmgcomp = '';
      }

      echo '<span id="wpdevinf-info-previous" data-url="' . get_permalink($prev_post->ID) . '" ' . $previmgcomp . 'style="display: none !important; visiblity: hidden !important;">' . $prev_post->post_title . '</span>';
    }

    // If next post exists
    if(!empty($next_post)) {
      // Get image
      $nextimg = wp_get_attachment_image_src(get_post_thumbnail_id( $next_post->ID ), 'full');
      if(!empty($nextimg)) {
        $nextimgcomp = 'data-img="' . $nextimg[0] . '" ';
      } else {
        $nextimgcomp = '';
      }

      echo '<span id="wpdevinf-info-next" data-url="' . get_permalink($next_post->ID) . '" ' . $nextimgcomp . 'style="display: none !important; visiblity: hidden !important;">' . $next_post->post_title . '</span>';
    }
  }
}
add_action('wp_footer', 'wpdevinf_post_information');

/**
Default Styles
**/
function wpdevinf_css_styles() {
  $wpdevinf_op = get_option( 'wpdevinf_options_option_name' ); // Array of All Options

  if(is_single()) {
    if(wp_is_mobile()) {
      $enableinf = $wpdevinf_op['enable_infinite_scroll_10']; // Enable Infinite Scroll
      $loadbg = $wpdevinf_op['loading_panel_background_color_14']; // Loading Panel Background Color
      $loadcolor = $wpdevinf_op['loading_bar_color_16']; // Loading Bar Color
      $enablecom = $wpdevinf_op['enable_comment_buttons_17']; // Enable Comment Buttons
      $fbcomcont = $wpdevinf_op['facebook_comment_container_18']; // Facebook Comment Container
      $dqcomcont = $wpdevinf_op['disqus_comment_container_19']; // Disqus Comment Container
    } else {
      $enableinf = $wpdevinf_op['enable_infinite_scroll_0']; // Enable Infinite Scroll
      $loadbg = $wpdevinf_op['loading_panel_background_color_4']; // Loading Panel Background Color
      $loadcolor = $wpdevinf_op['loading_bar_color_6']; // Loading Bar Color
      $enablecom = $wpdevinf_op['enable_comment_buttons_7']; // Enable Comment Buttons
      $fbcomcont = $wpdevinf_op['facebook_comment_container_8']; // Facebook Comment Container
      $dqcomcont = $wpdevinf_op['disqus_comment_container_9']; // Disqus Comment Container
    }

    // Start Output CSS
    $output = '<style type="text/css">';

    // General CSS
    $output .= 'html{top:0!important}hr.wpdevinf-post-divider{margin:0!important}.wpdevinf-bottom,.wpdevinf-top{text-align:center;font-family:inherit;background:' . $loadingcolor . ';height:auto!important;max-width:100%;min-width:100%}div#wpdevinf-bottom-inner,div#wpdevinf-top-inner{padding:2rem 0 0;background:rgba(0,0,0,.3);text-shadow:0 0 8px rgba(0,0,0,.6)}.wpdevinf-bottom a,.wpdevinf-top a{color:#f5f5f5 !important;font-weight:700;text-decoration: none;}.wpdevinf-bottom-label,.wpdevinf-top-label,h1.wpdevinf-bottom-title,h1.wpdevinf-top-title{text-align:center}h1.wpdevinf-bottom-title,h1.wpdevinf-top-title{padding:0 2rem;color:#fff !important}.wpdevinf-bottom-label,.wpdevinf-top-label{border:1px solid rgba(255,255,255,.2);padding:.5rem 1rem;margin:1rem 0 0;display:inline-block}.ui-dialog-titlebar-close,span.wpdevinf-loading, div#wpdevinf-fbcomments-cont button,div#wpdevinf-dqcomments-cont button{transition:all .3s ease;-webkit-transition:all .3s ease;-moz-transition:all .3s ease}span.wpdevinf-loading{font-weight:700;background:rgba(255,255,255,.2);padding:.2rem .5rem;cursor:pointer}span.wpdevinf-loading-next-load,span.wpdevinf-loading-previous-load{margin-left:0}span.wpdevinf-loading-next-cancel,span.wpdevinf-loading-previous-cancel{margin-left:.5rem}span.wpdevinf-loading:hover{background:rgba(255,255,255,.5)}.wpdevinf-bottom,.wpdevinf-top{background-size:cover!important;background-position:50% 50%!important}#wpdevinf-progress-bottom,#wpdevinf-progress-top{width:100%;height:10px;border:none;margin-top:2rem;background-color:rgba(41,41,41,.5)}#wpdevinf-progress-bottom div,#wpdevinf-progress-top div{height:100%;text-align:right;line-height:10px;width:0;}';

    // If Comment Buttons Enabled
    if(!empty($enablecom) && !empty($fbcomcont) || !empty($enablecom) && !empty($dqcomcont)) {
      $output .= 'div#dqcomments-dialog,div#fbcomments-dialog{background:#fff;padding:1rem;box-shadow:0 0 .5rem rgba(0,0,0,.4)}.ui-dialog-titlebar-close{background:#000;text-decoration:none;color:#fff;padding:.8rem 1rem;text-transform:uppercase;border:2px solid #000}.ui-dialog-titlebar-close:hover{background:rgba(0,0,0,0);color:#000}span.ui-dialog-title{display:none;visibility:hidden}.ui-dialog{outline:0}.ui-dialog-titlebar{background:rgba(0,0,0,.1)}';
    }

    // If Facebook Comments Enabled
    if(!empty($enablecom) && !empty($fbcomcont)) {
      $output .= 'div#fbcomments-dialog{border:2px solid #3b5998}';
    }

    // If Disqus Comments Enabled
    if(!empty($enablecom) && !empty($dqcomcont)) {
      $output .= 'div#dqcomments-dialog{border:2px solid #2e9fff}';
    }

    // If Facebook & Disqus Comments Enabled - Button Check
    if(!empty($enablecom) && !empty($fbcomcont) && !empty($dqcomcont)) {
      // Both Enabled
      $output .= 'div#wpdevinf-dqcomments-cont,div#wpdevinf-fbcomments-cont{display:inline-block;width:50%}div#wpdevinf-dqcomments-cont button,div#wpdevinf-fbcomments-cont button{width:100%;box-shadow:none;border:none;border-radius:0;font-size:18px;text-decoration:none;text-shadow:none;color:#fff;text-align:center}div#wpdevinf-fbcomments-cont button{background:#3b5998}div#wpdevinf-dqcomments-cont button{background:#2e9fff}div#wpdevinf-dqcomments-cont button:hover,div#wpdevinf-fbcomments-cont button:hover{opacity:.85}@media screen and (max-width:1200px){div#wpdevinf-dqcomments-cont button,div#wpdevinf-fbcomments-cont button{font-size:14px!important}}@media screen and (max-width:768px){div#wpdevinf-dqcomments-cont button,div#wpdevinf-fbcomments-cont button{font-size:16px!important}div#wpdevinf-dqcomments-cont,div#wpdevinf-fbcomments-cont{width:100%!important}}';
    } elseif(!empty($enablecom) && !empty($fbcomcont) && empty($dqcomcont)) {
      // Facebook Only
      $output .= 'div#wpdevinf-fbcomments-cont{margin:1rem 0}div#wpdevinf-fbcomments-cont button{width:100%;background:#3b5998;color:#fff;padding:.8rem;font-size:18px;border:none;border-radius:0;box-shadow:none;text-shadow:none}div#wpdevinf-fbcomments-cont button:hover{opacity:.85}';
    } elseif(!empty($enablecom) && !empty($dqcomcont) && empty($fbcomcont)) {
      // Disqus Only
      $output .= 'div#wpdevinf-dqcomments-cont{margin:1rem 0}div#wpdevinf-dqcomments-cont button{width:100%;background:#2e9fff;color:#fff;padding:.8rem;font-size:18px;border:none;border-radius:0;box-shadow:none;text-shadow:none}div#wpdevinf-dqcomments-cont button:hover{opacity:.85}';
    }

    // Panel Background Color
    if(!empty($loadbg)) {
      $output .= '.wpdevinf-bottom, .wpdevinf-top{background-color:' . $loadbg . ';}';
    } else {
      $output .= '.wpdevinf-bottom, .wpdevinf-top{background-color: #333;}';
    }

    // Loading Bar Color
    if(!empty($loadcolor)) {
      $output .= '#wpdevinf-progress-bottom div,#wpdevinf-progress-top div{background:' . $loadcolor . ' !important;}';
    } else {
      $output .= '#wpdevinf-progress-bottom div,#wpdevinf-progress-top div{background:#fff}';
    }

    // End Output
    $output .= '</style>';
  } else {
    // Do not output CSS
    $output = ''; // Nothing.
  }

  echo $output;
}
add_action('wp_head', 'wpdevinf_css_styles', 98);

/**
WPDevelopers Infinite Options
**/
class WpdevinfOptions {
	private $wpdevinf_options_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'wpdevinf_options_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'wpdevinf_options_page_init' ) );
	}

	public function wpdevinf_options_add_plugin_page() {
		add_menu_page(
			'WP Developers Infinite Scroll', // page_title
			'WPDev Infinite', // menu_title
			'manage_options', // capability
			'wpdevinf-options', // menu_slug
			array( $this, 'wpdevinf_options_create_admin_page' ), // function
			'dashicons-image-rotate', // icon_url
			100 // position
		);
	}

	public function wpdevinf_options_create_admin_page() {
		$this->wpdevinf_options_options = get_option( 'wpdevinf_options_option_name' ); ?>

		<div class="wrap wpdevinf-options-page">
			<img src="<?php echo plugin_dir_url(__FILE__) . 'admin/wpdevinf-logo.png'; ?>" />
			<p></p>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'wpdevinf_options_option_group' );
					do_settings_sections( 'wpdevinf-options-admin' );
					submit_button();
				?>
			</form>
		</div>
	<?php }

	public function wpdevinf_options_page_init() {
		register_setting(
			'wpdevinf_options_option_group', // option_group
			'wpdevinf_options_option_name', // option_name
			array( $this, 'wpdevinf_options_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'wpdevinf_options_desktopsetting_section', // id
			'Desktop Settings', // title
			array( $this, 'wpdevinf_options_section_info' ), // callback
			'wpdevinf-options-admin' // page
		);

		add_settings_field(
			'enable_infinite_scroll_0', // id
			'Enable Infinite Scroll', // title
			array( $this, 'enable_infinite_scroll_0_callback' ), // callback
			'wpdevinf-options-admin', // page
			'wpdevinf_options_desktopsetting_section' // section
		);

    add_settings_field(
      'top_loading_type_placement_2', // id
      'Top Loading Placement', // title
      array( $this, 'top_loading_type_placement_2_callback' ), // callback
      'wpdevinf-options-admin', // page
      'wpdevinf_options_desktopsetting_section' // section
    );

    add_settings_field(
			'top_loading_type_insert_2', // id
			'Top Loading Insert', // title
			array( $this, 'top_loading_type_insert_2_callback' ), // callback
			'wpdevinf-options-admin', // page
			'wpdevinf_options_desktopsetting_section' // section
		);

    add_settings_field(
			'top_loading_type_2', // id
			'Top Loading Type', // title
			array( $this, 'top_loading_type_2_callback' ), // callback
			'wpdevinf-options-admin', // page
			'wpdevinf_options_desktopsetting_section' // section
		);

    add_settings_field(
      'bottom_loading_type_placement_3', // id
      'Bottom Loading Placement', // title
      array( $this, 'bottom_loading_type_placement_3_callback' ), // callback
      'wpdevinf-options-admin', // page
      'wpdevinf_options_desktopsetting_section' // section
    );

    add_settings_field(
			'bottom_loading_type_3', // id
			'Bottom Loading Type', // title
			array( $this, 'bottom_loading_type_3_callback' ), // callback
			'wpdevinf-options-admin', // page
			'wpdevinf_options_desktopsetting_section' // section
		);

		add_settings_field(
			'loading_panel_background_color_4', // id
			'Loading Panel Background Color', // title
			array( $this, 'loading_panel_background_color_4_callback' ), // callback
			'wpdevinf-options-admin', // page
			'wpdevinf_options_desktopsetting_section' // section
		);

		add_settings_field(
			'enable_panel_image_5', // id
			'Enable Panel Image', // title
			array( $this, 'enable_panel_image_5_callback' ), // callback
			'wpdevinf-options-admin', // page
			'wpdevinf_options_desktopsetting_section' // section
		);

		add_settings_field(
			'loading_bar_color_6', // id
			'Loading Bar Color', // title
			array( $this, 'loading_bar_color_6_callback' ), // callback
			'wpdevinf-options-admin', // page
			'wpdevinf_options_desktopsetting_section' // section
		);

    add_settings_field(
			'post_navigation_container_1', // id
			'Post Navigation Container', // title
			array( $this, 'post_navigation_container_1_callback' ), // callback
			'wpdevinf-options-admin', // page
			'wpdevinf_options_desktopsetting_section' // section
		);

		add_settings_field(
			'enable_comment_buttons_7', // id
			'Enable Comment Buttons', // title
			array( $this, 'enable_comment_buttons_7_callback' ), // callback
			'wpdevinf-options-admin', // page
			'wpdevinf_options_desktopsetting_section' // section
		);

		add_settings_field(
			'facebook_comment_container_8', // id
			'Facebook Comment Container', // title
			array( $this, 'facebook_comment_container_8_callback' ), // callback
			'wpdevinf-options-admin', // page
			'wpdevinf_options_desktopsetting_section' // section
		);

		add_settings_field(
			'disqus_comment_container_9', // id
			'Disqus Comment Container', // title
			array( $this, 'disqus_comment_container_9_callback' ), // callback
			'wpdevinf-options-admin', // page
			'wpdevinf_options_desktopsetting_section' // section
		);

    add_settings_section(
			'wpdevinf_options_mobilesetting_section', // id
			'Mobile Settings', // title
			array( $this, 'wpdevinf_options_section_info' ), // callback
			'wpdevinf-options-admin' // page
		);

		add_settings_field(
			'enable_infinite_scroll_10', // id
			'Enable Infinite Scroll', // title
			array( $this, 'enable_infinite_scroll_10_callback' ), // callback
			'wpdevinf-options-admin', // page
			'wpdevinf_options_mobilesetting_section' // section
		);

    add_settings_field(
      'top_loading_type_placement_12', // id
      'Top Loading Placement', // title
      array( $this, 'top_loading_type_placement_12_callback' ), // callback
      'wpdevinf-options-admin', // page
      'wpdevinf_options_mobilesetting_section' // section
    );

    add_settings_field(
      'top_loading_type_insert_12', // id
      'Top Loading Insert', // title
      array( $this, 'top_loading_type_insert_12_callback' ), // callback
      'wpdevinf-options-admin', // page
      'wpdevinf_options_mobilesetting_section' // section
    );

		add_settings_field(
			'top_loading_type_12', // id
			'Top Loading Type', // title
			array( $this, 'top_loading_type_12_callback' ), // callback
			'wpdevinf-options-admin', // page
			'wpdevinf_options_mobilesetting_section' // section
		);

    add_settings_field(
      'bottom_loading_type_placement_13', // id
      'Bottom Loading Placement', // title
      array( $this, 'bottom_loading_type_placement_13_callback' ), // callback
      'wpdevinf-options-admin', // page
      'wpdevinf_options_mobilesetting_section' // section
    );

		add_settings_field(
			'bottom_loading_type_13', // id
			'Bottom Loading Type', // title
			array( $this, 'bottom_loading_type_13_callback' ), // callback
			'wpdevinf-options-admin', // page
			'wpdevinf_options_mobilesetting_section' // section
		);

		add_settings_field(
			'loading_panel_background_color_14', // id
			'Loading Panel Background Color', // title
			array( $this, 'loading_panel_background_color_14_callback' ), // callback
			'wpdevinf-options-admin', // page
			'wpdevinf_options_mobilesetting_section' // section
		);

		add_settings_field(
			'enable_panel_image_15', // id
			'Enable Panel Image', // title
			array( $this, 'enable_panel_image_15_callback' ), // callback
			'wpdevinf-options-admin', // page
			'wpdevinf_options_mobilesetting_section' // section
		);

		add_settings_field(
			'loading_bar_color_16', // id
			'Loading Bar Color', // title
			array( $this, 'loading_bar_color_16_callback' ), // callback
			'wpdevinf-options-admin', // page
			'wpdevinf_options_mobilesetting_section' // section
		);

    add_settings_field(
      'post_navigation_container_11', // id
      'Post Navigation Container', // title
      array( $this, 'post_navigation_container_11_callback' ), // callback
      'wpdevinf-options-admin', // page
      'wpdevinf_options_mobilesetting_section' // section
    );

		add_settings_field(
			'enable_comment_buttons_17', // id
			'Enable Comment Buttons', // title
			array( $this, 'enable_comment_buttons_17_callback' ), // callback
			'wpdevinf-options-admin', // page
			'wpdevinf_options_mobilesetting_section' // section
		);

		add_settings_field(
			'facebook_comment_container_18', // id
			'Facebook Comment Container', // title
			array( $this, 'facebook_comment_container_18_callback' ), // callback
			'wpdevinf-options-admin', // page
			'wpdevinf_options_mobilesetting_section' // section
		);

		add_settings_field(
			'disqus_comment_container_19', // id
			'Disqus Comment Container', // title
			array( $this, 'disqus_comment_container_19_callback' ), // callback
			'wpdevinf-options-admin', // page
			'wpdevinf_options_mobilesetting_section' // section
		);
	}

	public function wpdevinf_options_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['enable_infinite_scroll_0'] ) ) {
			$sanitary_values['enable_infinite_scroll_0'] = $input['enable_infinite_scroll_0'];
		}

		if ( isset( $input['post_navigation_container_1'] ) ) {
			$sanitary_values['post_navigation_container_1'] = sanitize_text_field( $input['post_navigation_container_1'] );
		}

		if ( isset( $input['top_loading_type_2'] ) ) {
			$sanitary_values['top_loading_type_2'] = $input['top_loading_type_2'];
		}

    if ( isset( $input['top_loading_type_placement_2'] ) ) {
			$sanitary_values['top_loading_type_placement_2'] = sanitize_text_field( $input['top_loading_type_placement_2'] );
		}

    if ( isset( $input['top_loading_type_insert_2'] ) ) {
			$sanitary_values['top_loading_type_insert_2'] = sanitize_text_field( $input['top_loading_type_insert_2'] );
		}

		if ( isset( $input['bottom_loading_type_3'] ) ) {
			$sanitary_values['bottom_loading_type_3'] = $input['bottom_loading_type_3'];
		}

    if ( isset( $input['bottom_loading_type_placement_3'] ) ) {
			$sanitary_values['bottom_loading_type_placement_3'] = sanitize_text_field( $input['bottom_loading_type_placement_3'] );
		}

		if ( isset( $input['loading_panel_background_color_4'] ) ) {
			$sanitary_values['loading_panel_background_color_4'] = sanitize_text_field( $input['loading_panel_background_color_4'] );
		}

		if ( isset( $input['enable_panel_image_5'] ) ) {
			$sanitary_values['enable_panel_image_5'] = $input['enable_panel_image_5'];
		}

		if ( isset( $input['loading_bar_color_6'] ) ) {
			$sanitary_values['loading_bar_color_6'] = sanitize_text_field( $input['loading_bar_color_6'] );
		}

		if ( isset( $input['enable_comment_buttons_7'] ) ) {
			$sanitary_values['enable_comment_buttons_7'] = $input['enable_comment_buttons_7'];
		}

		if ( isset( $input['facebook_comment_container_8'] ) ) {
			$sanitary_values['facebook_comment_container_8'] = sanitize_text_field( $input['facebook_comment_container_8'] );
		}

		if ( isset( $input['disqus_comment_container_9'] ) ) {
			$sanitary_values['disqus_comment_container_9'] = sanitize_text_field( $input['disqus_comment_container_9'] );
		}

		if ( isset( $input['enable_infinite_scroll_10'] ) ) {
			$sanitary_values['enable_infinite_scroll_10'] = $input['enable_infinite_scroll_10'];
		}

		if ( isset( $input['post_navigation_container_11'] ) ) {
			$sanitary_values['post_navigation_container_11'] = sanitize_text_field( $input['post_navigation_container_11'] );
		}

		if ( isset( $input['top_loading_type_12'] ) ) {
			$sanitary_values['top_loading_type_12'] = $input['top_loading_type_12'];
		}

    if ( isset( $input['top_loading_type_placement_12'] ) ) {
			$sanitary_values['top_loading_type_placement_12'] = sanitize_text_field( $input['top_loading_type_placement_12'] );
		}

    if ( isset( $input['top_loading_type_insert_12'] ) ) {
			$sanitary_values['top_loading_type_insert_12'] = sanitize_text_field( $input['top_loading_type_insert_12'] );
		}

		if ( isset( $input['bottom_loading_type_13'] ) ) {
			$sanitary_values['bottom_loading_type_13'] = $input['bottom_loading_type_13'];
		}

    if ( isset( $input['bottom_loading_type_placement_13'] ) ) {
			$sanitary_values['bottom_loading_type_placement_13'] = sanitize_text_field( $input['bottom_loading_type_placement_13'] );
		}

		if ( isset( $input['loading_panel_background_color_14'] ) ) {
			$sanitary_values['loading_panel_background_color_14'] = sanitize_text_field( $input['loading_panel_background_color_14'] );
		}

		if ( isset( $input['enable_panel_image_15'] ) ) {
			$sanitary_values['enable_panel_image_15'] = $input['enable_panel_image_15'];
		}

		if ( isset( $input['loading_bar_color_16'] ) ) {
			$sanitary_values['loading_bar_color_16'] = sanitize_text_field( $input['loading_bar_color_16'] );
		}

		if ( isset( $input['enable_comment_buttons_17'] ) ) {
			$sanitary_values['enable_comment_buttons_17'] = $input['enable_comment_buttons_17'];
		}

		if ( isset( $input['facebook_comment_container_18'] ) ) {
			$sanitary_values['facebook_comment_container_18'] = sanitize_text_field( $input['facebook_comment_container_18'] );
		}

		if ( isset( $input['disqus_comment_container_19'] ) ) {
			$sanitary_values['disqus_comment_container_19'] = sanitize_text_field( $input['disqus_comment_container_19'] );
		}

		return $sanitary_values;
	}

	public function wpdevinf_options_section_info() {

	}

	public function enable_infinite_scroll_0_callback() {
		printf(
			'<input type="checkbox" name="wpdevinf_options_option_name[enable_infinite_scroll_0]" id="enable_infinite_scroll_0" value="enable_infinite_scroll_0" %s> <label for="enable_infinite_scroll_0">Turn on Infinite Scroll for Desktop</label>',
			( isset( $this->wpdevinf_options_options['enable_infinite_scroll_0'] ) && $this->wpdevinf_options_options['enable_infinite_scroll_0'] === 'enable_infinite_scroll_0' ) ? 'checked' : ''
		);
	}

	public function post_navigation_container_1_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdevinf_options_option_name[post_navigation_container_1]" placeholder=".class or #id" id="post_navigation_container_1" value="%s"><label for="post_navigation_container_1">Hide the post navigation by entering the post navigation class or ID.</label>',
			isset( $this->wpdevinf_options_options['post_navigation_container_1'] ) ? esc_attr( $this->wpdevinf_options_options['post_navigation_container_1']) : ''
		);
	}

	public function top_loading_type_2_callback() {
		?> <fieldset><?php $checked = ( isset( $this->wpdevinf_options_options['top_loading_type_2'] ) && $this->wpdevinf_options_options['top_loading_type_2'] === '1' ) ? 'checked' : '' ; ?>
		<label for="top_loading_type_2-0"><input type="radio" name="wpdevinf_options_option_name[top_loading_type_2]" id="top_loading_type_2-0" value="1" <?php echo $checked; ?>> Push In</label>
		<?php $checked = ( isset( $this->wpdevinf_options_options['top_loading_type_2'] ) && $this->wpdevinf_options_options['top_loading_type_2'] === '2' ) ? 'checked' : '' ; ?>
		<label for="top_loading_type_2-1"><input type="radio" name="wpdevinf_options_option_name[top_loading_type_2]" id="top_loading_type_2-1" value="2" <?php echo $checked; ?>> Slide In</label>
		<?php $checked = ( isset( $this->wpdevinf_options_options['top_loading_type_2'] ) && $this->wpdevinf_options_options['top_loading_type_2'] === '3' ) ? 'checked' : '' ; ?>
		<label for="top_loading_type_2-2"><input type="radio" name="wpdevinf_options_option_name[top_loading_type_2]" id="top_loading_type_2-2" value="3" <?php echo $checked; ?>>  Static</label></fieldset> <?php
	}

  public function top_loading_type_placement_2_callback() {
    printf(
      '<input class="regular-text" type="text" name="wpdevinf_options_option_name[top_loading_type_placement_2]" placeholder=".class or #id" id="top_loading_type_placement_2" value="%s"><label for="top_loading_type_placement_2">Top loading trigger location. Accepts a class or ID.</label>',
      isset( $this->wpdevinf_options_options['top_loading_type_placement_2'] ) ? esc_attr( $this->wpdevinf_options_options['top_loading_type_placement_2']) : ''
    );
  }

  public function top_loading_type_insert_2_callback() {
    printf(
      '<input class="regular-text" type="text" name="wpdevinf_options_option_name[top_loading_type_insert_2]" placeholder=".class or #id" id="top_loading_type_insert_2" value="%s"><label for="top_loading_type_insert_2">Top loading scroll down trigger location. Adds infinite scroll header once you scroll past triggered element. Accepts a class or ID.</label>',
      isset( $this->wpdevinf_options_options['top_loading_type_insert_2'] ) ? esc_attr( $this->wpdevinf_options_options['top_loading_type_insert_2']) : ''
    );
  }

	public function bottom_loading_type_3_callback() {
		?> <fieldset><?php $checked = ( isset( $this->wpdevinf_options_options['bottom_loading_type_3'] ) && $this->wpdevinf_options_options['bottom_loading_type_3'] === '1' ) ? 'checked' : '' ; ?>
		<label for="bottom_loading_type_3-0"><input type="radio" name="wpdevinf_options_option_name[bottom_loading_type_3]" id="bottom_loading_type_3-0" value="1" <?php echo $checked; ?>> Push In</label>
		<?php $checked = ( isset( $this->wpdevinf_options_options['bottom_loading_type_3'] ) && $this->wpdevinf_options_options['bottom_loading_type_3'] === '2' ) ? 'checked' : '' ; ?>
		<label for="bottom_loading_type_3-1"><input type="radio" name="wpdevinf_options_option_name[bottom_loading_type_3]" id="bottom_loading_type_3-1" value="2" <?php echo $checked; ?>> Slide In</label>
		<?php $checked = ( isset( $this->wpdevinf_options_options['bottom_loading_type_3'] ) && $this->wpdevinf_options_options['bottom_loading_type_3'] === '3' ) ? 'checked' : '' ; ?>
		<label for="bottom_loading_type_3-2"><input type="radio" name="wpdevinf_options_option_name[bottom_loading_type_3]" id="bottom_loading_type_3-2" value="3" <?php echo $checked; ?>>  Static</label></fieldset> <?php
	}

  public function bottom_loading_type_placement_3_callback() {
    printf(
      '<input class="regular-text" type="text" name="wpdevinf_options_option_name[bottom_loading_type_placement_3]" placeholder=".class or #id" id="bottom_loading_type_placement_3" value="%s"><label for="bottom_loading_type_placement_3">Bottom loading trigger location. If static panel, also location of panel. Accepts a class or ID.</label>',
      isset( $this->wpdevinf_options_options['bottom_loading_type_placement_3'] ) ? esc_attr( $this->wpdevinf_options_options['bottom_loading_type_placement_3']) : ''
    );
  }

	public function loading_panel_background_color_4_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdevinf_options_option_name[loading_panel_background_color_4]" placeholder="#333, rgba(255,255,255,1), or red" id="loading_panel_background_color_4" value="%s"><label for="loading_panel_background_color_4">Panel background color if no image exists or image option is disabled. Accepts hex, RGBA, or written colors.</label>',
			isset( $this->wpdevinf_options_options['loading_panel_background_color_4'] ) ? esc_attr( $this->wpdevinf_options_options['loading_panel_background_color_4']) : ''
		);
	}

	public function enable_panel_image_5_callback() {
		printf(
			'<input type="checkbox" name="wpdevinf_options_option_name[enable_panel_image_5]" id="enable_panel_image_5" value="enable_panel_image_5" %s> <label for="enable_panel_image_5">Display next or previous post featured image. If no image exists, the background color displays.</label>',
			( isset( $this->wpdevinf_options_options['enable_panel_image_5'] ) && $this->wpdevinf_options_options['enable_panel_image_5'] === 'enable_panel_image_5' ) ? 'checked' : ''
		);
	}

	public function loading_bar_color_6_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdevinf_options_option_name[loading_bar_color_6]"  placeholder="#333, rgba(255,255,255,1), or red" id="loading_bar_color_6" value="%s"><label for="loading_bar_color_6">Load bar color for transition timer. Accepts hex, RGBA, or written colors.</label>',
			isset( $this->wpdevinf_options_options['loading_bar_color_6'] ) ? esc_attr( $this->wpdevinf_options_options['loading_bar_color_6']) : ''
		);
	}

	public function enable_comment_buttons_7_callback() {
		printf(
			'<input type="checkbox" name="wpdevinf_options_option_name[enable_comment_buttons_7]" id="enable_comment_buttons_7" value="enable_comment_buttons_7" %s> <label for="enable_comment_buttons_7">Replace Facebook and Disqus with comment buttons that display modals.</label>',
			( isset( $this->wpdevinf_options_options['enable_comment_buttons_7'] ) && $this->wpdevinf_options_options['enable_comment_buttons_7'] === 'enable_comment_buttons_7' ) ? 'checked' : ''
		);
	}

	public function facebook_comment_container_8_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdevinf_options_option_name[facebook_comment_container_8]" placeholder=".class or #id" id="facebook_comment_container_8" value="%s"><label for="facebook_comment_container_8">Facebook comment container for grabbing the comments, placing them in a modal, and hiding the normal output. Accepts a class or ID.</label>',
			isset( $this->wpdevinf_options_options['facebook_comment_container_8'] ) ? esc_attr( $this->wpdevinf_options_options['facebook_comment_container_8']) : ''
		);
	}

	public function disqus_comment_container_9_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdevinf_options_option_name[disqus_comment_container_9]" placeholder=".class or #id" id="disqus_comment_container_9" value="%s"><label for="disqus_comment_container_9">Disqus comment container for grabbing the comments, placing them in a modal, and hiding the normal output. Accepts a class or ID.</label>',
			isset( $this->wpdevinf_options_options['disqus_comment_container_9'] ) ? esc_attr( $this->wpdevinf_options_options['disqus_comment_container_9']) : ''
		);
	}

	public function enable_infinite_scroll_10_callback() {
		printf(
			'<input type="checkbox" name="wpdevinf_options_option_name[enable_infinite_scroll_10]" id="enable_infinite_scroll_10" value="enable_infinite_scroll_10" %s> <label for="enable_infinite_scroll_10">Turn on Infinite Scroll for Mobile</label>',
			( isset( $this->wpdevinf_options_options['enable_infinite_scroll_10'] ) && $this->wpdevinf_options_options['enable_infinite_scroll_10'] === 'enable_infinite_scroll_10' ) ? 'checked' : ''
		);
	}

	public function post_navigation_container_11_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdevinf_options_option_name[post_navigation_container_11]" placeholder=".class or #id" id="post_navigation_container_11" value="%s"><label for="post_navigation_container_11">Hide the post navigation by entering the post navigation class or ID.</label>',
			isset( $this->wpdevinf_options_options['post_navigation_container_11'] ) ? esc_attr( $this->wpdevinf_options_options['post_navigation_container_11']) : ''
		);
	}

	public function top_loading_type_12_callback() {
		?> <fieldset><?php $checked = ( isset( $this->wpdevinf_options_options['top_loading_type_12'] ) && $this->wpdevinf_options_options['top_loading_type_12'] === '1' ) ? 'checked' : '' ; ?>
		<label for="top_loading_type_12-0"><input type="radio" name="wpdevinf_options_option_name[top_loading_type_12]" id="top_loading_type_12-0" value="1" <?php echo $checked; ?>> Push In</label>
		<?php $checked = ( isset( $this->wpdevinf_options_options['top_loading_type_12'] ) && $this->wpdevinf_options_options['top_loading_type_12'] === '2' ) ? 'checked' : '' ; ?>
		<label for="top_loading_type_12-1"><input type="radio" name="wpdevinf_options_option_name[top_loading_type_12]" id="top_loading_type_12-1" value="2" <?php echo $checked; ?>> Slide In</label>
		<?php $checked = ( isset( $this->wpdevinf_options_options['top_loading_type_12'] ) && $this->wpdevinf_options_options['top_loading_type_12'] === '3' ) ? 'checked' : '' ; ?>
		<label for="top_loading_type_12-2"><input type="radio" name="wpdevinf_options_option_name[top_loading_type_12]" id="top_loading_type_12-2" value="3" <?php echo $checked; ?>>  Static</label></fieldset> <?php
	}

  public function top_loading_type_placement_12_callback() {
		printf(
			'<input class="regular-text" type="text" placeholder=".class or #id" name="wpdevinf_options_option_name[top_loading_type_placement_12]" id="top_loading_type_placement_12" value="%s"><label for="top_loading_type_placement_12">Top loading trigger location. Accepts a class or ID.</label>',
			isset( $this->wpdevinf_options_options['top_loading_type_placement_12'] ) ? esc_attr( $this->wpdevinf_options_options['top_loading_type_placement_12']) : ''
		);
	}

  public function top_loading_type_insert_12_callback() {
		printf(
			'<input class="regular-text" type="text" placeholder=".class or #id" name="wpdevinf_options_option_name[top_loading_type_insert_12]" id="top_loading_type_insert_12" value="%s"><label for="top_loading_type_insert_12">Top loading scroll down trigger location. Adds infinite scroll header once you scroll past triggered element. Accepts a class or ID.</label>',
			isset( $this->wpdevinf_options_options['top_loading_type_insert_12'] ) ? esc_attr( $this->wpdevinf_options_options['top_loading_type_insert_12']) : ''
		);
	}

	public function bottom_loading_type_13_callback() {
		?> <fieldset><?php $checked = ( isset( $this->wpdevinf_options_options['bottom_loading_type_13'] ) && $this->wpdevinf_options_options['bottom_loading_type_13'] === '1' ) ? 'checked' : '' ; ?>
		<label for="bottom_loading_type_13-0"><input type="radio" name="wpdevinf_options_option_name[bottom_loading_type_13]" id="bottom_loading_type_13-0" value="1" <?php echo $checked; ?>> Push In</label>
		<?php $checked = ( isset( $this->wpdevinf_options_options['bottom_loading_type_13'] ) && $this->wpdevinf_options_options['bottom_loading_type_13'] === '2' ) ? 'checked' : '' ; ?>
		<label for="bottom_loading_type_13-1"><input type="radio" name="wpdevinf_options_option_name[bottom_loading_type_13]" id="bottom_loading_type_13-1" value="2" <?php echo $checked; ?>> Slide In</label>
		<?php $checked = ( isset( $this->wpdevinf_options_options['bottom_loading_type_13'] ) && $this->wpdevinf_options_options['bottom_loading_type_13'] === '3' ) ? 'checked' : '' ; ?>
		<label for="bottom_loading_type_13-2"><input type="radio" name="wpdevinf_options_option_name[bottom_loading_type_13]" id="bottom_loading_type_13-2" value="3" <?php echo $checked; ?>>  Static</label></fieldset> <?php
	}

  public function bottom_loading_type_placement_13_callback() {
		printf(
			'<input class="regular-text" type="text" placeholder=".class or #id" name="wpdevinf_options_option_name[bottom_loading_type_placement_13]" id="bottom_loading_type_placement_13" value="%s"><label for="bottom_loading_type_placement_13">Bottom loading trigger location. If static panel, also location of panel. Accepts a class or ID.</label>',
			isset( $this->wpdevinf_options_options['bottom_loading_type_placement_13'] ) ? esc_attr( $this->wpdevinf_options_options['bottom_loading_type_placement_13']) : ''
		);
	}

	public function loading_panel_background_color_14_callback() {
		printf(
			'<input class="regular-text" type="text" placeholder="#333, rgba(255,255,255,1), or red" name="wpdevinf_options_option_name[loading_panel_background_color_14]" id="loading_panel_background_color_14" value="%s"><label for="loading_panel_background_color_4">Panel background color if no image exists or image option is disabled. Accepts hex, RGBA, or written colors.</label>',
			isset( $this->wpdevinf_options_options['loading_panel_background_color_14'] ) ? esc_attr( $this->wpdevinf_options_options['loading_panel_background_color_14']) : ''
		);
	}

	public function enable_panel_image_15_callback() {
		printf(
			'<input type="checkbox" name="wpdevinf_options_option_name[enable_panel_image_15]" id="enable_panel_image_15" value="enable_panel_image_15" %s> <label for="enable_panel_image_15">Display next or previous post featured image. If no image exists, the background color displays.</label>',
			( isset( $this->wpdevinf_options_options['enable_panel_image_15'] ) && $this->wpdevinf_options_options['enable_panel_image_15'] === 'enable_panel_image_15' ) ? 'checked' : ''
		);
	}

	public function loading_bar_color_16_callback() {
		printf(
			'<input class="regular-text" type="text" placeholder="#333, rgba(255,255,255,1), or red" name="wpdevinf_options_option_name[loading_bar_color_16]" id="loading_bar_color_16" value="%s"><label for="loading_bar_color_16">Load bar color for transition timer. Accepts hex, RGBA, or written colors.</label>',
			isset( $this->wpdevinf_options_options['loading_bar_color_16'] ) ? esc_attr( $this->wpdevinf_options_options['loading_bar_color_16']) : ''
		);
	}

	public function enable_comment_buttons_17_callback() {
		printf(
			'<input type="checkbox" name="wpdevinf_options_option_name[enable_comment_buttons_17]" id="enable_comment_buttons_17" value="enable_comment_buttons_17" %s> <label for="enable_comment_buttons_17">Replace Facebook and Disqus with comment buttons that display modals.</label>',
			( isset( $this->wpdevinf_options_options['enable_comment_buttons_17'] ) && $this->wpdevinf_options_options['enable_comment_buttons_17'] === 'enable_comment_buttons_17' ) ? 'checked' : ''
		);
	}

	public function facebook_comment_container_18_callback() {
		printf(
			'<input class="regular-text" type="text" placeholder=".class or #id" name="wpdevinf_options_option_name[facebook_comment_container_18]" id="facebook_comment_container_18" value="%s"><label for="facebook_comment_container_18">Facebook comment container for grabbing the comments, placing them in a modal, and hiding the normal output. Accepts a class or ID.</label>',
			isset( $this->wpdevinf_options_options['facebook_comment_container_18'] ) ? esc_attr( $this->wpdevinf_options_options['facebook_comment_container_18']) : ''
		);
	}

	public function disqus_comment_container_19_callback() {
		printf(
			'<input class="regular-text" type="text" placeholder=".class or #id" name="wpdevinf_options_option_name[disqus_comment_container_19]" id="disqus_comment_container_19" value="%s"><label for="disqus_comment_container_19">Disqus comment container for grabbing the comments, placing them in a modal, and hiding the normal output. Accepts a class or ID.</label>',
			isset( $this->wpdevinf_options_options['disqus_comment_container_19'] ) ? esc_attr( $this->wpdevinf_options_options['disqus_comment_container_19']) : ''
		);
	}

}
if ( is_admin() )
	$wpdevinf_options = new WpdevinfOptions();

/*
 * Retrieve this value with:
 * $wpdevinf_options_options = get_option( 'wpdevinf_options_option_name' ); // Array of All Options
 * $enable_infinite_scroll_0 = $wpdevinf_op['enable_infinite_scroll_0']; // Enable Infinite Scroll
 * $post_navigation_container_1 = $wpdevinf_op['post_navigation_container_1']; // Post Navigation Container
 * $top_loading_type_2 = $wpdevinf_op['top_loading_type_2']; // Top Loading Type
 * $bottom_loading_type_3 = $wpdevinf_op['bottom_loading_type_3']; // Bottom Loading Type
 * $loading_panel_background_color_4 = $wpdevinf_op['loading_panel_background_color_4']; // Loading Panel Background Color
 * $enable_panel_image_5 = $wpdevinf_op['enable_panel_image_5']; // Enable Panel Image
 * $loading_bar_color_6 = $wpdevinf_op['loading_bar_color_6']; // Loading Bar Color
 * $enable_comment_buttons_7 = $wpdevinf_op['enable_comment_buttons_7']; // Enable Comment Buttons
 * $facebook_comment_container_8 = $wpdevinf_op['facebook_comment_container_8']; // Facebook Comment Container
 * $disqus_comment_container_9 = $wpdevinf_op['disqus_comment_container_9']; // Disqus Comment Container
 * $enable_infinite_scroll_10 = $wpdevinf_op['enable_infinite_scroll_10']; // Enable Infinite Scroll
 * $post_navigation_container_11 = $wpdevinf_op['post_navigation_container_11']; // Post Navigation Container
 * $top_loading_type_12 = $wpdevinf_op['top_loading_type_12']; // Top Loading Type
 * $bottom_loading_type_13 = $wpdevinf_op['bottom_loading_type_13']; // Bottom Loading Type
 * $loading_panel_background_color_14 = $wpdevinf_op['loading_panel_background_color_14']; // Loading Panel Background Color
 * $enable_panel_image_15 = $wpdevinf_op['enable_panel_image_15']; // Enable Panel Image
 * $loading_bar_color_16 = $wpdevinf_op['loading_bar_color_16']; // Loading Bar Color
 * $enable_comment_buttons_17 = $wpdevinf_op['enable_comment_buttons_17']; // Enable Comment Buttons
 * $facebook_comment_container_18 = $wpdevinf_op['facebook_comment_container_18']; // Facebook Comment Container
 * $disqus_comment_container_19 = $wpdevinf_op['disqus_comment_container_19']; // Disqus Comment Container
 */
