<!DOCTYPE html>
<?php

//require_once(dirname(__FILE__).'/CANotAPI.inc.php');
//require_once(dirname(__FILE__).'/resources/fir.data.inc.php');

// Get cURL resource
$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, [
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'http://testcURL.com',
    CURLOPT_USERAGENT => 'Codular Sample cURL Request',
    CURLOPT_POST => 1,
    CURLOPT_POSTFIELDS => [
        item1 => 'value',
        item2 => 'value2'
    ]
]);
// Send the request & save response to $resp
$resp = curl_exec($curl);
// Close request to clear up some resources
curl_close($curl);


?>
<html>
    <head>
    <meta charset="UTF-8">
    <title>Roster</title>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
	<?php
	
	echo $resp;
	?>
</body>
</html>
