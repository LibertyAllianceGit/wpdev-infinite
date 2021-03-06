/**********
ScrollSpy.js
**********/
!function(t){function n(n,e,o,r){var l=t();return t.each(i,function(t,i){var u=i.offset().top,c=i.offset().left,f=c+i.width(),a=u+i.height(),s=!(c>e||r>f||u>o||n>a);s&&l.push(i)}),l}function e(){++f;var e=l.scrollTop(),o=l.scrollLeft(),r=o+l.width(),i=e+l.height(),c=n(e+a.top,r+a.right,i+a.bottom,o+a.left);t.each(c,function(t,n){var e=n.data("scrollSpy:ticks");"number"!=typeof e&&n.triggerHandler("scrollSpy:enter"),n.data("scrollSpy:ticks",f)}),t.each(u,function(t,n){var e=n.data("scrollSpy:ticks");"number"==typeof e&&e!==f&&(n.triggerHandler("scrollSpy:exit"),n.data("scrollSpy:ticks",null))}),u=c}function o(){l.trigger("scrollSpy:winSize")}function r(t,n,e){var o,r,l,i=null,u=0;e||(e={});var c=function(){u=e.leading===!1?0:s(),i=null,l=t.apply(o,r),o=r=null};return function(){var f=s();u||e.leading!==!1||(u=f);var a=n-(f-u);return o=this,r=arguments,0>=a?(clearTimeout(i),i=null,u=f,l=t.apply(o,r),o=r=null):i||e.trailing===!1||(i=setTimeout(c,a)),l}}var l=t(window),i=[],u=[],c=!1,f=0,a={top:0,right:0,bottom:0,left:0},s=Date.now||function(){return(new Date).getTime()};t.scrollSpy=function(n,o){n=t(n),n.each(function(n,e){i.push(t(e))}),o=o||{throttle:100},a.top=o.offsetTop||0,a.right=o.offsetRight||0,a.bottom=o.offsetBottom||0,a.left=o.offsetLeft||0;var u=r(e,o.throttle||100),f=function(){t(document).ready(u)};return c||(l.on("scroll",f),l.on("resize",f),c=!0),setTimeout(f,0),n},t.winSizeSpy=function(n){return t.winSizeSpy=function(){return l},n=n||{throttle:100},l.on("resize",r(o,n.throttle||100))},t.fn.scrollSpy=function(n){return t.scrollSpy(t(this),n)}}(jQuery);

/**********
AutoLoad.js by http://wpdevelopers.com
**********/

// Run jQuery in noConflict Mode
jQuery.noConflict();

// Setup Global Variables
var nav_container = wpdevinf_vars.navcontainer;
var topload_type = wpdevinf_vars.topnavtype;
var topload_location = wpdevinf_vars.topnavplace;
var toptrig_location = wpdevinf_vars.topnavtrig;
var botload_type = wpdevinf_vars.botnavtype;
var botload_location = wpdevinf_vars.botnavplace;
var comment_btns = wpdevinf_vars.commentbtns;
var fb_comments = wpdevinf_vars.fbbuttons;
var dq_comments = wpdevinf_vars.dqbuttons;
var loading_img = wpdevinf_vars.loadingimg;
var modalsize = wpdevinf_vars.modalsize;

/**
On Document Load Run Functions
**/
jQuery(document).ready(function() {
  // Add Elements
  wpdevinf_add_elements();
  // Hide Post Nav
  wpdevinf_hide_postnav();
  // Load Comment Buttons
  wpdevinf_comment_buttons();
	// Start Scrollspy
	initialise_Scrollspy();
});

