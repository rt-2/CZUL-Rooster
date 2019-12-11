<!DOCTYPE html>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//require_once(dirname(__FILE__).'/CANotAPI.inc.php');
//require_once(dirname(__FILE__).'/resources/fir.data.inc.php');

// Get cURL resource
$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, [
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'https://api.vatcan.ca/IF84l6Y1utjILKlk/roster',
    CURLOPT_USERAGENT => 'Codular Sample cURL Request',
	CURLOPT_SSL_VERIFYPEER => false,
]);
// Send the request & save response to $resp
$resp = curl_exec($curl);
// Close request to clear up some resources
curl_close($curl);

$allMembers = json_decode($resp);

?>
<html>
    <head>
    <meta charset="UTF-8">
    <title>Roster</title>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
<textarea>
	<?php
	
	echo $resp;
	?>
</textarea>
</body>
</html>
