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
define('CVC_API_BASE', "https://cvc.bg/system/integration/tracking.do") ;


/* Determine courier according to tracking number format */
define('PATTERN_SPEEDY',			'/^(6)[0-9]{10}$/') ;
define('PATTERN_ECONT',				'/^(1)[0-9]{12}$/') ;
define('PATTERN_A1POST',			'/^(UR|LY|RS)[0-9]{9}(DE)$/') ;
define('PATTERN_LEOEXPRES',		'/^(6|7)[0-9]{7}$/') ;
define('PATTERN_CVC',		      '/^[0]{2}(09|10)[0-9]{4}$/') ;
define('PATTERN_ELTAGR',      '/^(HB)[0-9]{9}(GR)$/') ;
define('PATTERN_BGPOST',			'/^(CP|VV|RI|EB|DB)[0-9]{9}(BG)$/') ;

/* ************************************************************************* */
/*
Speedy uses 11-digit tracking numbers starting with 6
Econt uses 13-digit tracking numbers starting with 10
A1 Post uses UPU format (XX123456789YY)
	UR: No tracking provided
	LY: Tracking provided, no signature on delivery
	RS: Tracking provided, require signature on delivery
	Trailing marker is always DE
Leo Expres uses 8-digit tracking numbers; the first one is either a 6 or a 7
CVC uses uses 8-digit tracking numbers; we presume they increase linearly and
are currently in the 90,000 — 100,000 range.
BG Post uses UPU format as well. Confirmed codes:
	CP: Tracked Int'l parcel
	VV: Tracked Int'l parcel (valuable, with declared value)
	RI: Tracked Int'l mail or small package; requires signature on delivery
	EB: EMS package (will be tracked separately!)
	DB: Domestic Courier Service (rarely used)
	Trailing marker is always BG
*/
/* ************************************************************************* */
