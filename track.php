<?php

/* Enable for Debugging Purposes only */
// ini_set('display_errors', 1) ;
// ini_set('display_startup_errors', 1) ;
// error_reporting(E_ALL) ;

require_once( $_SERVER['DOCUMENT_ROOT'] . '/access.inc.php' ) ;
require_once( $_SERVER['DOCUMENT_ROOT'] . '/config.inc.php' ) ;

/* Determine language */
if (isset($_GET['lang']))
{
	$language_id = substr(htmlspecialchars($_GET['lang']), 0, 2) ;
	
	if (!preg_match('/^(bg|en)$/', $language_id))
	{
		$language_id = LANGUAGE_DEFAULT ;
	}
}	else {
		$language_id = LANGUAGE_DEFAULT ;
}

/* Determine parcel sender */
if ( isset ($_GET['p'] ) && !empty ($_GET['p'] ) )
{
	$parcel_id = htmlspecialchars($_GET['p']) ;
	// ******************************************************************************
	// Maybe do some more input sanitization?
	// e.g. limit character length, accept only alphanumericals and # sign (for CVC)?
	// ******************************************************************************
	
	if (preg_match(PATTERN_SPEEDY, $parcel_id)) {
		echo '<h2>Доставка<span class="optional">та се изпълнява</span> чрез <span class="speedy">Спиди</span>. Хронология<span class="optional"> на събитията</span>:</h2>' ;
		printSpeedy($parcel_id, $language_id) ;
	}	elseif (preg_match(PATTERN_ECONT, $parcel_id)) {
		echo '<h2>Доставка<span class="optional">та се изпълнява</span> чрез <span class="econt">Еконт</span>. Хронология<span class="optional"> на събитията</span>:</h2>' ;
		printEcont($parcel_id, $language_id) ;
	} elseif (preg_match(PATTERN_A1POST, $parcel_id)) {
		echo '<h2>Доставка<span class="optional">та се изпълнява</span> чрез <span class="a1post">A1 Post</span>. Хронология<span class="optional"> на събитията</span>:</h2>' ;
		printA1post($parcel_id, $language_id) ;
	} elseif (preg_match(PATTERN_LEOEXPRES, $parcel_id)) {
		echo '<h2>Доставка<span class="optional">та се изпълнява</span> чрез <span class="leoexpres">Leo Expres</span>. Хронология<span class="optional"> на събитията</span>:</h2>' ;
		printLeoexpres($parcel_id) ;
	} elseif (preg_match(PATTERN_CVC, $parcel_id)) {
		echo '<h2>Доставка<span class="optional">та се изпълнява</span> чрез <span class="cvc">CVC</span>. Хронология<span class="optional"> на събитията</span>:</h2>' ;
		printCVC($parcel_id, $language_id) ;
	} else {
		echo '<h2>Не можем да разпознаем куриера по посочения номер на товарителница. <a href="' . SITE_CONTACT_URL . '">Свържете се с нас</a> за повече информация. </h2>' ;
		die() ;
	}
} else {
		/* Replace echo statement with something else if you want to modify */
		/* page behavior for people who land on page without a tracking no. */
		echo '<h2>Необходимо е да подадете заявка с номер на товарителница</h2>' ;
		die() ;
}

