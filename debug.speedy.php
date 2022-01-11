<?php

/* Enable for Debugging Purposes only */
ini_set('display_errors', 1) ;
ini_set('display_startup_errors', 1) ;
error_reporting(E_ALL) ;

require_once( $_SERVER['DOCUMENT_ROOT'] . '/access.inc.php' ) ;
require_once( $_SERVER['DOCUMENT_ROOT'] . '/config.inc.php' ) ;
require_once( $_SERVER['DOCUMENT_ROOT'] . '/feedback.php' ) ;

/* Determine parcel sender */
if ( isset ($_GET['p'] ) && !empty ($_GET['p'] ) ) {
	$parcel_id = htmlspecialchars($_GET['p']) ;
	if (preg_match(PATTERN_SPEEDY, $parcel_id)) {
		echo '<h2>Доставка<span class="optional">та се изпълнява</span> чрез <span class="speedy">Спиди</span>. Хронология<span class="optional"> на събитията</span>:</h2></div>' ;
		printSpeedy($parcel_id) ;
	} else {
		/* Replace echo statement with something else if you want to modify */
		/* page behavior for people who land on page without a tracking no. */
		echo '<h2>Необходимо е да подадете заявка с номер на товарителница</h2>' ;
		die() ;
	}
}

function printSpeedy($parcel_id) {
	
	/* Make API request */
	$reqURL = SPEEDY_API_BASE . SPEEDY_API_CMD_TRACK . '?userName=' . SPEEDY_USER . '&password=' . SPEEDY_PASS . '&language=' . $language_id . '&parcels=' . $parcel_id ;

	$curl = curl_init() ;
	curl_setopt_array($curl, array(
		CURLOPT_URL => $reqURL,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 1,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
	)) ;
	$response = curl_exec($curl) ;
	$status = curl_getinfo($curl, CURLINFO_HTTP_CODE) ;
	if ( $status != 200 )
	{
			die("Error: call to URL $reqURL failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl)) ;
	}
	curl_close($curl) ;

	echo "<h2>Raw output</h2>\n" ;
	echo "<pre>\n" ;
	var_dump ($response) ;
	echo "</pre>\n" ;
	echo "<hr><br>\n" ;

	/* Interpret request output */
	$data		= json_decode($response, true) ;
	$operations	= $data['parcels']['0']['operations'] ;

	echo "<h2>JSON Decode</h2>\n" ;
	echo "<pre>\n" ;
	var_dump ($operations) ;
	echo "</pre>\n" ;
	echo "<hr><br>\n" ;

	/* Print data */
	for($i=0; $i < count($operations); $i++) 	{
		$opdate = $operations[$i]['dateTime'] ;
		$opdate = strtotime($opdate) ;
		$opdate = date('d.m.Y H:i', $opdate) ;
		$opcode = $operations[$i]['operationCode'] ;
		$opstatus	= $operations[$i]['description'] ;

		/* Checking for DPD Predict */
		if (175 === $opcode)
		{
			$predict = $operations[$i]['comment'] ;
		}
		/*
		echo '<div class="monospaced">' ;
		echo '<span class="timestamp">' . $opdate . '</span>' ;
		echo '<span class="monoblocked-inline">' . $opstatus . '</span>' ;
		echo '</div>' ;
		*/
	}
	
	/* Check if there is office location data */
	$reqURL = SPEEDY_API_BASE . SPEEDY_API_CMD_RCV_OFFICE . '?userName=' . SPEEDY_USER . '&password=' . SPEEDY_PASS . '&language=' . $language_id . '&shipmentIds=' . $parcel_id ;

	$curl = curl_init() ;
	curl_setopt_array($curl, array(
		CURLOPT_URL => $reqURL,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 1,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
	)) ;
	$response = curl_exec($curl) ;
	$status = curl_getinfo($curl, CURLINFO_HTTP_CODE) ;
	if ( $status != 200 )
	{
			die("Error: call to URL $reqURL failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl)) ;
	}
	curl_close($curl) ;

	$data		= json_decode($response, true) ;

	echo "<h2>Speedy Receive Office Data</h2>\n" ;
	echo "<pre>\n" ;
	var_dump ($data) ;
	echo "</pre>\n" ;
	echo "<hr><br>\n" ;

	$collect	= $data['shipments']['0']['recipient']['pickupOfficeId'] ;

	echo "<h2>Office ID output</h2>\n" ;
	echo "<pre>\n" ;
	var_dump ($collect) ;
	echo "</pre>\n" ;
	echo "<hr><br>\n" ;

	/*
	if (-14 === $opcode) { // Package has been delivered
		echo '<h3 class="h3delivered">Пратката е доставена</h3>' . "\n" ;
		feedbackRequestGoogle() ;
	} elseif ($collect) { // Package has been sent to office and is not yet delivered
		echo '<h3 class="h3map">Локация и работно време:</h3>' ;
		echo '<div class="map">' ;
		echo '<iframe class="ifmap" src="https://services.speedy.bg/officesmap?lang=bg&id=' . $collect . '">' ;
		echo '</div>' ;
	}
	*/
}