/**
Add Elements
**/
function wpdevinf_add_elements() {
  // Setup Next & Previous Post Images
	var post_prev_img = jQuery('#wpdevinf-info-previous').attr('data-img');

	// If Exists/Doesn't Exist Previous Image Logic
	if (post_prev_img === undefined || post_prev_img === null && loading_img == undefined || loading_img == null) {
	     post_prev_image = '';
	} else {
			 post_prev_image = ' style="background: url(\'' + post_prev_img + '\') no-repeat; background-color: #fff;"';
	}

	// Add Bottom Loading Bar
	var previoustitle = jQuery('#wpdevinf-info-previous').text();
	// If Text Doesn't Exist
	if(previoustitle === undefined || previoustitle === null || previoustitle === '') {
		// Don't do anything
	// If Text Exists
	} else {
    if(botload_type == 3) {
      jQuery(botload_location).after('<div class="wpdevinf-bottom"' + post_prev_image + '><div id="wpdevinf-bottom-inner" style="color: #fff;"><h1 class="wpdevinf-bottom-title">' + previoustitle + '</h1><div class="wpdevinf-bottom-label"><span class="wpdevinf-bottom-loading-previous">Loading Previous Article</span> <span class="wpdevinf-loading wpdevinf-loading-previous"></span></div><div id="wpdevinf-progress-bottom"><div></div></div><hr style="height: 0" class="wpdevinf-post-divider wpdevinf-post-divider-bottom" />');
    }
    if(botload_type == 2) {
      jQuery(botload_location).after('<hr style="height: 1px" class="wpdevinf-post-divider wpdevinf-post-divider-bottom" />');
      jQuery('body').after('<div class="wpdevinf-bottom"' + post_prev_image + '><div id="wpdevinf-bottom-inner" style="color: #fff;"><h1 class="wpdevinf-bottom-title">' + previoustitle + '</h1><div class="wpdevinf-bottom-label"><span class="wpdevinf-bottom-loading-previous">Loading Previous Article</span> <span class="wpdevinf-loading wpdevinf-loading-previous"></span></div><div id="wpdevinf-progress-bottom"><div></div></div>');
    }
    if(botload_type == 1) {
      jQuery(botload_location).after('<hr style="height: 0" class="wpdevinf-post-divider wpdevinf-post-divider-bottom" />');
      jQuery('body').after('<div class="wpdevinf-bottom"' + post_prev_image + '><div id="wpdevinf-bottom-inner" style="color: #fff;"><h1 class="wpdevinf-bottom-title">' + previoustitle + '</h1><div class="wpdevinf-bottom-label"><span class="wpdevinf-bottom-loading-previous">Loading Previous Article</span> <span class="wpdevinf-loading wpdevinf-loading-previous"></span></div><div id="wpdevinf-progress-bottom"><div></div></div>');
    }
	}

  // Bottom Load Type Animations
  if(botload_type == 1 || botload_type == 2) {
    var bottomheight = jQuery('.wpdevinf-bottom').height();
    jQuery('.wpdevinf-bottom').css('z-index', '9999');
    jQuery('.wpdevinf-bottom').css('left', '0');
  }

  if(botload_type == 1) {
    jQuery('.wpdevinf-bottom').css('position', 'relative');
    jQuery('.wpdevinf-bottom').css('bottom', '0');
    jQuery('.wpdevinf-bottom').css('margin-bottom', -bottomheight);
  }

  if(botload_type == 2) {
    jQuery('.wpdevinf-bottom').css('position', 'fixed');
    jQuery('.wpdevinf-bottom').css('bottom', -bottomheight);
  }

}

/**
Hide Post Navigation
**/
function wpdevinf_hide_postnav() {
  // Hide Post Navigation (Once we get info from it)
	if(nav_container === undefined || nav_container === null) {
		// Don't do anything
	} else {
		jQuery(nav_container).hide();
	}
}

/**
Replace Comments with Buttons
**/
function wpdevinf_comment_buttons() {
  // Create Comment Buttons
	if(comment_btns === undefined || comment_btns === null) {
		// Do nothing with buttons.
	} else {
		// Create Facebook Buttons
		if(fb_comments === undefined || fb_comments === null) {
			// Do nothing for Facebook buttons.
		} else {
			var fbcomments = jQuery(fb_comments).html();
			jQuery('#fbcomments-dialog').html(fbcomments);
			jQuery(fb_comments).before('<div id="wpdevinf-fbcomments-cont"><button id="wpdevinf-fb-btn">Comment via Facebook</button></div>');
			jQuery('#wpdevinf-fb-btn').on('click', function() {
				jQuery( "#fbcomments-dialog" ).dialog({
					minWidth: modalsize
				});
			});
			jQuery(fb_comments).remove();
		}

		// Create Disqus Buttons
		if(dq_comments === undefined || dq_comments === null) {
			// Do nothing for Disqus buttons.
		} else {
			var dqcomments = jQuery(dq_comments).html();
			jQuery('#dqcomments-dialog').html(dqcomments);
			jQuery(dq_comments).before('<div id="wpdevinf-dqcomments-cont"><button id="wpdevinf-dq-btn">Comment via Disqus</button></div>');
			jQuery('#wpdevinf-dq-btn').on('click', function() {
				jQuery( "#dqcomments-dialog" ).dialog({
					minWidth: modalsize
				});
			});
			jQuery(dq_comments).remove();
		}
	}
}

