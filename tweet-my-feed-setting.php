<?php


	add_action('admin_menu', 'tweet_my_feed_create_menu');


	function tweet_my_feed_create_menu() {
		//create new top-level menu
		add_menu_page('Tweet My Feed Plugin Settings', 'Tweet My Feed Settings', 'administrator', __FILE__, 'tweet_my_feed_settings_page' , plugins_url('/images/icon.png', __FILE__) );

		//call register settings function
		add_action( 'admin_init', 'register_tweet_my_feed_settings' );
	}

	function register_tweet_my_feed_settings() {
		//register our settings
		register_setting( 'tweet-my-feed-settings-group', 'screenName' );
		register_setting( 'tweet-my-feed-settings-group', 'tweetsToShow' );
		register_setting( 'tweet-my-feed-settings-group', 'oauthAccessToken' );
		register_setting( 'tweet-my-feed-settings-group', 'oauthAccessTokenSecret' );
		register_setting( 'tweet-my-feed-settings-group', 'oauthConsumerKey' );
		register_setting( 'tweet-my-feed-settings-group', 'oauthConsumerSecret' );
	}


	function tweet_my_feed_settings_page() {
	?>


	<div class="wrap">
	<h1>Tweet My Feed</h1>

	<form method="post" action="options.php">
	    <?php settings_fields( 'tweet-my-feed-settings-group' ); ?>
	    <?php do_settings_sections( 'tweet-my-feed-settings-group' ); ?>
	    <table class="form-table">

	        <tr valign="top">
		        <th scope="row">Screen Name</th>
		        <td><input type="text" name="screenName" value="<?php echo esc_attr( get_option('screenName') ); ?>" /></td>
	        </tr>
	        <tr valign="top">
		        <th scope="row">Tweets to show</th>
		        <td><input type="text" name="tweetsToShow" value="<?php echo esc_attr( get_option('tweetsToShow') ); ?>" /></td>
	        </tr>

	        <tr valign="top">
		        <th scope="row">oauth Access Token</th>
		        <td><input type="text" name="oauthAccessToken" value="<?php echo esc_attr( get_option('oauthAccessToken') ); ?>" /></td>
	        </tr>

	        <tr valign="top">
		        <th scope="row">oauth Access Secret</th>
		        <td><input type="text" name="oauthAccessTokenSecret" value="<?php echo esc_attr( get_option('oauthAccessTokenSecret') ); ?>" /></td>
	        </tr>

	        <tr valign="top">
		        <th scope="row">oauth Consumer Key</th>
		        <td><input type="text" name="oauthConsumerKey" value="<?php echo esc_attr( get_option('oauthConsumerKey') ); ?>" /></td>
	        </tr>

	        <tr valign="top">
		        <th scope="row">oauth Consumer Secret</th>
		        <td><input type="text" name="oauthConsumerSecret" value="<?php echo esc_attr( get_option('oauthConsumerSecret') ); ?>" /></td>
	        </tr>
	         
	    </table>
	    
	    <?php submit_button(); ?>

	</form>

	</div>
	<?php 

}