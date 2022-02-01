<?php

/* Enable for Debugging Purposes only */
// ini_set('display_errors', 1) ;
// ini_set('display_startup_errors', 1) ;
// error_reporting(E_ALL) ;

require_once( $_SERVER['DOCUMENT_ROOT'] . '/access.inc.php' ) ;
require_once( $_SERVER['DOCUMENT_ROOT'] . '/config.inc.php' ) ;
require_once( $_SERVER['DOCUMENT_ROOT'] . '/feedback.php' ) ;

/* Determine language */
if (isset($_GET['lang'])) {
	$language_id = substr(htmlspecialchars($_GET['lang']), 0, 2) ;
	
	if (!preg_match('/^(bg|en)$/', $language_id))
	{
		$language_id = LANGUAGE_DEFAULT ;
	}
}	else {
		$language_id = LANGUAGE_DEFAULT ;
}

/* Determine parcel sender */
if ( isset ($_GET['p'] ) && !empty ($_GET['p'] ) ) {
	$parcel_id = htmlspecialchars($_GET['p']) ;
	// ******************************************************************************
	// Maybe do some more input sanitization?
	// e.g. limit character length, accept only alphanumericals and # sign (for CVC)?
	// ******************************************************************************
	
	if (preg_match(PATTERN_SPEEDY, $parcel_id)) {
		echo '<h2>Доставка<span class="optional">та се изпълнява</span> чрез <span class="speedy">Спиди</span>. Хронология<span class="optional"> на събитията</span>:</h2></div>' ;
		printSpeedy($parcel_id, $language_id) ;
	}	elseif (preg_match(PATTERN_ECONT, $parcel_id)) {
		echo '<h2>Доставка<span class="optional">та се изпълнява</span> чрез <span class="econt">Еконт</span>. Хронология<span class="optional"> на събитията</span>:</h2></div>' ;
		printEcont($parcel_id, $language_id) ;
	} elseif (preg_match(PATTERN_A1POST, $parcel_id)) {
		echo '<h2>Доставка<span class="optional">та се изпълнява</span> чрез <span class="a1post">A1 Post</span>. Хронология<span class="optional"> на събитията</span>:</h2></div>' ;
		printA1post($parcel_id, $language_id) ;
	} elseif (preg_match(PATTERN_LEOEXPRES, $parcel_id)) {
		echo '<h2>Доставка<span class="optional">та се изпълнява</span> чрез <span class="leoexpres">Leo Expres</span>. Хронология<span class="optional"> на събитията</span>:</h2></div>' ;
		printLeoexpres($parcel_id) ;
	} elseif (preg_match(PATTERN_CVC, $parcel_id)) {
		echo '<h2>Доставка<span class="optional">та се изпълнява</span> чрез <span class="cvc">CVC</span>. Хронология<span class="optional"> на събитията</span>:</h2></div>' ;
		printCVC($parcel_id) ;
	} elseif (preg_match(PATTERN_ELTAGR, $parcel_id)) {
		echo '<h2>Доставка<span class="optional">та се изпълнява</span> чрез <span class="elta">Hellenic Post (ΕΛΤΑ)</span>. Хронология<span class="optional"> на събитията</span>:</h2></div>' ;
		printEltaGR($parcel_id) ;
	} elseif (preg_match(PATTERN_BGPOST, $parcel_id)) {
		echo '<h2>Доставка<span class="optional">та се изпълнява</span> чрез <span class="bgpost">Български пощи</span>. Хронология<span class="optional"> на събитията</span>:</h2></div>' ;
		printBGPost($parcel_id) ;
	} elseif (preg_match(PATTERN_EMSBULPOST, $parcel_id)) {
		echo '<h2>Доставка<span class="optional">та се изпълнява</span> чрез <span class="emsbulpost">EMS Bulpost</span>. Хронология<span class="optional"> на събитията</span>:</h2></div>' ;
		printEMSBulpost($parcel_id) ;
	} else {
		echo '<h2>Не можем да разпознаем куриера по посочения номер на товарителница.<br> <a href="' . SITE_CONTACT_URL . '">Свържете се с нас</a> за повече информация. </h2></div>' ;
		die() ;
	}
} else {
		/* Replace echo statement with something else if you want to modify */
		/* page behavior for people who land on page without a tracking no. */
		echo '<h2>Необходимо е да подадете заявка с номер на товарителница</h2>' ;
		die() ;
}

