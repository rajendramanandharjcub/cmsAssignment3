<?php


	add_action('admin_menu', 'tweet_my_feed_create_menu');


	function tweet_my_feed_create_menu() {
		//create new top-level menu
		add_menu_page('Tweet My Feed Plugin Settings', 'Tweet My Feed Settings', 'administrator', __FILE__, 'tweet_my_feed_settings_page' , plugins_url('/images/icon.png', __FILE__) );

		//call register settings function
		add_action( 'admin_init', 'register_tweet_my_feed_settings' );
	}

	