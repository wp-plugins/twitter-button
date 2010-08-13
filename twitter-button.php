<?php
/*
Plugin Name: Twitter Button
Plugin URI: http://www.svil4ok.com/wordpress-plugins/twitter-button/
Description: Twitter Button is a WordPress plugin that includes a several features for creating interactive and user-friendly toolbar. <a href="http://www.svil4ok.com/wordpress-plugins/twitter-button/">More information here</a>.
Version: 0.1
Author: Svilen Popov
Author URI: http://www.svil4ok.com/

*/

define('TWITTER_BUTTON_TAG', '[twitter-button]');

if (!defined('ABSPATH')) {
	return ;
}

// Button Style
register_activation_hook(__FILE__,'tb_button_install'); 
register_deactivation_hook( __FILE__, 'tb_button_remove' );
function tb_button_install() {
	add_option("tb-button", 'vertical', '', 'yes');
}
function tb_button_remove() {
	delete_option('tb-button');
}


// Tweet Text
register_activation_hook(__FILE__,'tb_tweet_text_install'); 
register_deactivation_hook( __FILE__, 'tb_tweet_text_remove' );
function tb_tweet_text_install() {
	add_option("tb-tweet-text", '1', '', 'yes');
}
function tb_tweet_text_remove() {
	delete_option('tb-tweet-text');
}
register_activation_hook(__FILE__,'tb_tweet_text_custom_install'); 
register_deactivation_hook( __FILE__, 'tb_tweet_text_custom_remove' );
function tb_tweet_text_custom_install() {
	add_option("tb-tweet-text-custom", '', '', 'yes');
}
function tb_tweet_text_custom_remove() {
	delete_option('tb-tweet-text-custom');
}

// URL
register_activation_hook(__FILE__,'tb_url_install'); 
register_deactivation_hook( __FILE__, 'tb_url_remove' );
function tb_url_install() {
	add_option("tb-url", '1', '', 'yes');
}
function tb_url_remove() {
	delete_option('tb-url');
}
register_activation_hook(__FILE__,'tb_url_custom_install'); 
register_deactivation_hook( __FILE__, 'tb_url_custom_remove' );
function tb_url_custom_install() {
	add_option("tb-url-custom", '', '', 'yes');
}
function tb_url_custom_remove() {
	delete_option('tb-url-custom');
}

// Twitter Language
register_activation_hook(__FILE__,'tb_language_install'); 
register_deactivation_hook( __FILE__, 'tb_language_remove' );
function tb_language_install() {
	add_option("tb-language", 'en', '', 'yes');
}
function tb_language_remove() {
	delete_option('tb-language');
}

// Twitter Account
register_activation_hook(__FILE__,'tb_user_install'); 
register_deactivation_hook( __FILE__, 'tb_user_remove' );
function tb_user_install() {
	add_option("tb-user", '', '', 'yes');
}
function tb_user_remove() {
	delete_option('tb-user');
}

// Admin Language
register_activation_hook(__FILE__,'tb_admin_lang_install'); 
register_deactivation_hook( __FILE__, 'tb_admin_lang_remove' );
function tb_admin_lang_install() {
	add_option("tb-admin-lang", 'en', '', 'yes');
}
function tb_admin_lang_remove() {
	delete_option('tb-admin-lang');
}

// Method
register_activation_hook(__FILE__,'tb_method_install'); 
register_deactivation_hook( __FILE__, 'tb_method_remove' );
function tb_method_install() {
	add_option("tb-method", 'automatic', '', 'yes');
}
function tb_method_remove() {
	delete_option('tb-method');
}

function twitter_button() {
	global $post;
	$tb_button = (get_settings('tb-button') == "") ? 'vertical' : get_settings('tb-button');
	$tb_tweet_text = (get_settings('tb-tweet-text') == "1") ? '' : ' data-text="'. get_settings('tb-tweet-text-custom') .'"';
	$tb_url = (get_settings('tb-url') == "1") ? '' : ' data-url="'. get_settings('tb-url-custom') .'"';
	$tb_lang = (get_settings('tb-language') == "en") ? '' : ' data-lang="'. get_settings('tb-language') .'"';
	$tb_user = (get_settings('tb-user') == "") ? '' : ' data-via="'. get_settings('tb-user') .'"';
	
	$tweet_button = '<a href="http://twitter.com/share" class="twitter-share-button" data-count="'. $tb_button .'" '.$tb_user.$tb_lang.$tb_url.$tb_tweet_text.'>Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>';
	return $tweet_button;
}

function tweet_button($content) {
	$button = twitter_button();
	if (get_option('tb-method') == "shortcode") {
		return str_replace(TWITTER_BUTTON_TAG, $button, $content);
	}
	else if (get_option('tb-method') == "manual") {
		return $content;
	}
	else { 
		$content .= $button;
		return $content;
	}
}
add_filter('the_content', 'tweet_button');

function tb_include_admin() {  
	include('twitter-button-admin.php');  
}  
function tb_admin() {
	add_options_page("Twitter Button", "Twitter Button", 1, "twitter-button", "tb_include_admin");
}
add_action('admin_menu', 'tb_admin');

function tb_admin_register_head() {
	$siteurl = get_option('siteurl');
	$url = $siteurl . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '';
	echo "<!-- WordPress Twitter Button -->\n";
	echo "<link rel='stylesheet' type='text/css' href='$url/css/tb-admin.css' />\n";
	echo "<script type='text/javascript' src='$url/controllers/tb-tabs.js'></script>\n";
	echo "<!-- WordPress Twitter Button -->\n";
}
add_action('admin_head', 'tb_admin_register_head');

?>