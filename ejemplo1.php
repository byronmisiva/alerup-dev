<?php
/**
 * Created by PhpStorm.
 * User: byronherrera
 * Date: 3/9/15
 * Time: 11:43
 */

$oauth_hash = '';
$oauth_hash .= 'oauth_consumer_key=LJLRRzjsJDvZeAzbXcvEAx2VZ&';
$oauth_hash .= 'oauth_nonce=' . time() . '&';
$oauth_hash .= 'oauth_signature_method=HMAC-SHA1&';
$oauth_hash .= 'oauth_timestamp=' . time() . '&';
$oauth_hash .= 'oauth_token=567396947-d7D2JnXHhwXTkGioYnjX7v4Hpjy9BUKDugnDYW5J&';
$oauth_hash .= 'oauth_version=1.0';
$base = '';
$base .= 'GET';
$base .= '&';
$base .= rawurlencode('https://api.twitter.com/1.1/statuses/user_timeline.json');
$base .= '&';
$base .= rawurlencode($oauth_hash);
$key = '';
$key .= rawurlencode('VgbBTfpuzydvpXFhvXeM5dSvoKaVJQyOewp2sLOrO5VaniXjuK');
$key .= '&';
$key .= rawurlencode('0wHDuFz7t17aPzop0ZuvoUcZJ2uo4Qlwbot2UcPFlenPG');
$signature = base64_encode(hash_hmac('sha1', $base, $key, true));
$signature = rawurlencode($signature);


$oauth_header = '';
$oauth_header .= 'oauth_consumer_key="LJLRRzjsJDvZeAzbXcvEAx2VZ", ';
$oauth_header .= 'oauth_nonce="' . time() . '", ';
$oauth_header .= 'oauth_signature="' . $signature . '", ';
$oauth_header .= 'oauth_signature_method="HMAC-SHA1", ';
$oauth_header .= 'oauth_timestamp="' . time() . '", ';
$oauth_header .= 'oauth_token="567396947-d7D2JnXHhwXTkGioYnjX7v4Hpjy9BUKDugnDYW5J", ';
$oauth_header .= 'oauth_version="1.0", ';
$curl_header = array("Authorization: Oauth {$oauth_header}", 'Expect:');

$curl_request = curl_init();
curl_setopt($curl_request, CURLOPT_HTTPHEADER, $curl_header);
curl_setopt($curl_request, CURLOPT_HEADER, false);
curl_setopt($curl_request, CURLOPT_URL, 'https://api.twitter.com/1.1/statuses/user_timeline.json');
curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, false);
$json = curl_exec($curl_request);
curl_close($curl_request);

echo $json;