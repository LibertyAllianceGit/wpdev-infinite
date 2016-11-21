/**********
ScrollSpy.js
**********/
!function(t){function n(n,e,o,r){var l=t();return t.each(i,function(t,i){var u=i.offset().top,c=i.offset().left,f=c+i.width(),a=u+i.height(),s=!(c>e||r>f||u>o||n>a);s&&l.push(i)}),l}function e(){++f;var e=l.scrollTop(),o=l.scrollLeft(),r=o+l.width(),i=e+l.height(),c=n(e+a.top,r+a.right,i+a.bottom,o+a.left);t.each(c,function(t,n){var e=n.data("scrollSpy:ticks");"number"!=typeof e&&n.triggerHandler("scrollSpy:enter"),n.data("scrollSpy:ticks",f)}),t.each(u,function(t,n){var e=n.data("scrollSpy:ticks");"number"==typeof e&&e!==f&&(n.triggerHandler("scrollSpy:exit"),n.data("scrollSpy:ticks",null))}),u=c}function o(){l.trigger("scrollSpy:winSize")}function r(t,n,e){var o,r,l,i=null,u=0;e||(e={});var c=function(){u=e.leading===!1?0:s(),i=null,l=t.apply(o,r),o=r=null};return function(){var f=s();u||e.leading!==!1||(u=f);var a=n-(f-u);return o=this,r=arguments,0>=a?(clearTimeout(i),i=null,u=f,l=t.apply(o,r),o=r=null):i||e.trailing===!1||(i=setTimeout(c,a)),l}}var l=t(window),i=[],u=[],c=!1,f=0,a={top:0,right:0,bottom:0,left:0},s=Date.now||function(){return(new Date).getTime()};t.scrollSpy=function(n,o){n=t(n),n.each(function(n,e){i.push(t(e))}),o=o||{throttle:100},a.top=o.offsetTop||0,a.right=o.offsetRight||0,a.bottom=o.offsetBottom||0,a.left=o.offsetLeft||0;var u=r(e,o.throttle||100),f=function(){t(document).ready(u)};return c||(l.on("scroll",f),l.on("resize",f),c=!0),setTimeout(f,0),n},t.winSizeSpy=function(n){return t.winSizeSpy=function(){return l},n=n||{throttle:100},l.on("resize",r(o,n.throttle||100))},t.fn.scrollSpy=function(n){return t.scrollSpy(t(this),n)}}(jQuery);

/**********
AutoLoad.js by http://wpdevelopers.com
**********/

// Setup Global Variables
var nav_container = wpdevinf_vars.navcontainer;
var load_location = wpdevinf_vars.navlocation;

// Run jQuery in noConflict Mode
jQuery.noConflict();

