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
		<div class="header">
			<h1 class="maintitle">Информация за <span class="optional"> движението на </span>Вашата пратка</h1>		
			<?php require_once( $sitebasepath=$_SERVER['DOCUMENT_ROOT'] . '/track.php') ; ?>
		</div>
	</section>
	<!-- Main Area End -->

	<section id="additional">
		<div class="extra-content">
			<p>&nbsp;</p>
		</div>
	</section>

	<!-- Pixel & Tracking Code Area Begin -->
  
  <!-- Pixel & Tracking Code Area End -->
</body>
</html>
