<?php
header('Content-Type: application/json');
//1st Setting up the Report Host and the Report Data

$host = 'https://api.omniture.com/admin/1.4/rest/?method=Report.Run';
$data='{
	"reportDescription":{
		"reportSuiteID":"[rsid]",
		"dateFrom":"2 hours ago",
		"dateTo":"now",
		"dateGranularity":"minute:60",
		"metrics":[
			{
				"id":"pageviews"
			}
		],
		"elements":[
			{
				"id":"page",
				"top":"15"
			}
		],
		"source":"realtime"
	}
}';

//2nd Creating the WSSE Header: https://marketing.adobe.com/developer/documentation/authentication-1/wsse-authentication-2

$nonce = md5(rand(), TRUE);
$created = gmdate('Y-m-d\TH:i:sO');
$username = "[WEB SERVICES USERNAME]";
$sharedSecret = "[WEB SERVICES PASSWORD]";
$b64nonce = base64_encode($nonce);
$passwordDigest = base64_encode(sha1($nonce . $created . $sharedSecret, TRUE));


$token = array(
	sprintf('X-WSSE: UsernameToken Username="%s", PasswordDigest="%s", Nonce="%s", Created="%s"',
	  $username,
	  $passwordDigest,
	  $b64nonce,
	  $created
	)
);

//3rd Setting Up the cURL request

$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_URL, $host);
curl_setopt($ch, CURLOPT_HTTPHEADER, $token);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);

//4th Output the cURL Response

echo $response;

?>