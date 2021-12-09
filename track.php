<?php

/* Enable for Debugging Purposes only */
// ini_set('display_errors', 1) ;
// ini_set('display_startup_errors', 1) ;
// error_reporting(E_ALL) ;


require_once( $_SERVER['DOCUMENT_ROOT'] . '/config.inc.php' ) ;

// Determine  courier
// Speedy uses 11-character tracking numbers starting with 6
// Econt uses 13-character tracking numbers starting with 10
define("SPEEDY_COUNT", 11) ;
define("SPEEDY_START",  6) ;
define("ECONT_COUNT",  13) ;
define("ECONT_START",   1) ;
//define("CVC_COUNT",		  8) ;
//define("CVC_START",	  "#") ;

if ( isset ($_GET['p'] ) && !empty ($_GET['p'] ) )
{
	$parcel_id = htmlspecialchars($_GET['p']) ;
	$cid_len   = strlen($parcel_id) ;
	$cid_start = substr($parcel_id, 0, 1) ;
}
else
{
  /* Replace echo statement with something else if you want to modify */
  /* page behavior for people who land on page without a tracking no. */
  echo '<h2>Необходимо е да подадете заявка с номер на товарителница</h2>' ;
	die() ;
}

if ( SPEEDY_COUNT == $cid_len && SPEEDY_START == $cid_start )
{

 	echo '<h2>Доставка<span class="optional">та се изпълнява</span> чрез <span class="speedy">Спиди</span>. Хронология<span class="optional"> на събитията</span>:</h2>' ;

	/* SPEEDY START */

	$reqURL = $callSpeedyTrack . $parcel_id ;

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
	$operations	= $data['parcels']['0']['operations'] ;

	for($i=0; $i < count($operations); $i++) 	{
		$opdate = $operations[$i]['dateTime'] ;
		$opdate = strtotime($opdate) ;
		$opdate = date('d.m.Y H:i', $opdate) ;

		echo '<div class="monospaced">' ;
		echo  $opdate ;
		echo " &rarr; " ;
		echo '<span class="monoblocked">' . $operations[$i]['description'] . '</span>' ;
		echo '</div>' ;
		}
	}
	elseif ( ECONT_COUNT == $cid_len && ECONT_START == $cid_start )
	{

	/* ECONT START*/

  echo '<h2>Доставка<span class="optional">та се изпълнява</span> чрез <span class="econt">Еконт</span>. Хронология<span class="optional"> на събитията</span>:</h2>' ;

	$reqURL = $callEcontTrack ;
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

	$response		= json_decode($json_response, true) ;
	$operations	= $response['shipmentStatuses'][0]['status']['trackingEvents'] ;

	$terms = [
		"prepared"					=> "Поръчката е обработена.",
		"courier"						=> "Приета от куриер",
		"office"						=> "Складирана в офис",
		"courier_direction"	=> "Натоварена на линия",
		"client"						=> "Доставена на получателя",
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

  for ($i=0; $i < count($operations); $i++)
  {
    $opdate = $operations[$i]['time'] / 1000 ;
    $opdate = date('d.m.Y H:i', $opdate) ;
		//$action = $operations[$i]['destinationType'] ;
		$action = $terms[$operations[$i]['destinationType']] ;

		echo '<div class="monospaced">' ;
		echo $opdate ;
    echo " &rarr; " ;
		echo '<span class="monoblocked">' . $action . '</span> <span class="monoblocked">' . $operations[$i]['destinationDetails'] . '</span>' ;
		echo '</div>' ;
  	}
	}

	else
	{

	echo '<div style="width: 90%; padding: 12px 24px; "><pre>Не можем да разпознаем куриера по посочения номер на товарителница. <a href="' . SITE_CONTACT_URL . '">Свържете се с нас</a> за повече информация. </pre>' ;
	}
