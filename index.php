<!DOCTYPE html>
<html lang="en">

<?php require_once( $_SERVER['DOCUMENT_ROOT'] . '/access.inc.php') ; ?>
<?php require_once( $_SERVER['DOCUMENT_ROOT'] . '/config.inc.php') ; ?>

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $SITE_TITLE ;?></title>
  <link rel="canonical" href="<?php echo $SITE_URL ; ?>" />
  <link rel="stylesheet" href="./style.css" />
</head>

<body>
	<!-- Main Area Begin -->
	<section id="main">
		<h1 class="maintitle">Информация за <span class="optional"> движението на </span>Вашата пратка</h1>		
		<?php require_once( $sitebasepath=$_SERVER['DOCUMENT_ROOT'] . '/track.php') ; ?>
	</section>
	<!-- Main Area End -->

	
	<!-- Marketing Messaging Area Begin -->
	<section id="additional">
		<p>&nbsp;</p>
	</section>
	<!-- Marketing Messaging Area End -->

	
	<!-- Pixel & Tracking Code Area Begin -->
  
  	<!-- Pixel & Tracking Code Area End -->
</body>
</html>