/**
Track Scroll Movement
**/
function initialise_Scrollspy() {
		// Watch Bottom Post Divider Exit
		jQuery('.wpdevinf-post-divider.wpdevinf-post-divider-bottom').on('scrollSpy:enter', functionprevious);
    // Watch to Add Top
    jQuery(toptrig_location).on('scrollSpy:exit', functionheader);
		// Enable Element Watching
    jQuery('.wpdevinf-post-divider').scrollSpy();
    jQuery(toptrig_location).scrollSpy();
}

var flagMarker = true;

/**
Add Head Element
**/
function functionheader() {
  if(flagMarker === true) {
    // Setup Next & Previous Post Images
  	var post_next_img = jQuery('#wpdevinf-info-next').attr('data-img');

    // If Exists/Doesn't Exist Next Image Logic
  	if (post_next_img === undefined || post_next_img === null && loading_img == undefined || loading_img == null) {
  	     post_next_image = '';
  	} else {
  			 post_next_image = ' style="background: url(\'' + post_next_img + '\') no-repeat; background-color: #fff;"';
  	}

    // Add Top Loading Bar
  	var nexttitle = jQuery('#wpdevinf-info-next').text();
  	// If Text Doesn't Exist
  	if(nexttitle === undefined || nexttitle === null || nexttitle === '') {
  		// Don't do anything
  	// If Text Exists
  	} else {
      if(topload_type == 1 || topload_type == 2) {
        console.log(nexttitle);
        jQuery('body').before('<div class="wpdevinf-top"' + post_next_image + '><div id="wpdevinf-top-inner" style="color: #fff;"><h1 class="wpdevinf-top-title">' + nexttitle + '</h1><div class="wpdevinf-top-label"><span class="wpdevinf-top-loading-next">Loading Next Article</span> <span class="wpdevinf-loading wpdevinf-loading-next"></span></div><div id="wpdevinf-progress-top"><div></div></div></div></div><hr style="height: 1px" class="wpdevinf-post-divider wpdevinf-post-divider-top" />');
      } else {
        jQuery('body').before('<hr style="height: 0" class="wpdevinf-post-divider wpdevinf-post-divider-top" /><div class="wpdevinf-top"' + post_next_image + '><div id="wpdevinf-top-inner" style="color: #fff;"><h1 class="wpdevinf-top-title">' + nexttitle + '</h1><div class="wpdevinf-top-label"><span class="wpdevinf-top-loading-next">Loading Next Article</span> <span class="wpdevinf-loading wpdevinf-loading-next"></span></div><div id="wpdevinf-progress-top"><div></div></div></div></div>');
      }
  	}

    // Top Load Type Animations
    if(topload_type == 1 || botload_type == 2) {
      var topheight = jQuery('.wpdevinf-top').height();
      jQuery('.wpdevinf-top').css('z-index', '9999');
    }

    if(topload_type == 1) {
      jQuery('.wpdevinf-top').css('position', 'relative');
      jQuery('.wpdevinf-top').css('margin-top', -topheight);
    }

    if(topload_type == 2) {
      jQuery('.wpdevinf-top').css('position', 'fixed');
      jQuery('.wpdevinf-top').css('top', -topheight);
    }

    startscrollSpy();
    flagMarker = false;
  }

}

function startscrollSpy() {
  // Watch Top Post Divider Enter
  jQuery('.wpdevinf-post-divider.wpdevinf-post-divider-top').on('scrollSpy:enter', functionnext);
  jQuery('.wpdevinf-post-divider').scrollSpy();
}

