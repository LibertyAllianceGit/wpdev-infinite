<?php
/**
 * Plugin Name: WP Developers | Infinite ∞
 * Plugin URI: http://wpdevelopers.com
 * Description: Auto loads the next post on single, with a slick transition, and loads new ads.
 * Version: 1.0.1
 * Author: Tyler Johnson
 * Author URI: http://libertyalliance.com
 * Copyright 2016 WP Developers & Liberty Alliance LLC
 */

/**
Check for Plugin Updates
**/
require 'plugin-update-checker/plugin-update-checker.php';
$className = PucFactory::getLatestClassVersion('PucGitHubChecker');
$myUpdateChecker = new $className(
    'https://github.com/LibertyAllianceGit/wpdev-infinite',
    __FILE__,
    'master'
);

/**
Enqueue Scripts
**/
// Enqueue Needed Scripts
function wpdevinf_enqueue_files() {
  if(is_single()) {
    // Grab Options
    $wpdevinfopt = get_option( 'wpdevinf_options_option_name' );
    $enableinfinite = $wpdevinfopt['enable_infinite_scroll_0']; // Enable infinite scroll
    $navoption = $wpdevinfopt['post_navigation_container_0']; // Post Navigation Container
    $wptouchnavoption = $wpdevinfopt['wptouch_post_navigation_container_0']; // Post Navigation
    $locoption = $wpdevinfopt['bottom_loading_location_container_1']; // Bottom Loading Location
    $wptouchlocoption = $wpdevinfopt['wptouch_bottom_loading_location_container_1']; // Bottom Loading Location
    $enablebtns = $wpdevinfopt['enable_comment_buttons_3']; // Enable Comment Buttons
    $fbcontainer = $wpdevinfopt['facebook_comments_container_4']; // Facebook Comments Container
    $dqcontainer = $wpdevinfopt['disqus_comments_container_5']; // Disqus Comments Container
    $delaytime = $wpdevinfopt['page_refresh_transition_time_6']; // Page Refresh Transition Time

    if(!empty($enableinfinite)) {
      wp_enqueue_script('wpdevinf-scroll-js', plugin_dir_url(__FILE__) . 'js/wpdevinf-scroll.min.js', array('jquery'), null, true);
      wp_enqueue_script('jquery-effects-bounce');
      wp_enqueue_script( 'jquery-ui-core' );
      wp_enqueue_script( 'jquery-ui-dialog' );

      // Setup Options
        // Setup Remove Navigation
        if(!empty($navoption) && !wp_is_mobile()) {
          $navoutput = $navoption;
        } elseif(!empty($wptouchnavoption) && wp_is_mobile()) {
          $navoutput = $wptouchnavoption;
        } else {
          $navoutput = '';
        }

        // Setup Bottom Output Location
        if(!empty($locoption) && !wp_is_mobile()) {
          $locoutput = $locoption;
        } elseif(!empty($wptouchlocoption) && wp_is_mobile()) {
          $locoutput = $wptouchlocoption;
        } elseif(!empty($navoption)) {
          $locoutput = $navoption;
        } else {
          $locoutput = 'body';
        }

        // Setup Buttons
        if(!empty($enablebtns)) {
          if(!empty($fbcontainer)) {
            $fboutput = $fbcontainer;
          } else {
            $fboutput = '';
          }
          if(!empty($dqcontainer)) {
            $dqoutput = $dqcontainer;
          } else {
            $dqoutput = '';
          }
        }

        if(!wp_is_mobile()) {
          $modalsize = '500';
        } else {
          $modalsize = '300';
        }

        if(!empty($delaytime)) {
          $transtimer = $delaytime;
        } else {
          $transtimer = 3000;
        }

      $data = array(
        'navcontainer' => $navoutput,
        'navlocation'  => $locoutput,
        'commentbtns'  => $enablebtns,
        'fbbuttons'    => $fboutput,
        'dqbuttons'    => $dqoutput,
        'modalsize'    => $modalsize,
        'transtimer'   => $transtimer
      );
      wp_localize_script('wpdevinf-scroll-js', 'wpdevinf_vars', $data);
    }
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
  $wpdevinfopt = get_option( 'wpdevinf_options_option_name' );
  $enableinfinite = $wpdevinfopt['enable_infinite_scroll_0']; // Enable infinite scroll

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
  $wpdevinfopt = get_option( 'wpdevinf_options_option_name' );
  $enableinfinite = $wpdevinfopt['enable_infinite_scroll_0']; // Enable infinite scroll

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
  $wpdevinfopt = get_option( 'wpdevinf_options_option_name' ); // Array of All Options
  $enableinfinite = $wpdevinfopt['enable_infinite_scroll_0']; // Enable infinite scroll

  if(is_single() && !empty($enableinfinite)) {
    // Setup Options
    $loadingcolor = $wpdevinfopt['loading_background_color_2']; // Loading Background Color
    $enablecomments = $wpdevinfopt['enable_comment_buttons_3']; // Enable Comment Buttons
    $fbcomments = $wpdevinfopt['facebook_comments_container_4']; // Facebook Comments Container
    $dqcomments = $wpdevinfopt['disqus_comments_container_5']; // Disqus Comments Container

    // Start Output CSS
    $output = '<style type="text/css">';

    // General CSS
    $output .= 'body,body:before,html{top:0!important}hr.wpdevinf-post-divider{margin:0!important}.wpdevinf-bottom,.wpdevinf-top{text-align:center;font-family:inherit;background:' . $loadingcolor . ';height:auto!important;max-width:100%;min-width:100%}div#wpdevinf-bottom-inner,div#wpdevinf-top-inner{padding:2rem 0 0;background:rgba(0,0,0,.3);text-shadow:0 0 8px rgba(0,0,0,.6)}.wpdevinf-bottom a,.wpdevinf-top a{color:#f5f5f5 !important;font-weight:700;text-decoration: none;}.wpdevinf-bottom{margin:2rem 0}.wpdevinf-bottom-label,.wpdevinf-top-label,h1.wpdevinf-bottom-title,h1.wpdevinf-top-title{text-align:center}h1.wpdevinf-bottom-title,h1.wpdevinf-top-title{padding:0 2rem;color:#fff !important}.wpdevinf-bottom-label,.wpdevinf-top-label{border:1px solid rgba(255,255,255,.2);padding:.5rem 1rem;margin:1rem 0 0;display:inline-block}.ui-dialog-titlebar-close,span.wpdevinf-loading, div#wpdevinf-fbcomments-cont button,div#wpdevinf-dqcomments-cont button{transition:all .3s ease;-webkit-transition:all .3s ease;-moz-transition:all .3s ease}span.wpdevinf-loading{font-weight:700;background:rgba(255,255,255,.2);padding:.2rem .5rem;cursor:pointer}span.wpdevinf-loading-next-load,span.wpdevinf-loading-previous-load{margin-left:0}span.wpdevinf-loading-next-cancel,span.wpdevinf-loading-previous-cancel{margin-left:.5rem}span.wpdevinf-loading:hover{background:rgba(255,255,255,.5)}.wpdevinf-bottom,.wpdevinf-top{background-size:cover!important;background-position:50% 50%!important}#wpdevinf-progress-bottom,#wpdevinf-progress-top{width:100%;height:10px;border:none;margin-top:2rem;background-color:rgba(41,41,41,.5)}#wpdevinf-progress-bottom div,#wpdevinf-progress-top div{height:100%;text-align:right;line-height:10px;width:0;background-color:rgba(255,255,255,.5)}';

    // If Comment Buttons Enabled
    if(!empty($enablecomments) && !empty($fbcomments) || !empty($enablecomments) && !empty($dqcomments)) {
      $output .= 'div#dqcomments-dialog,div#fbcomments-dialog{background:#fff;padding:1rem;box-shadow:0 0 .5rem rgba(0,0,0,.4)}.ui-dialog-titlebar-close{background:#000;text-decoration:none;color:#fff;padding:.8rem 1rem;text-transform:uppercase;border:2px solid #000}.ui-dialog-titlebar-close:hover{background:rgba(0,0,0,0);color:#000}span.ui-dialog-title{display:none;visibility:hidden}.ui-dialog{outline:0}.ui-dialog-titlebar{background:rgba(0,0,0,.1)}';
    }

    // If Facebook Comments Enabled
    if(!empty($enablecomments) && !empty($fbcomments)) {
      $output .= 'div#fbcomments-dialog{border:2px solid #3b5998}';
    }

    // If Disqus Comments Enabled
    if(!empty($enablecomments) && !empty($dqcomments)) {
      $output .= 'div#dqcomments-dialog{border:2px solid #2e9fff}';
    }

    // If Facebook & Disqus Comments Enabled - Button Check
    if(!empty($enablecomments) && !empty($fbcomments) && !empty($dqcomments)) {
      // Both Enabled
      $output .= 'div#wpdevinf-dqcomments-cont,div#wpdevinf-fbcomments-cont{display:inline-block;width:50%}div#wpdevinf-dqcomments-cont button,div#wpdevinf-fbcomments-cont button{width:100%;box-shadow:none;border:none;border-radius:0;font-size:18px;text-decoration:none;text-shadow:none;color:#fff;text-align:center}div#wpdevinf-fbcomments-cont button{background:#3b5998}div#wpdevinf-dqcomments-cont button{background:#2e9fff}div#wpdevinf-dqcomments-cont button:hover,div#wpdevinf-fbcomments-cont button:hover{opacity:.85}@media screen and (max-width:1200px){div#wpdevinf-dqcomments-cont button,div#wpdevinf-fbcomments-cont button{font-size:14px!important}}@media screen and (max-width:768px){div#wpdevinf-dqcomments-cont button,div#wpdevinf-fbcomments-cont button{font-size:16px!important}div#wpdevinf-dqcomments-cont,div#wpdevinf-fbcomments-cont{width:100%!important}}';
    } elseif(!empty($enablecomments) && !empty($fbcomments) && empty($dqcomments)) {
      // Facebook Only
      $output .= 'div#wpdevinf-fbcomments-cont{margin:1rem 0}div#wpdevinf-fbcomments-cont button{width:100%;background:#3b5998;color:#fff;padding:.8rem;font-size:18px;border:none;border-radius:0;box-shadow:none;text-shadow:none}div#wpdevinf-fbcomments-cont button:hover{opacity:.85}';
    } elseif(!empty($enablecomments) && !empty($dqcomments) && empty($fbcomments)) {
      // Disqus Only
      $output .= 'div#wpdevinf-dqcomments-cont{margin:1rem 0}div#wpdevinf-dqcomments-cont button{width:100%;background:#2e9fff;color:#fff;padding:.8rem;font-size:18px;border:none;border-radius:0;box-shadow:none;text-shadow:none}div#wpdevinf-dqcomments-cont button:hover{opacity:.85}';
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
			'WPDev Infinite', // page_title
			'WPDev Infinite', // menu_title
			'manage_options', // capability
			'wpdevinf-options', // menu_slug
			array( $this, 'wpdevinf_options_create_admin_page' ), // function
			'dashicons-controls-repeat', // icon_url
			100 // position
		);
	}

	public function wpdevinf_options_create_admin_page() {
		$this->wpdevinf_options_options = get_option( 'wpdevinf_options_option_name' ); ?>

		<div class="wrap wpdevinf-options-page">
			<h2><img src="<?php echo plugin_dir_url(__FILE__) . 'admin/wpdevinf-logo.png'; ?>" alt="WPDevelopers Infinite"/></h2>
			<p>A new, fresh take on infinite load for single posts that puts the user in control.</p>
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
			'wpdevinf_options_setting_section', // id
			'Settings', // title
			array( $this, 'wpdevinf_options_section_info' ), // callback
			'wpdevinf-options-admin' // page
		);

    add_settings_field(
			'enable_infinite_scroll_0', // id
			'Enable Infinite Scroll', // title
			array( $this, 'enable_infinite_scroll_0_callback' ), // callback
			'wpdevinf-options-admin', // page
			'wpdevinf_options_setting_section' // section
		);

		add_settings_field(
			'post_navigation_container_0', // id
			'Post Navigation Container', // title
			array( $this, 'post_navigation_container_0_callback' ), // callback
			'wpdevinf-options-admin', // page
			'wpdevinf_options_setting_section' // section
		);

    add_settings_field(
			'wptouch_post_navigation_container_0', // id
			'WPTouch Post Navigation Container', // title
			array( $this, 'wptouch_post_navigation_container_0_callback' ), // callback
			'wpdevinf-options-admin', // page
			'wpdevinf_options_setting_section' // section
		);

		add_settings_field(
			'bottom_loading_location_container_1', // id
			'Bottom Loading Location Container', // title
			array( $this, 'bottom_loading_location_container_1_callback' ), // callback
			'wpdevinf-options-admin', // page
			'wpdevinf_options_setting_section' // section
		);

    add_settings_field(
			'wptouch_bottom_loading_location_container_1', // id
			'WPTouch Bottom Loading Location Container', // title
			array( $this, 'wptouch_bottom_loading_location_container_1_callback' ), // callback
			'wpdevinf-options-admin', // page
			'wpdevinf_options_setting_section' // section
		);

		add_settings_field(
			'loading_background_color_2', // id
			'Loading Background Color', // title
			array( $this, 'loading_background_color_2_callback' ), // callback
			'wpdevinf-options-admin', // page
			'wpdevinf_options_setting_section' // section
		);

		add_settings_field(
			'enable_comment_buttons_3', // id
			'Enable Comment Buttons', // title
			array( $this, 'enable_comment_buttons_3_callback' ), // callback
			'wpdevinf-options-admin', // page
			'wpdevinf_options_setting_section' // section
		);

		add_settings_field(
			'facebook_comments_container_4', // id
			'Facebook Comments Container', // title
			array( $this, 'facebook_comments_container_4_callback' ), // callback
			'wpdevinf-options-admin', // page
			'wpdevinf_options_setting_section' // section
		);

		add_settings_field(
			'disqus_comments_container_5', // id
			'Disqus Comments Container', // title
			array( $this, 'disqus_comments_container_5_callback' ), // callback
			'wpdevinf-options-admin', // page
			'wpdevinf_options_setting_section' // section
		);

		add_settings_field(
			'page_refresh_transition_time_6', // id
			'Page Refresh Transition Time', // title
			array( $this, 'page_refresh_transition_time_6_callback' ), // callback
			'wpdevinf-options-admin', // page
			'wpdevinf_options_setting_section' // section
		);
	}

	public function wpdevinf_options_sanitize($input) {
		$sanitary_values = array();
    if ( isset( $input['enable_infinite_scroll_0'] ) ) {
			$sanitary_values['enable_infinite_scroll_0'] = $input['enable_infinite_scroll_0'];
		}

		if ( isset( $input['post_navigation_container_0'] ) ) {
			$sanitary_values['post_navigation_container_0'] = sanitize_text_field( $input['post_navigation_container_0'] );
		}

    if ( isset( $input['wptouch_post_navigation_container_0'] ) ) {
			$sanitary_values['wptouch_post_navigation_container_0'] = sanitize_text_field( $input['wptouch_post_navigation_container_0'] );
		}

		if ( isset( $input['bottom_loading_location_container_1'] ) ) {
			$sanitary_values['bottom_loading_location_container_1'] = sanitize_text_field( $input['bottom_loading_location_container_1'] );
		}

    if ( isset( $input['wptouch_bottom_loading_location_container_1'] ) ) {
			$sanitary_values['wptouch_bottom_loading_location_container_1'] = sanitize_text_field( $input['wptouch_bottom_loading_location_container_1'] );
		}

		if ( isset( $input['loading_background_color_2'] ) ) {
			$sanitary_values['loading_background_color_2'] = sanitize_text_field( $input['loading_background_color_2'] );
		}

		if ( isset( $input['enable_comment_buttons_3'] ) ) {
			$sanitary_values['enable_comment_buttons_3'] = $input['enable_comment_buttons_3'];
		}

		if ( isset( $input['facebook_comments_container_4'] ) ) {
			$sanitary_values['facebook_comments_container_4'] = sanitize_text_field( $input['facebook_comments_container_4'] );
		}

		if ( isset( $input['disqus_comments_container_5'] ) ) {
			$sanitary_values['disqus_comments_container_5'] = sanitize_text_field( $input['disqus_comments_container_5'] );
		}

		if ( isset( $input['page_refresh_transition_time_6'] ) ) {
			$sanitary_values['page_refresh_transition_time_6'] = sanitize_text_field( $input['page_refresh_transition_time_6'] );
		}

		return $sanitary_values;
	}

	public function wpdevinf_options_section_info() {

	}

  public function enable_infinite_scroll_0_callback() {
		printf(
			'<input type="checkbox" name="wpdevinf_options_option_name[enable_infinite_scroll_0]" id="enable_infinite_scroll_0" value="enable_infinite_scroll_0" %s><label for="enable_infinite_scroll_0">Enable infinite scroll.</label>',
			( isset( $this->wpdevinf_options_options['enable_infinite_scroll_0'] ) && $this->wpdevinf_options_options['enable_infinite_scroll_0'] === 'enable_infinite_scroll_0' ) ? 'checked' : ''
		);
	}

	public function post_navigation_container_0_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdevinf_options_option_name[post_navigation_container_0]" placeholder="nav.post-nav" id="post_navigation_container_0" value="%s"><label for="post_navigation_container_0">Location of single post navigation. Will hide the post navigation. Leave blank if navigation does not exist.</label>',
			isset( $this->wpdevinf_options_options['post_navigation_container_0'] ) ? esc_attr( $this->wpdevinf_options_options['post_navigation_container_0']) : ''
		);
	}

  public function wptouch_post_navigation_container_0_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdevinf_options_option_name[wptouch_post_navigation_container_0]" placeholder="nav.post-nav" id="wptouch_post_navigation_container_0" value="%s"><label for="wptouch_post_navigation_container_0">Location of WPTouch single post navigation. Will hide the post navigation. Leave blank if navigation does not exist.</label>',
			isset( $this->wpdevinf_options_options['wptouch_post_navigation_container_0'] ) ? esc_attr( $this->wpdevinf_options_options['wptouch_post_navigation_container_0']) : ''
		);
	}

	public function bottom_loading_location_container_1_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdevinf_options_option_name[bottom_loading_location_container_1]" placeholder="nav.post-nav" id="bottom_loading_location_container_1" value="%s"><label for="bottom_loading_location_container_1">Location in which to load the bottom, next post container. Can be the post navigation container. This option is REQUIRED.</label>',
			isset( $this->wpdevinf_options_options['bottom_loading_location_container_1'] ) ? esc_attr( $this->wpdevinf_options_options['bottom_loading_location_container_1']) : ''
		);
	}

  public function wptouch_bottom_loading_location_container_1_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdevinf_options_option_name[wptouch_bottom_loading_location_container_1]" placeholder="nav.post-nav" id="wptouch_bottom_loading_location_container_1" value="%s"><label for="wptouch_bottom_loading_location_container_1">Location in which to load the bottom, next post container. Can be the post navigation container. This option is REQUIRED.</label>',
			isset( $this->wpdevinf_options_options['wptouch_bottom_loading_location_container_1'] ) ? esc_attr( $this->wpdevinf_options_options['wptouch_bottom_loading_location_container_1']) : ''
		);
	}

	public function loading_background_color_2_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdevinf_options_option_name[loading_background_color_2]" placeholder="#333333" id="loading_background_color_2" value="%s"><label for="loading_background_color_2">If there is no featured image for the next or previous posts, this is the background color that will display. Default is #333333.</label>',
			isset( $this->wpdevinf_options_options['loading_background_color_2'] ) ? esc_attr( $this->wpdevinf_options_options['loading_background_color_2']) : ''
		);
	}

	public function enable_comment_buttons_3_callback() {
		printf(
			'<input type="checkbox" name="wpdevinf_options_option_name[enable_comment_buttons_3]" id="enable_comment_buttons_3" value="enable_comment_buttons_3" %s><label for="enable_comment_buttons_3">Enable buttons that will display Facebook or Disqus comments, if clicked, in a modal.</label>',
			( isset( $this->wpdevinf_options_options['enable_comment_buttons_3'] ) && $this->wpdevinf_options_options['enable_comment_buttons_3'] === 'enable_comment_buttons_3' ) ? 'checked' : ''
		);
	}

	public function facebook_comments_container_4_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdevinf_options_option_name[facebook_comments_container_4]" placeholder="#fb-comments" id="facebook_comments_container_4" value="%s"><label for="facebook_comments_container_4">Facebook comments container. Must be used to replace the Facebook comments for button usage.</label>',
			isset( $this->wpdevinf_options_options['facebook_comments_container_4'] ) ? esc_attr( $this->wpdevinf_options_options['facebook_comments_container_4']) : ''
		);
	}

	public function disqus_comments_container_5_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdevinf_options_option_name[disqus_comments_container_5]" placeholder="#comments" id="disqus_comments_container_5" value="%s"><label for="disqus_comments_container_5">Disqus comments container. Must be used to replace the Disqus comments for button usage.</label>',
			isset( $this->wpdevinf_options_options['disqus_comments_container_5'] ) ? esc_attr( $this->wpdevinf_options_options['disqus_comments_container_5']) : ''
		);
	}

	public function page_refresh_transition_time_6_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdevinf_options_option_name[page_refresh_transition_time_6]" placeholder="3000" id="page_refresh_transition_time_6" value="%s"><label for="page_refresh_transition_time_6">The amount of time it takes to load next post. Default is 3000 (3 seconds).</label>',
			isset( $this->wpdevinf_options_options['page_refresh_transition_time_6'] ) ? esc_attr( $this->wpdevinf_options_options['page_refresh_transition_time_6']) : ''
		);
	}

}
if ( is_admin() )
	$wpdevinf_options = new WpdevinfOptions();