function printSpeedy($parcel_id, $language_id) {
	
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
		$opstatus	= $operations[$i]['description'] ;

		echo '<div class="monospaced">' ;
		echo '<span class="timestamp">' . $opdate . '</span>' ;
		echo '<span class="monoblocked-inline">' . $opstatus . '</span>' ;
		echo '</div>' ;
		
		/* Checking for DPD Predict */
		if (175 === $opcode)
		{
			$predict = $operations[$i]['comment'] ;
			echo '<div class="predict">' . $predict . '</div>' ;
		}

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
	$cod			= $data['shipments']['0']['service']['additionalServices']['cod']['amount'] ;
	$deadline	= date('d.m.Y г.', strtotime($data['shipments']['0']['delivery']['deadline'])) ;

	/* This next bit of code pulls the costs
	 * associated with the shipment.
	 * 
	 * $cod is the Cash on Delivery part
	 * $shipping is the Cost of Shipment.
	 * 
	 * Will not be used for the time being.
	 */
	
	 /*
	if ('RECIPIENT' === $data['shipments']['0']['payment']['courierServicePayer']) {
		$shipping	= $data['shipments']['0']['price']['total'] ;
	} else {
		$shipping = 0 ;
	}
	*/
	
	if (-14 === $opcode) { // Package has been delivered
		echo '<h3 class="h3delivered">Пратката е доставена</h3>' . "\n" ;
		feedbackRequestGoogle() ;
	}	elseif (111 === $opcode) {		// Package has been rejected and is being returned to sender
		echo '<h3 class="h3rejected">Пратката пътува обратно към изпращача</h3>' ;
	}	elseif (124 === $opcode) {		// Package has been returned to sender
		echo '<h3 class="h3returned">Пратката е върната на изпращача</h3>' ;
	} elseif ($collect) {						// Package has been sent to office and is not yet collected
		echo '<h3 class="h3office">Пратката е адресирана <strong>до поискване</strong></h3>' ;
		echo '<h3 class="h3office">Дата на доставка: <strong>' . $deadline . '</strong></h3>' ;
		echo '<h3 class="h3map">Локация и работно време на офиса</h3>' ;
		echo '<div class="map">' ;
		echo '<iframe class="ifmap" src="https://services.speedy.bg/officesmap?lang=' . $language_id . '&id=' . $collect . '">' ;
		echo '</div>' ;
	}
}

function printEcont($parcel_id, $language_id) {

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

	/* Check if shipment destination is an office */
	if ("office" == $response['shipmentStatuses'][0]['status']['receiverDeliveryType'])
	{
		$rcvOffice	= $response['shipmentStatuses'][0]['status']['receiverOfficeCode'] ;
	}	

	/* Dictionary of shipment statuses */
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

	/* Monitor if shipment is returned */
	$returnflag = false ;

	/* Print data */
	for ($i=0; $i < count($operations); $i++)
	{
		$opdate = $operations[$i]['time'] / 1000 ;
		$opdate = date('d.m.Y H:i', $opdate) ;

		if ('en' == $language_id) {
			$opstatus		= $terms_en[$operations[$i]['destinationType']] ;
			$oplocation = $operations[$i]['destinationDetailsEn'] ;
		} else {
			$opstatus		= $terms[$operations[$i]['destinationType']] ;
			$oplocation = $operations[$i]['destinationDetails'] ;
		}

		echo '<div class="monospaced">' ;
		echo '<span class="timestamp">' . $opdate . '</span>' ;
		echo '<span class="monoblocked-inline">' . $opstatus . '</span> <span class="monoblocked">' . $oplocation . '</span>' ;
		echo '</div>' ;

		if ("client" === $operations[$i]['destinationType'] && false === $returnflag) { // Package has been delivered OK; ask for review
			echo '<h3 class="h3delivered">Пратката е доставена</h3>' ;
			feedbackRequestGoogle() ;
		} elseif ("return" === $operations[$i]['destinationType'] && false === $returnflag) { // Package has been rejected and is being returned to sender
			$returnflag = true ;
			echo '<h3 class="h3rejected">Пратката пътува обратно към изпращача</h3>' ;
		} elseif ("client" === $operations[$i]['destinationType'] && true === $returnflag) { // Package has been returned to sender
			echo '<h3 class="h3returned">Пратката е върната на изпращача</h3>' ;
			break ;
		}
	}

}

function printA1post($parcel_id, $language_id) {

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
		echo '<span class="timestamp">' . $opdate . '</span>' ;
		echo '<span class="monoblocked-inline">' . $opstatus . '</span>' ;
		echo '</div>' ;
		
		/* If package has been delivered, show the delivery notice and ask for a review */
		if ("DELIVERED" == strtok($opstatus, " ")) { // Package has been delivered
			echo '<h3 class="h3delivered">Пратката е доставена</h3>' . "\n" ;
			feedbackRequestGoogle() ;
			break ;
		}
	}
}

