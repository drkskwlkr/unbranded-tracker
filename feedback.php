<?php

echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">' . "\n" ;
echo '<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">' . "\n" ;
echo '<style>
.css-button-google {
	font-family: Roboto, sans-serif;
	color: #FFFFFF;
	font-size: 16px;
	border-radius: 5px;
	border: 1px #3381ed solid;
	background: linear-gradient(180deg, #3d93f6 5%, #1e62d0 100%);
	text-shadow: 1px 1px 1px #1571cd;
	box-shadow: inset 1px 1px 2px 0px #97c4fe;
	cursor: pointer;
	display: inline-flex;
	align-items: center;
  margin-top: 12px;
  margin-bottom: 12px;
}
.css-button-google:hover {
	background: linear-gradient(180deg, #1e62d0 5%, #3d93f6 100%);
}
.css-button-google-icon {
	padding: 10px 16px;
	border-right: 1px solid rgba(255, 255, 255, 0.16);
	box-shadow: rgba(0, 0, 0, 0.14) -1px 0px 0px inset;
}
.css-button-google-icon i {
	position: relative;
	font-size: 24px;
}
.css-button-google-text {
	padding: 10px 18px;
}
</style>' . "\n" ;

/* Insert Google Review button */
function feedbackRequestGoogle () {
  echo '<div class="reviewarea">' ;
  echo '<p>Ако сте доволни от покупката, бихте ли оставили отзив за нас?</p>' ;
  echo '<a class="css-button-google">' . "\n" ;
	echo '<span class="css-button-google-icon"><i class="fa fa-google" aria-hidden="true"></i></span>' . "\n" ;
	echo '<span class="css-button-google-text">Препоръчай</span>' . "\n" ;
  echo '</a>' ;
  echo '</div>' ;
}

