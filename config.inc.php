<?php
# General
define ('SITE_TITLE',					"") ; // Insert tracking website name inside dobule quotes, e.g. define ('SITE_TITLE', "Магазин Example.com: движение на доставките") ;
define ('SITE_URL',						"") ; // Insert tracking website address including protocol inside double quotes, e.g. define ('SITE_URL', "https://tracking.example.com") ;
define ('SITE_CONTACT_URL',		"") ; // Insert contact website address including protocol, e.g. define ('SITE_CONTACT_URL', "https://www.example.com/contact") ;
define ('LANGUAGE_DEFAULT',		"bg") ; // Determine default language for return queries. Specify either bg or en (small caps)

# ------- SPEEDY -------
# Speedy User details
define ('SPEEDY_USER', "") ;        // Insert username Speedy API username inside quotes, e.g. define ('SPEEDY_USER', "912345") ;
define ('SPEEDY_PASS', "") ;        // Insert password for Speedy API inside quotes, e.g. define ('SPEEDY_PASS', "12345678901") ;

# Speedy API details
define ('SPEEDY_API_BASE', "https://api.speedy.bg/v1/") ; // Do not change this unless instructed by the developer
define ('SPEEDY_API_CMD_TRACK', "track") ;                // Do not change this unless instructed by the developer


# ------- ECONT ---------
# Econt API details
define ('ECONT_API_BASE', "https://ee.econt.com/services/") ;                            // Do not change this unless instructed by the developer
define ('ECONT_API_CMD_TRACK', "Shipments/ShipmentService.getShipmentStatuses.json") ;   // Do not change this unless instructed by the developer


# ------- A1 POST -------
define('A1POST_URL_BASE_BG', "https://a1post.bg/track/") ;
define('A1POST_URL_BASE_EN', "https://a1post.bg/en/track/") ;


/* Determine courier according to tracking number format */
define('PATTERN_SPEEDY',	'/^(6)[0-9]{10}$/') ;
define('PATTERN_ECONT',   '/^(1)[0-9]{12}$/') ;
define('PATTERN_A1POST',  '/^(UR|LY|RS)[0-9]{9}(DE)$/') ;

/* **************************************************** */
/*
Speedy uses 11-character tracking numbers starting with 6
Econt uses 13-character tracking numbers starting with 10
A1 Post uses UPU format (XX123456789YY)
	UR: No tracking provided
	LY: Tracking provided, no signature on delivery
	RS: Tracking provided, require signature on delivery
	Trailing marker is always DE
*/
/* **************************************************** */