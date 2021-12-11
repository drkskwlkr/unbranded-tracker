<?php
# Some variables we will need

# Administrative
define ('SITE_TITLE',        "") ; // Insert tracking website name inside dobule quotes, e.g. define ('SITE_TITLE', "Магазин Example.com: движение на доставките") ;
define ('SITE_URL',          "") ; // Insert tracking website address including protocol inside double quotes, e.g. define ('SITE_URL', "https://tracking.example.com") ;
define ('SITE_CONTACT_URL',  "") ; // Insert contact website address including protocol, e.g. define ('SITE_CONTACT_URL', "https://www.example.com/contact") ;

# ------- SPEEDY -------
# Speedy User details
define ('SPEEDY_USER', "") ;        // Insert username Speedy API username inside quotes, e.g. define ('SPEEDY_USER', "912345") ;
define ('SPEEDY_PASS', "") ;        // Insert password for Speedy API inside quotes, e.g. define ('SPEEDY_PASS', "12345678901") ;
define ('SPEEDY_LANG', "bg") ;      // Do not change this unless instructed by the developer

# Speedy API details
define ('SPEEDY_API_BASE', "https://api.speedy.bg/v1/") ; // Do not change this unless instructed by the developer
define ('SPEEDY_API_CMD_TRACK', "track") ;                // Do not change this unless instructed by the developer

# Speedy API command syntax
$callSpeedyTrack = SPEEDY_API_BASE . SPEEDY_API_CMD_TRACK . '?userName=' . SPEEDY_USER . '&password=' . SPEEDY_PASS . '&language=' . SPEEDY_LANG . '&parcels=' ;


# ------- ECONT ---------
# Econt API details
define ('ECONT_API_BASE', "https://ee.econt.com/services/") ;                            // Do not change this unless instructed by the developer
define ('ECONT_API_CMD_TRACK', "Shipments/ShipmentService.getShipmentStatuses.json") ;   // Do not change this unless instructed by the developer

# Econt API syntax
$callEcontTrack = ECONT_API_BASE . ECONT_API_CMD_TRACK ;

# ------- A1 POST -------
define('A1POST_URL_BASE_BG', "https://a1post.bg/track/") ;
define('A1POST_URL_BASE_EN', "https://a1post.bg/en/track/") ;