function printLeoexpres($parcel_id) {

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
		echo '<span class="timestamp">' . $opdate . '</span>' ;
		echo '<span class="monoblocked-inline">' . $opstatus . '</span>' ;
		echo '</div>' ;
		
		if (strpos($opstatus, "Доставена до клиент")) { // Package has been delivered
			echo '<h3 class="h3delivered">Пратката е доставена</h3>' . "\n" ;
			feedbackRequestGoogle() ;
		}
	}
}

function printCVC($parcel_id) {

	/* Grab output from CVC tracking page */
	$reqURL = CVC_API_BASE . $parcel_id ;
	$html   = @file_get_contents ($reqURL) ;

	/* Get the parts which we need */
	$trim_start     = "<div class='tblRowsSimple' id='tblRows'>" ;
	$trim_start_len	= strlen($trim_start) ;
	$trim_end				= "<a href='/track'" ;
	$trim_end_len		= strlen($trim_end) ;

	$output	= substr(strstr(strstr($html, $trim_start, false), $trim_end, true), $trim_start_len) ;
	$output	= ltrim($output, "\n") ;							// remove leftover newline characters
	$output = str_replace("\t", "", $output) ;		// remove all tabs
	$output	= rtrim($output, "\n") ;							// remove leftover newline characters
	$output	= substr($output, 0, -6) ;						// remove leftover div closing tag
	$output	= rtrim($output, "\n") ;							// remove leftover newline characters

	/* Replace CVC output formatting with UT formatting and print data*/
	$seek	= array(
		'/^<div class=\'tblRow\' style=\'cursor:default;\'>$/m' ,
		'/^<div class=\'tblItem\' style=\'flex-basis:85px;\'>/m' ,
		'/<div class=\'note\'>/m' ,
		'/:[0-5][0-9]<\/div><\/div>$/m' ,
		'/^<div class=\'tblItem left bold\' style=\'flex:1;\'>/m' ,
		'/(?<!^)<\/div>$/m' 
	) ;

	$replace = array(
		'' ,
		'<div class="monospaced"><span class="timestamp">' ,
		'&nbsp;' ,
		'</span>' ,
		'<span class="monoblocked-inline">' ,
		'</span>'
	) ;
	
	echo preg_replace ($seek, $replace, $output) ;

}

function printEltaGR($parcel_id) {
	$callEltaTrack  = 'https://www.elta.gr/en-us/personal/tracktrace.aspx?qc=' ;
	$reqURL         = $callEltaTrack . $parcel_id ;
	$html           = @file_get_contents ($reqURL) ;

	if($html === FALSE)
	{
		echo '<h2 class="alert">Сайтът на спедитора ограничава заявките. Опитайте да опресните страницата след няколко минути.</h2>' ;
		die();
	}

  $trim_start     = '<div id="printme">' ;
	$trim_start_len	= strlen($trim_start) ;
	$trim_end				= '<br/><br/>' ;
  $output         = substr(strstr(strstr($html, $trim_start, false), $trim_end, true), $trim_start_len) ;
	$output					= str_replace(' & ', ' &amp; ', $output) ;

  $DOM            = new DOMDocument() ;
  $DOM->loadHTML('<?xml encoding="UTF-8">' . $output) ; // Encoding is very important!

	$rows				= $DOM->getElementsByTagName('tr') ;
	$rows_count = $rows->length ;
	$progress		= array() ;
	$record			= array() ;

	foreach ($rows as $row) {
		$cells = $row -> getElementsByTagName('td');
		foreach ($cells as $cell) {
			if (isset($cell->nodeValue))
			{
				array_push($record, $cell->nodeValue) ;
			}	else {
				array_push($record, "--") ;
			}
		}
		array_push ($progress, $record) ;
		$record = [] ;
	}

	// Remove last (first two records)
	$start = count($progress) - 2 ;
	// and skipt the first (latest) record

	for ($i = $start; $i > 1; $i--) {

		// Fix date formatting (wrong day and month)
		$dateGreek = strstr($progress[$i][0], ',', true) ;
		$timeGreek = strstr($progress[$i][0], ',') ;
		$dateArray = explode('/', $dateGreek) ;
		$tmp = $dateArray[0] ;
		$dateArray[0] = $dateArray[1] ;
		$dateArray[1] = $tmp ;
		unset($tmp) ;
		$opdate = implode('/', $dateArray) . $timeGreek ;

		$opdate		= date('d.m.Y H:i', strtotime($opdate)) ;
		$oploc		= $progress[$i][1] ;
		$opstatus	= $progress[$i][2] ;

		echo '<div class="monospaced">' ;
		echo '<span class="timestamp">' . $opdate . '</span>' ;
		echo '<span style="float: right;">' . $oploc . '</span>' ;
		echo '<span class="monoblocked-inline">' . $opstatus . '</span>' ;
		echo '</div>' ;
	}
}

