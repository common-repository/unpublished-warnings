<?php
/*
Plugin Name: Unpublished Warnings
Plugin URI: http://wordpress.org/extend/plugins/unpublished-warnings/
Description: This plugin will warn you when you're looking at an unpublished post that you just happen to be able to see.
Author: William Sullivan
Version: 0.4.4
Author URI: https://enkrates.com
*/

// UW_add_my_stylesheet() adapted from
// http://codex.wordpress.org/Function_Reference/wp_enqueue_style

function UW_add_my_stylesheet() {
    $myStyleUrl = plugins_url( 'unpublished_warnings.css', __FILE__ );
    $myStyleFile = plugin_dir_path( __FILE__ ) . '/unpublished_warnings.css';
    if ( file_exists( $myStyleFile ) ) {
        wp_register_style( 'unpublished_warnings', $myStyleUrl );
        wp_enqueue_style( 'unpublished_warnings');
    }
}

function UW_add_my_script() {
	$myScriptURL = plugins_url( 'unpublished_warnings.js', __FILE__ );
	$myScriptFile = plugin_dir_path( __FILE__ ) . '/unpublished_warnings.js';
	if ( file_exists($myScriptFile) ) {
		wp_register_script( 'unpublished_warnings',
							$myScriptURL,
							array( 'jquery' ) );
		wp_enqueue_script('unpublished_warnings');
	}
}

function UW_is_unpublished() {
	return !( isset( $_GET['preview'] ) && $_GET['preview'] == 'true' )
			&& get_post_status( get_the_ID() ) != 'publish';
}

function UW_prepend_warning( $content ) {
	if ( UW_is_unpublished() ){
		$warning_message = 'This post has not yet been published. ' .
							'You can see it because you are logged ' .
							'in with a privileged account.';
		$hide_me_message = 'Click here to hide this warning.';
		$content = "<div id='unpublished-warnings'><p>" .
					wptexturize( $warning_message ) .
					"</p>\n<p>" .
		 			wptexturize( $hide_me_message ) .
		 			"</p>\n</div>\n" . $content;
	}
	return $content;
}

add_action( 'wp_print_styles', 'UW_add_my_stylesheet' );
add_action( 'wp_enqueue_scripts', 'UW_add_my_script' );
add_filter( 'the_content', 'UW_prepend_warning' );

