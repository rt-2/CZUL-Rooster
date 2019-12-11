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

$allInstructors = array();


$allControllerTrainingStates = file_get_contents('../CONTROLLERS_TRAINING_STATE.data.json');
$allControllerTrainingStates = json_decode($allControllerTrainingStates)->CONTROLLERS_TRAINING_STATE;
$allControllerTrainingStates = json_decode(json_encode($allControllerTrainingStates), true);

$allGuestStates = file_get_contents('../GUESTS_TRAINING_STATE.data.json');
$allGuestStates = json_decode($allGuestStates)->GUESTS_TRAINING_STATE;
$allGuestStates = json_decode(json_encode($allGuestStates), true);



class RosterMemberInfosFinal
{
	public $data;
	
	public function __construct() {
		$this->data = [
			'cid' => '',
			'name' => '',
			'rating' => '',
			'active' => false,
			'position' => '',
			'instructor' => '',
		];
	}
	
}
class RosterGuestInfosFinal
{
	public $data;
	
	public function __construct() {
		$this->data = [
			'cid' => '',
			'name' => '',
			'rating' => '',
			'position' => '',
		];
	}
	
}

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


	<h2>Members: </h2>
	
	<table border="true" cellpadding="5">
		<tr>
		<?php
		$thoseColumnObj = new RosterMemberInfosFinal();
			foreach(array_keys($thoseColumnObj->data) as $columnName)
			{
				echo '<td><b>'.strToUpper($columnName).'</b></td>';
			}
		?>
		</tr>
		<?php
		
		
		foreach($allMembers as $thisMember)
		{
			$thisAllInfos = new RosterMemberInfosFinal();
			$thisAllInfos->data['cid'] = $thisMember['cid'];
			$thisAllInfos->data['name'] = $thisMember['fname'].' '.$thisMember['lname'];
			$thisAllInfos->data['rating'] = $thisMember['rating'];
			
			if($thisAllInfos->data['rating'] >= 8)
			{
				$allInstructors[$thisAllInfos->data['cid']] = $thisAllInfos->data['name'];
			}
			
			if(array_key_exists($thisAllInfos->data['cid'], $allControllerTrainingStates))
			{
				$thisAllInfos->data['active'] = true;
				$thisTrainingState = $allControllerTrainingStates[$thisAllInfos->data['cid']];
				
				$thisAllInfos->data['position'] = $thisTrainingState['position'];
				
				if(array_key_exists('instructor', $thisTrainingState))
				{
					$thisAllInfos->data['instructor'] = $thisTrainingState['instructor'];
					if(strlen($thisAllInfos->data['instructor']) > 0)
					{
						if(array_key_exists($thisAllInfos->data['instructor'], $allInstructors))
						{
							$thisAllInfos->data['instructor'] = $allInstructors[$thisAllInfos->data['instructor']].' ('.$thisAllInfos->data['instructor'].')';
						}
					}
				}
			}
			
			
			$thisAllInfos->data['rating'] = $ratingNames[$thisAllInfos->data['rating']];
			$thisAllInfos->data['active'] = ($thisAllInfos->data['active'] === true)? 'Oui': 'Non';
			
			
			
			
			?>
			<tr>
				<?php
					foreach($thisAllInfos->data as $key => $value)
					{
						echo '<td>'.$value.'</td>';
					}
				
				?>
				
			</tr>
			<?php
		}
		?>
	</table>

	<h2>Guests: </h2>

	<table border="true" cellpadding="5">
		<?php
		
		
		
		foreach($allGuestStates as $cid => $attrs)
		{
			if($cid > 799999)
			{
				$thisAllInfos = new RosterGuestInfosFinal();
				$thisAllInfos->data['cid'] = $cid;
				
				
				// Get cURL resource
				$curl = curl_init();
				// Set some options - we are passing in a useragent too here
				curl_setopt_array($curl, [
					CURLOPT_RETURNTRANSFER => 1,
					CURLOPT_URL => 'https://cert.vatsim.net/cert/vatsimnet/idstatusint.php?cid='.$thisAllInfos->data['cid'],
					//CURLOPT_URL => 'https://api.vatcan.ca/IF84l6Y1utjILKlk/roster/1380757',
					CURLOPT_USERAGENT => 'Codular Sample cURL Request',
					CURLOPT_SSL_VERIFYPEER => false,
				]);
				// Send the request & save response to $resp
				$resp = curl_exec($curl);
				// Close request to clear up some resources
				curl_close($curl);
				$xml = simplexml_load_string($resp);
				$json = json_encode($xml);
				$array = json_decode($json,TRUE);
				$fetchGuestData = $array['user'];
				
				
				
				$thisAllInfos->data['name'] = $fetchGuestData['name_first'].' '.$fetchGuestData['name_last'];
				$thisAllInfos->data['rating'] = $fetchGuestData['rating'];
				
			
				if(array_key_exists('position', $attrs))
				{
					$thisAllInfos->data['position'] = $attrs['position'];
				}
				
				
				$thisAllInfos->data['rating'] = $ratingNames[$thisAllInfos->data['rating']];
				
				
				
				
				?>
				<tr>
					<?php
						foreach($thisAllInfos->data as $key => $value)
						{
							echo '<td>'.$value.'</td>';
						}
					
					?>
					
				</tr>
				<?php
			}
		}
		?>
	</table>
	
	<textarea>
		<?php
		
		echo json_encode($allControllerTrainingStates);
		?>
	</textarea>
	<textarea>
		<?php
		
		echo json_encode($allInstructors);
		?>
	</textarea>
</body>
</html>