function printBGPost($parcel_id) {
	$callBGPostTrack  = 'https://bgpost.bg/IPSWebTracking/IPSWeb_item_events.asp?itemid=' ;
	$reqURL           = $callBGPostTrack . $parcel_id ;
	$html             = @file_get_contents ($reqURL) ;

	if($html === FALSE)
	{
		echo '<h2 class="alert">Сайтът на спедитора ограничава заявките. Опитайте да опресните страницата след няколко минути.</h2>' ;
		die();
	}

  $trim_start     = '<td class="tabproperty"><wap> </wap> ' ;
	$trim_start_len	= strlen($trim_start) ;
	$trim_end				= '</table></td>' ;
  $output         = substr(strstr(strstr($html, $trim_start, false), $trim_end, true), $trim_start_len) ;

  // var_dump ($output) ;
  
  $DOM            = new DOMDocument() ;
  $DOM->loadHTML('<?xml encoding="UTF-8">' . $output) ; // Encoding is very important!

	$rows				= $DOM->getElementsByTagName('tr') ;
	$rows_count = $rows->length ;
	
  $progress		= array() ;
	$record			= array() ;
  
  foreach ($rows as $row) {
		$cells = $row -> getElementsByTagName('td');

		foreach ($cells as $cell) {
			if (isset($cell->nodeValue))
			{
				array_push($record, $cell->nodeValue) ;
			}	else {
				array_push($record, "--") ;
			}
		}
		array_push ($progress, $record) ;
		$record = [] ;
	}

	$iterations = count($progress) ;

  // Remove top two rows (used for table headers)
	for ($i = 2; $i < $iterations; $i++) {

    $opdate     = $progress[$i][0] ;
    $opcountry  = $progress[$i][1] ;
    $oplocation = $progress[$i][2] ;
    $opstatus   = $progress[$i][3] ;

		echo '<div class="monospaced">' ;
		echo '<span class="timestamp">' . $opdate . '</span>' ;
		echo '<span class="status">' . $opstatus . '</span>' ;
    echo '<span class="monoblocked" style=opacity: 0.75; "><i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;' . $opcountry . ' » ' . $oplocation . '</span>' ;
    echo '</div>' ;

	}
}

function printEMSBulpost($parcel_id) {
	$callEMSBulpostTrack  = 'https://icis.bgpost.bg/BulPostICIS-web-pub/track.jsf?ShipmentNo=' ;
	$reqURL           = $callEMSBulpostTrack . $parcel_id ;
	$html             = @file_get_contents ($reqURL) ;

	if($html === FALSE)
	{
		echo '<h2 class="alert">Сайтът на спедитора ограничава заявките. Опитайте да опресните страницата след няколко минути.</h2>' ;
		die();
	}

  $trim_start     = '<div class="ui-datatable-tablewrapper">' ;
	$trim_start_len	= strlen($trim_start) ;
	$trim_end				= '</div><div id="parcelTrackForm:waybillEventDatatable_paginator_bottom"' ;
  $output         = substr(strstr(strstr($html, $trim_start, false), $trim_end, true), $trim_start_len) ;

  $DOM            = new DOMDocument() ;
  $DOM->loadHTML('<?xml encoding="UTF-8">' . $output) ; // Encoding is very important!

	$rows				= $DOM->getElementsByTagName('tr') ;
	$rows_count = $rows->length ;
  $progress		= array() ;
	$record			= array() ;
  
  foreach ($rows as $row) {
		$cells = $row -> getElementsByTagName('td');

		foreach ($cells as $cell) {
			if (isset($cell->nodeValue))
			{
				array_push($record, $cell->nodeValue) ;
			}	else {
				array_push($record, "--") ;
			}
		}
		array_push ($progress, $record) ;
		$record = [] ;
	}

	$iterations = count($progress) - 1 ;

  for ($i = $iterations; $i > 0; $i--) {

    $opdate     = date('d.m.Y H:i', strtotime($progress[$i][0])) ; // Drop seconds from timestamp
    $opcountry  = $progress[$i][1] ;
    $oplocation = $progress[$i][2] ;
    $opstatus   = $progress[$i][3] ; // Do not use

		echo '<div class="monospaced">' ;
		echo '<span class="timestamp">' . $opdate . '</span>' ;
		echo '<span class="status">' . $oplocation . '</span>' ;
    echo '<span class="monoblocked" style="display: block; opacity: 0.75; "><i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;' . $opcountry . '</span>' ;
    echo '</div>' ;

	}
}