<?php
# ------- SPEEDY -------
# Speedy API details
define ('SPEEDY_API_BASE', "https://api.speedy.bg/v1/") ; // Do not change this unless instructed by the developer
define ('SPEEDY_API_CMD_TRACK', "track") ;                // Do not change this unless instructed by the developer
define ('SPEEDY_API_CMD_RCV_OFFICE', 'shipment/info') ;		// Do not change this unless instructed by the developer


# ------- ECONT ---------
# Econt API details
define ('ECONT_API_BASE', "https://ee.econt.com/services/") ;                            // Do not change this unless instructed by the developer
define ('ECONT_API_CMD_TRACK', "Shipments/ShipmentService.getShipmentStatuses.json") ;   // Do not change this unless instructed by the developer


# ------- A1 POST -------
define('A1POST_URL_BASE_BG', "https://a1post.bg/track/") ;
define('A1POST_URL_BASE_EN', "https://a1post.bg/en/track/") ;


# ------- Leo Expres ----
define('LEOEXPRES_URL_BASE', "https://leoexpres.bg/include/ajax/get_trace.php?wb=") ;


# ------- CVC -----------
define('CVC_API_BASE', "https://my.e-cvc.bg/track?wb=") ;


/* Determine courier according to tracking number format */
define('PATTERN_SPEEDY',			'/^(6)[0-9]{10}$/') ;
define('PATTERN_ECONT',				'/^(10|53)[0-9]{11}$/') ;
define('PATTERN_A1POST',			'/^(UR|LY|RS)[0-9]{9}(DE)$/') ;
define('PATTERN_EVROPAT',     			'/^91\d{8}$/') ;
define('PATTERN_CVC',		      		'/^\d{8}$/') ;
define('PATTERN_ELTAGR',      			'/^(HB)[0-9]{9}(GR)$/') ;
define('PATTERN_BGPOST',			'/^(CP|RI|CV|VV)[0-9]{9}(BG)$/') ;
define('PATTERN_EMSBULPOST',			'/^(ED|EE)[0-9]{9}(BG)$/') ;

/* ************************************************************************* */
/*
Speedy uses 11-digit tracking numbers starting with 6
Econt uses 13-digit tracking numbers starting with 10 or 53
A1 Post uses UPU format (XX123456789YY)
	UR: No tracking provided
	LY: Tracking provided, no signature on delivery
	RS: Tracking provided, require signature on delivery
	Trailing marker is always DE
Leo Expres is retired (company folded)
Evropat uses 10-digit tracking numbers; the first two are assumed to be 91
CVC uses uses 8-digit tracking numbers;
BG Post uses UPU format as well. Confirmed codes:
	CP: Tracked Int'l parcel
	CV: Tracked Int'l parcel (valuable, with declared value)
	RI: Tracked Int'l mail or small package; requires signature on delivery
	VV: Tracked Int'l mail or small package; (valuable, with declared value)
	Trailing marker is always BG
EMS Bulpost uses UPU format as well. Confirmed codes:
	ED, EE: EMS package (will be tracked separately!)
	DB: Domestic Courier Service (rarely used)
	Trailing marker is always BG
	*/
/* ************************************************************************* */