/**
Load Next or Previous Post
**/
// Load Next Post
function functionnext() {
	// Get Next URL
	var post_nav = jQuery('#wpdevinf-info-next').attr('data-url');
	// If We Have URL
	if ( post_nav ) {
    if(topload_type == 1) {
      jQuery('.wpdevinf-top').animate({marginTop:'0px'}, 500);
    }
    if(topload_type == 2) {
      jQuery('.wpdevinf-top').animate({top:'0px'}, 500);
    }
		// Run on a delay
		var timeoutnext = setTimeout(function() {
		  jQuery(location).attr('href', post_nav).delay( 3000 );
		}, 3000);
		// Animate bar
		if(topload_type == 3) {
      jQuery('.wpdevinf-top').effect( 'bounce', {times:1,distance:-20}, 500 );
    }
		// Add Materials
		jQuery('.wpdevinf-loading-next').html('Cancel').addClass('wpdevinf-loading-next-cancel');
		jQuery('.wpdevinf-top-loading-next').html('Loading Next Post in <span class="wpdev-top-loading-count"></span>');
    var counter = 3;
    var interval = setInterval(function() {
        counter--;
        jQuery('.wpdev-top-loading-count').html(counter);
        if (counter == 0) {
            // Display a login box
            clearInterval(interval);
        }
    }, 1000);
		jQuery('#wpdevinf-progress-top').html('<div></div>');
		// Progress Bar
		progress(100, jQuery('#wpdevinf-progress-top'));
		// On Cancel Click
		jQuery('.wpdevinf-loading-next-cancel').on('click', function(){
			functionnextstop();
		});
		// Watch for scroll down
		jQuery('.wpdevinf-post-divider.wpdevinf-post-divider-top').on('scrollSpy:exit', functionnextstop);
    jQuery('.wpdevinf-post-divider').scrollSpy();
		// If scroll down, stop delay from running function
		function functionnextstop() {
      var topheight = jQuery('.wpdevinf-top').height();
      if(topload_type == 1) {
        jQuery('.wpdevinf-top').animate({marginTop:-topheight}, 500);
      }
      if(topload_type == 2) {
        jQuery('.wpdevinf-top').animate({top:-topheight}, 500);
      }
			// Replace Materials
			jQuery('.wpdevinf-loading-next').html('<a href="' + post_nav + '">Load Next Post</a>').removeClass('wpdevinf-loading-next-cancel').addClass('wpdevinf-loading-next-load');
			jQuery('.wpdevinf-top-loading-next').html('');
			jQuery('#wpdevinf-progress-top').html('');
			clearTimeout(timeoutnext);
		}
	// If no next URL
	} else {
		// Remove top bar
		jQuery('.wpdevinf-top').hide();
	}
}

// Load Next Post
function functionprevious() {
	// Get Next URL
	var post_nav = jQuery('#wpdevinf-info-previous').attr('data-url');
	// If We Have URL
	if ( post_nav ) {
    if(botload_type == 1) {
      jQuery('.wpdevinf-bottom').animate({marginBottom:'0px'}, 500);
    }
    if(botload_type == 2) {
      jQuery('.wpdevinf-bottom').animate({bottom:'0px'}, 500);
    }
		// Run on a delay
		var timeoutprev = setTimeout(function() {
		  jQuery(location).attr('href', post_nav).delay( 3000 );
		}, 3000);
		// Animate bar
    if(botload_type == 3) {
      jQuery('.wpdevinf-bottom').effect( 'bounce', {times:1,distance:-20}, 500 );
    }
		// Add Materials
		jQuery('.wpdevinf-loading-previous').html('Cancel').addClass('wpdevinf-loading-previous-cancel');
		jQuery('.wpdevinf-bottom-loading-previous').html('Loading Previous Post in <span class="wpdev-bottom-loading-count"></span>');
    var counter = 3;
    var interval = setInterval(function() {
        counter--;
        jQuery('.wpdev-bottom-loading-count').html(counter);
        if (counter == 0) {
            // Display a login box
            clearInterval(interval);
        }
    }, 1000);
		jQuery('#wpdevinf-progress-bottom').html('<div></div>');
		// Progress Bar
		progress(100, jQuery('#wpdevinf-progress-bottom'));
		// On Cancel Click
		jQuery('.wpdevinf-loading-previous-cancel').on('click', function(){
			functionpreviousstop();
		});
		// Watch for scroll up, stop delay from running function
		jQuery('.wpdevinf-post-divider.wpdevinf-post-divider-bottom').on('scrollSpy:exit', functionpreviousstop);
    jQuery('.wpdevinf-post-divider').scrollSpy();
		// If scroll up, stop delay from running function
		function functionpreviousstop() {
      var bottomheight = jQuery('.wpdevinf-bottom').outerHeight();
      if(botload_type == 1) {
        jQuery('.wpdevinf-bottom').animate({marginBottom:-bottomheight}, 500);
      }
      if(botload_type == 2) {
        jQuery('.wpdevinf-bottom').animate({bottom:-bottomheight}, 500);
      }
			// Remove Materials
			jQuery('.wpdevinf-loading-previous').html('<a href="' + post_nav + '">Load Previous Post</a>').removeClass('wpdevinf-loading-previous-cancel').addClass('wpdevinf-loading-previous-load');
			jQuery('.wpdevinf-bottom-loading-previous').html('');
			jQuery('#wpdevinf-progress-bottom').html('');
			clearTimeout(timeoutprev);
		}
	// If no next URL
	} else {
		// Hide bottom bar
		jQuery('.wpdevinf-bottom').hide();
	}
}

// Create Progress Bar
function progress(percent, $element) {
		var progressBarWidth = percent * $element.width() / 100;
		$element.find('div').animate({ width: progressBarWidth }, 3000);
}