/**
On Document Load Run Functions
**/
jQuery(document).ready(function() {

	// Setup Next & Previous Post Images
	var post_next_img = jQuery('#wpdevinf-info-next').attr('data-img');
	var post_prev_img = jQuery('#wpdevinf-info-previous').attr('data-img');

	// If Exists/Doesn't Exist Next Image Logic
	if (post_next_img === undefined || post_next_img === null) {
	     post_next_image = '';
	} else {
			 post_next_image = ' style="background: url(\'' + post_next_img + '\') no-repeat;"';
	}

	// If Exists/Doesn't Exist Previous Image Logic
	if (post_prev_img === undefined || post_prev_img === null) {
	     post_prev_image = '';
	} else {
			 post_prev_image = ' style="background: url(\'' + post_prev_img + '\') no-repeat;"';
	}

	// Add Top Loading Bar
	var nexttitle = jQuery('#wpdevinf-info-next').text();
	// If Text Doesn't Exist
	if(nexttitle === undefined || nexttitle === null) {
		// Don't do anything
	// If Text Exists
	} else {
		jQuery('body').before('<div class="wpdevinf-top"' + post_next_image + '><hr style="height: 0" class="wpdevinf-post-divider wpdevinf-post-divider-top" /><div id="wpdevinf-top-inner" style="color: #fff;"><h1 class="wpdevinf-top-title">' + nexttitle + '</h1><div class="wpdevinf-top-label"><span class="wpdevinf-top-loading-next">Loading Next Article</span> <span class="wpdevinf-loading wpdevinf-loading-next"></span></div><div id="wpdevinf-progress-top"><div></div></div></div></div>');
	}

	// Add Bottom Loading Bar
	var previoustitle = jQuery('#wpdevinf-info-previous').text();
	// If Text Doesn't Exist
	if(previoustitle === undefined || previoustitle === null) {
		// Don't do anything
	// If Text Exists
	} else {
		jQuery(load_location).after('<div class="wpdevinf-bottom"' + post_prev_image + '><hr style="height: 0" class="wpdevinf-post-divider wpdevinf-post-divider-bottom" /><div id="wpdevinf-bottom-inner" style="color: #fff;"><h1 class="wpdevinf-bottom-title">' + previoustitle + '</h1><div class="wpdevinf-bottom-label"><span class="wpdevinf-bottom-loading-previous">Loading Previous Article</span> <span class="wpdevinf-loading wpdevinf-loading-previous"></span></div><div id="wpdevinf-progress-bottom"><div></div></div>');
	}

	// Hide Post Navigation (Once we get info from it)
	if(nav_container === undefined || nav_container === null) {
		// Don't do anything
	} else {
		jQuery(nav_container).hide();
	}

	// Offset top bar, so that it doesn't display unless we scroll up
	jQuery.fn.scrollView = function () {
	    return this.each(function () {
	        jQuery('html, body').animate({
	            scrollTop: jQuery(this).offset().top
	        }, 0);
	    });
	}

	// Run Offset Top Bar Function
	jQuery('body').scrollView();

	// Create Comment Buttons
	if(wpdevinf_vars.commentbtns === undefined || wpdevinf_vars.commentbtns === null) {
		// Do nothing with buttons.
	} else {
		// Create Facebook Buttons
		if(wpdevinf_vars.fbbuttons === undefined || wpdevinf_vars.fbbuttons === null) {
			// Do nothing for Facebook buttons.
		} else {
			var fbcomments = jQuery(wpdevinf_vars.fbbuttons).html();
			jQuery('#fbcomments-dialog').html(fbcomments);
			jQuery(wpdevinf_vars.fbbuttons).before('<div id="wpdevinf-fbcomments-cont"><button id="wpdevinf-fb-btn">Comment via Facebook</button></div>');
			jQuery('#wpdevinf-fb-btn').on('click', function() {
				jQuery( "#fbcomments-dialog" ).dialog({
					minWidth: wpdevinf_vars.modalsize
				});
			});
			jQuery(wpdevinf_vars.fbbuttons).remove();
		}

		// Create Disqus Buttons
		if(wpdevinf_vars.dqbuttons === undefined || wpdevinf_vars.dqbuttons === null) {
			// Do nothing for Disqus buttons.
		} else {
			var dqcomments = jQuery(wpdevinf_vars.dqbuttons).html();
			jQuery('#dqcomments-dialog').html(dqcomments);
			jQuery(wpdevinf_vars.dqbuttons).before('<div id="wpdevinf-dqcomments-cont"><button id="wpdevinf-dq-btn">Comment via Disqus</button></div>');
			jQuery('#wpdevinf-dq-btn').on('click', function() {
				jQuery( "#dqcomments-dialog" ).dialog({
					minWidth: wpdevinf_vars.modalsize
				});
			});
			jQuery(wpdevinf_vars.dqbuttons).remove();
		}
	}
	// Start Scrollspy
	initialise_Scrollspy();
});

/**
Track Scroll Movement
**/
function initialise_Scrollspy() {
		// Watch Top Post Divider Enter
    jQuery('.wpdevinf-post-divider.wpdevinf-post-divider-top').on('scrollSpy:enter', functionnext);
		// Watch Bottom Post Divider Exit
		jQuery('.wpdevinf-post-divider.wpdevinf-post-divider-bottom').on('scrollSpy:enter', functionprevious);
		// Enable Element Watching
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
		// Run on a delay
		var timeoutnext = setTimeout(function() {
		  jQuery(location).attr('href', post_nav).delay( wpdevinf_vars.transtimer );
		}, wpdevinf_vars.transtimer);
		// Animate bar
		jQuery('.wpdevinf-top').effect( 'bounce', {times:1,distance:-20}, 500 );
		// Add Materials
		jQuery('.wpdevinf-loading-next').html('Cancel').addClass('wpdevinf-loading-next-cancel');
		jQuery('.wpdevinf-top-loading-next').html('Loading Next Post');
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
		// Run on a delay
		var timeoutprev = setTimeout(function() {
		  jQuery(location).attr('href', post_nav).delay( wpdevinf_vars.transtimer );
		}, wpdevinf_vars.transtimer);
		// Animate bar
		jQuery('.wpdevinf-bottom').effect( 'bounce', {times:1,distance:-20}, 500 );
		// Add Materials
		jQuery('.wpdevinf-loading-previous').html('Cancel').addClass('wpdevinf-loading-previous-cancel');
		jQuery('.wpdevinf-bottom-loading-previous').html('Loading Previous Post');
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
		$element.find('div').animate({ width: progressBarWidth }, wpdevinf_vars.transtimer);
}
