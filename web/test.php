<?php

//NOTE: The keyset used here will not work against the production API
$session = 'f10820d9fc0f8c7f3fb09b81c504f428';


$url = 'http://local.dev/app_dev.php/api/credentials/reset';

$fields_str = '';

$fields = array(
  'session' => $session
);

foreach($fields as $key=>$value) {
  $fields_str .= $key.'='.$value.'&';
}

rtrim($fields_str, '&');

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, count($fields));
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_str);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result = json_decode(curl_exec($ch));

curl_close($ch);

var_dump($result);
exit;

if($result->status == 'success') {
  
  //Keys have been reset and emailed to the developer.

} else {
  
  $status = $result['status'];
  $reason = $result['reason'];
  $message = $result['message'];

}