function printSpeedy($parcel_id, $language_id){
	
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

	/* Interpret request output */
	$data		= json_decode($response, true) ;
	$operations	= $data['parcels']['0']['operations'] ;

	/* Print data */
	for($i=0; $i < count($operations); $i++) 	{
		$opdate = $operations[$i]['dateTime'] ;
		$opdate = strtotime($opdate) ;
		$opdate = date('d.m.Y H:i', $opdate) ;
		$opcode = $operations[$i]['operationCode'] ;

		echo '<div class="monospaced">' ;
		echo  $opdate ;
		echo " &rarr; " ;
		echo '<span class="monoblocked">' . $operations[$i]['description'] . '</span>' ;
		echo '</div>' ;
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

	$collect	= $data['shipments']['0']['recipient']['pickupOfficeId'] ;

	if (-14 === $opcode) { // Package has been delivered
		echo '<h3 class="h3delivered">Пратката е доставена</h3>' . "\n" ;
		feedbackRequestGoogle() ;
	} elseif ($collect) { // Package has been sent to office and is not yet delivered
		echo '<h3 class="h3map">Локация и работно време:</h3>' ;
		echo '<div class="map">' ;
		echo '<iframe class="ifmap" src="https://services.speedy.bg/officesmap?lang=' . $language_id . '&id=' . $collect . '">' ;
		echo '</div>' ;
	}
}


function printEcont($parcel_id, $language_id){

	/* Make API request */
	$reqURL = ECONT_API_BASE . ECONT_API_CMD_TRACK ;
	$jsonq  = array("shipmentNumbers" => [$parcel_id] ) ;
	$query  = json_encode($jsonq) ;
	
	$curl = curl_init($reqURL) ;
	curl_setopt($curl, CURLOPT_HEADER, false) ;
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true) ;
	curl_setopt($curl, CURLOPT_HTTPHEADER,
				array("Content-type: application/json")) ;
	curl_setopt($curl, CURLOPT_POST, true) ;
	curl_setopt($curl, CURLOPT_POSTFIELDS, $query) ;

	$json_response = curl_exec($curl) ;

	$status = curl_getinfo($curl, CURLINFO_HTTP_CODE) ;

	if ( $status != 200 ) {
		die("Error: call to URL $reqURL failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl)) ;
	}

	curl_close($curl) ;

	/* Interpret request output */
	$response		= json_decode($json_response, true) ;
	$operations	= $response['shipmentStatuses'][0]['status']['trackingEvents'] ;

	$terms = [
		"prepared"					=> "Поръчката е обработена.",
		"courier"						=> "Приета от куриер",
		"office"						=> "Складирана в офис",
		"courier_direction"	=> "Натоварена на линия",
		"client"						=> "Доставена на получателя",
	] ;

	$terms_en = [
		"prepared"					=> "Order has been processed",
		"courier"						=> "Collected by courier",
		"office"						=> "Reached office",
		"courier_direction"	=> "In transit",
		"client"						=> "Delivered",
	] ;
	
	/* Remove action icons. Reason: mainly aesthetics (waste space and look bad on mobile screens) */
	/*
	$terms = [
		"prepared"					=> "Пратката очаква предаване към куриера",
		"courier"           => "<i class=\"fas fa-people-carry\"></i> Приета от куриер",
		"office"            => "<i class=\"fas fa-boxes\"></i> Складирана в офис",
		"courier_direction" => "<i class=\"fas fa-truck-loading\"></i> Натоварена на линия",
		"client"            => "<i class=\"fas fa-user-check\"></i> Доставена на получателя",
	] ;
	*/

	/* Print data */
	for ($i=0; $i < count($operations); $i++)
	{
		$opdate = $operations[$i]['time'] / 1000 ;
		$opdate = date('d.m.Y H:i', $opdate) ;
		//$action = $operations[$i]['destinationType'] ;
		$action = $terms[$operations[$i]['destinationType']] ;

		echo '<div class="monospaced">' ;
		echo $opdate ;
		echo " &rarr; " ;
		if ('en' == $language_id) {
			echo '<span class="monoblocked">' . $terms_en[$operations[$i]['destinationType']] . '</span> <span class="monoblocked">' . $operations[$i]['destinationDetailsEn'] . '</span>' ;
		} else {
			echo '<span class="monoblocked">' . $terms[$operations[$i]['destinationType']]    . '</span> <span class="monoblocked">' . $operations[$i]['destinationDetails']   . '</span>' ;
		}
		echo '</div>' ;
		
		/* If package has been delivered, show the delivery notice and ask for a review */
		if ("client" === $operations[$i]['destinationType']) { // Package has been delivered
			echo '<h3 class="h3delivered">Пратката е доставена</h3>' . "\n" ;
			feedbackRequestGoogle() ;
		}	
		
	}
}

function printA1post($parcel_id, $language_id){

	if ('bg' == $language_id || '' == $language_id )
	{
		$reqURL = A1POST_URL_BASE_BG ;
	}
	elseif ('en' == $language_id )
	{
		$reqURL = A1POST_URL_BASE_EN ;
	}

	// Scrape A1 page
	$reqURL				= $reqURL . $parcel_id ;
	$html					= file_get_contents ($reqURL) ;
	$html_parsed	= strstr(strstr($html, '<table id="ritm">', false), '</table>', true) ;

	// Turn the parsed table into a standalone HTML DOM document
	$DOM = new DOMDocument() ;
	$DOM->loadHTML('<?xml encoding="UTF-8">' . $html_parsed) ; // Encoding is very important!
	
	// Get all rows of that table and put them into an array
	$rows = $DOM -> getElementsByTagName('tr') ;

	// Go through every row
	for ($i = $rows->count() ; $i > 0 ; $i--)
	{
		$output		= $rows->item($i-1)->nodeValue ;
		$opdate		= substr($output, 0, 16) ;
		$opstatus	= substr($output, 16) ;

		echo '<div class="monospaced">' ;
		echo  $opdate ;
		echo " &rarr; " ;
		echo '<span class="monoblocked">' . $opstatus . '</span>' ;
		echo '</div>' ;
	}
}

function printLeoexpres($parcel_id){

	// Pull data from the website
	$reqURL = LEOEXPRES_URL_BASE . $parcel_id ;
	$html		= file_get_contents ($reqURL) ;

	// Turn the parsed table into a standalone HTML DOM document
	$DOM = new DOMDocument() ;
	$DOM->loadHTML('<?xml encoding="UTF-8">' . $html) ; // Encoding is very important!

	// Extract div tags that contain timestamps and actions
	$finder				= new DomXPath($DOM) ;
	$timestamps		= iterator_to_array($finder->query("//*[contains(@class, 'recent-activity-body-title')]")) ;
	$actions			= iterator_to_array($finder->query("//*[contains(@class, 'recent-activity-body-content')]")) ;

	// Go through every row
	$limit = count($timestamps) ;
	for ($i = 0; $i < $limit; $i++)
	{
		// Format timestamp for consistency with other outputs
		$opdate		= substr(str_replace(' - ', ' ', $timestamps[$i]->textContent), 0, 16) ;
		$opstatus	= $actions[$i]->textContent ;

		echo '<div class="monospaced">' ;
		echo  $opdate ;
		echo " &rarr; " ;
		echo '<span class="monoblocked">' . $opstatus . '</span>' ;
		echo '</div>' ;
		
		if (strpos($opstatus, "Доставена до клиент")) { // Package has been delivered
			echo '<h3 class="h3delivered">Пратката е доставена</h3>' . "\n" ;
			feedbackRequestGoogle() ;
		}
	}
}

function printCVC($parcel_id, $language_id) {

	/* Make API request */
	$reqURL = CVC_API_BASE . '?full=true&view=json&locale=' . $language_id . '&manifestID=' . $parcel_id ;

	$curl = curl_init($reqURL) ;
	curl_setopt($curl, CURLOPT_HEADER, false) ;
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true) ;
	curl_setopt($curl, CURLOPT_POST, false) ;

	$json_response = curl_exec($curl) ;

	$status = curl_getinfo($curl, CURLINFO_HTTP_CODE) ;

	if ( $status != 200 ) {
		die("Error: call to URL $reqURL failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl)) ;
	}

	curl_close($curl) ;

	/* Interpret request output */
	$response		= json_decode($json_response, true) ;
	$operations	= $response['states'] ;

	/* Print data */
	$limit = count($operations) - 1 ;

	for ($i = $limit; $i >= 0 ; $i--)
	{
		$opdate = $operations[$i]['date'] / 1000 ;
		$opdate = date('d.m.Y H:i', $opdate) ;

		echo '<div class="monospaced">' ;
		echo $opdate ;
		echo " &rarr; " ;
		echo '<span class="monoblocked">' . $operations[$i]['display'] . '</span> <span class="monoblocked">' . $operations[$i]['station'] . '</span>' ;
		echo '</div>' ;
	}
}
