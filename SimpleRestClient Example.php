<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Data Source API 1.4</title>
</head>
<body>

<?php
	include_once("./SimpleRestClient/SimpleRestClient.class.php");
	$error = false; 
	$done = false;
	
	function GetAPIData($method, $data) {
		/*$username = '[WEB SERVICES USERNAME]'; 
		$secret = '[WEB SERVICES PASSWORD]';
		Both can be found under ADMIN >> COMPANY SETTINGS >> WEB SERVICES (only users with web services right will be listed in the table)
		
		*/
		
		$username = '[WEB SERVICES USERNAME]';
		$secret = '[WEB SERVICES PASSWORD]';
		$nonce = md5(uniqid(php_uname('n'), true));
		$nonce_ts = date('c');
		$digest = base64_encode(sha1($nonce.$nonce_ts.$secret));
		/*$server possible values :
			api.omniture.com - San Jose
			api2.omniture.com - Dallas
			api3.omniture.com - London
			api4.omniture.com - Singapore
			api5.omniture.com - Pacific Northwest
		*/
		$server = "https://api.omniture.com";
		$path = "/admin/1.4/rest/";

		$rc=new SimpleRestClient();
		$rc->setOption(CURLOPT_HTTPHEADER, array("X-WSSE: UsernameToken Username=\"$username\", PasswordDigest=\"$digest\", Nonce=\"$nonce\", Created=\"$nonce_ts\""));

		$rc->postWebRequest($server.$path.'?method='.$method, $data);

		return $rc;
	}
	
	
	
	/*Build you REST requests. For example of requests go to API explorer : https://marketing.adobe.com/developer/api-explorer*/
	
	
	/*******************************************************************/
	/*Run a RealTime Report                                            */
	/*******************************************************************/
	
	$method="Report.Run";
	
	$data='{
			"reportDescription":{
				"reportSuiteID":"(RSID)",
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

	$rc=GetAPIData($method, $data);

	if ($rc->getStatusCode()==200) {
		$response=$rc->getWebResponse();
		$json=json_decode($response);
		if (strpos($response, "Bad Request") !== true) {
			echo "Running a Real Time Report: <br/>";
			echo "<br>";
			echo "<br>";
			echo "<br>";
			echo '<pre>'.$response.'<pre>';
		}
		else {
			$error=true;
			echo "not queued - <br />";
		}
	} else {
		$error=true;
		echo "something went really wrong <br />";
		var_dump($rc->getInfo());
		echo "<br>";
		echo "<br>";
		echo "<br>";
		echo '<pre>'.$rc->getWebResponse().'<pre>';
	}
	
	echo "------------------------------------------------------------- <br/>";
	echo "------------------------------------------------------------- <br/>";
	

?>



</body>
</html>