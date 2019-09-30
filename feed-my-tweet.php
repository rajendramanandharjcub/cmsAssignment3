<?php
/**
 * @package download-contact-to-csv
 */
/*
 Plugin Name: feed-my-tweet
  Plugin URI: https://rajendram.sgedu.site/
  Description: Download csv file from contact page.
  Version: 1.0.0
  Author: Rajendra Kumar Manandhar
  Author URI: https://rajendram.sgedu.site/
  License: GPLv2 or later
  Text Domain: feed-my-tweet
 */


defined('ABSPATH') or die('You cant access this file');

 // including tweet-my-feed plugin settings menu
include("tweet-my-feed-setting.php");

add_shortcode( 'mi-twitter-feed', 'twitter_feed_shortcode');
function twitter_feed_shortcode($atts)
{
    ob_start();
    extract(shortcode_atts(array(
        'tweets_to_show'=> '',
    ), $atts));
    $count = get_option('tweetsToShow'); // How many tweets to output
    $retweets = 0; // 0 to exclude, 1 to include
    $screen_name = get_option('screenName');
    // Populate these with the keys/tokens you just obtained
    $oauthAccessToken = get_option('oauthAccessToken');
    $oauthAccessTokenSecret = get_option('oauthAccessTokenSecret');
    $oauthConsumerKey = get_option('oauthConsumerKey');
    $oauthConsumerSecret = get_option('oauthConsumerSecret');

    // First we populate an array with the parameters needed by the API
    $oauth = array(
        'count' => $count,
        'include_rts' => $retweets,
        'oauth_consumer_key' => $oauthConsumerKey,
        'oauth_nonce' => time(),
        'oauth_signature_method' => 'HMAC-SHA1',
        'oauth_timestamp' => time(),
        'oauth_token' => $oauthAccessToken,
        'oauth_version' => '1.0',
        'tweet_mode' => 'extended'
    );

    $arr = array();
    foreach($oauth as $key => $val)
        $arr[] = $key.'='.rawurlencode($val);

    // Then we create an encypted hash of these values to prove to the API that they weren't tampered with during transfer
    $oauth['oauth_signature'] = base64_encode(hash_hmac('sha1', 'GET&'.rawurlencode('https://api.twitter.com/1.1/statuses/user_timeline.json').'&'.rawurlencode(implode('&', $arr)), rawurlencode($oauthConsumerSecret).'&'.rawurlencode($oauthAccessTokenSecret), true));

    $arr = array();
    foreach($oauth as $key => $val)
        $arr[] = $key.'="'.rawurlencode($val).'"';

    // Next we use Curl to access the API, passing our parameters and the security hash within the call
    $tweets = curl_init();
    curl_setopt_array($tweets, array(
        CURLOPT_HTTPHEADER => array('Authorization: OAuth '.implode(', ', $arr), 'Expect:'),
        CURLOPT_HEADER => false,
        CURLOPT_URL => 'https://api.twitter.com/1.1/statuses/user_timeline.json?tweet_mode=extended&count='.$count.'&include_rts='.$retweets,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
    ));
    $json = curl_exec($tweets);
    curl_close($tweets);
    $statuses = json_decode($json);

    $testing = wp_remote_get("https://api.twitter.com/1.1/statuses/user_timeline.json?tweet_mode=extended&count=".$count."&include_rts=".$retweets);
    // $json now contains the response from the Twitter API, which should include however many tweets we asked for.

        ?>

         <!-- twitter   -->
        
   <div class="container">
      <div class="col-md-4">
         <div class="panel panel-info">
            <div class="panel-heading">
               <h3 class="panel-title"><i class="fa fa-twitter-square" aria-hidden="true"></i>
                  Tweet My Feed
               </h3>
            </div>
            <?php
                    // Loop through them for output
                    foreach(json_decode($json) as $status) {
                     // Convert links back into actual links, otherwise they're just output as text
                        $enhancedStatus = htmlentities($status->full_text, ENT_QUOTES, 'UTF-8');
                        $enhancedStatus = preg_replace('/http:\/\/t.co\/([a-zA-Z0-9]+)/i', '<a href="http://t.co/$1">http://$1</a>', $enhancedStatus);
                        $enhancedStatus = preg_replace('/https:\/\/t.co\/([a-zA-Z0-9]+)/i', '<a href="https://t.co/$1">http://$1</a>', $enhancedStatus);
                        // Finally, output a simple paragraph containing the tweet and a link back to the Twitter account itself. You can format/style this as you like.
                        ?>
            <div class="panel-body">
                            
              <div class="card">
                <div class="card-body ">
                  <div class="card-header">
                    <img src="<?php echo $status->user->profile_image_url_https; ?>" class="img-circle card-img-left" style="-webkit-border-radius: initial; border-radius: 50%;"> 
                    <span class="card-title">
                    <a class="twitter-timeline" data-width="100%" href="https://twitter.com/intent/user?screen_name=<?php echo $screen_name; ?>" target="_blank">Tweets by @<?php echo $screen_name; ?>  </a>
                  </span>
                  </div>
                  
                  <div class="card-body text-center ">
                    <blockquote class="blockquote mb-0">
                    <p class="card-text">
                      <?php echo $enhancedStatus; ?>
                    </p>
                  </blockquote>
                    
                  </div>
                  <footer class="blockquote-footer">
                    via Twitter - <p><?php echo $status->user->name; ?>
                  </footer>
                   
                  
                </div>
        </div>

            </div>
            <?php 
            } 
          ?>
         </div>
      </div>
   </div>
        
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>

<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>


        <?php
    $content = ob_get_clean();
    return $content;
}


?>