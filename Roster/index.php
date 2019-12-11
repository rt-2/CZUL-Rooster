<!DOCTYPE html>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//require_once(dirname(__FILE__).'/CANotAPI.inc.php');
//require_once(dirname(__FILE__).'/resources/fir.data.inc.php');


$ratingNames = [
	'', // 0 (not used)
	'OBS', // 1
	'S1', // 2
	'S2', // 3
	'S3', // 4
	'C1', // 5
	'C2', // 6 (not used)
	'C3', // 7
	'I1', // 8
	'I2', // 9 (not used)
	'I3', // 10
	'SUP', // 11
];



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

$allMembers = json_decode($resp)->facility->roster;
$allMembers = json_decode(json_encode($allMembers), true);

usort($allMembers, function($a, $b) {
    return $b['rating'] - $a['rating'];
});

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

	<table border="true" cellpadding="5">
		<?php
		
		foreach($allMembers as $thisMember)
		{
			?>
			<tr>
				<td><?=$thisMember['cid']?></td>
				<td><?=$thisMember['fname'].' '.$thisMember['lname']?></td>
				<td><?=$ratingNames[$thisMember['rating']]?></td>
				
			</tr>
			<?php
		}
		?>
	</table>
</body>
</html>
