<?php
header('Content-Type: application/json');
//1st Get Token

$host = 'https://api.omniture.com/token';
$data = 'grant_type=client_credentials';
$headerArr = '65b5501e18-move-test:594658532803bd956a90';

$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_URL, $host);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, $headerArr);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

$response = curl_exec($ch);
curl_close($ch);

$tokenArr = (array)json_decode($response);

$token = array('Authorization: Bearer '.$tokenArr['access_token']);
echo json_encode($token);


//2nd Shoot the Report.Run Method with the above token.

$host = 'https://api.omniture.com/admin/1.4/rest/?method=Report.Run';
$data='
{
	"reportDescription":{
		"reportSuiteID":"lscswarlock",
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
				"id":"prop73",
				"top":"15"
			}
		],
		"source":"realtime"
	}
}';


$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_URL, $host);
curl_setopt($ch, CURLOPT_HTTPHEADER, $token);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

$response = curl_exec($ch);
curl_close($ch);

print_r($response);


?>